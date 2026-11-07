# ===========================================================================
#  Setup lingkungan Campus Lost & Found (FindUMB) - Windows
#  Jalankan di PowerShell sebagai Administrator:
#     Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
#     .\setup-windows.ps1
# ===========================================================================

$ErrorActionPreference = "Stop"

function Info($m) { Write-Host "[*] $m" -ForegroundColor Cyan }
function Ok($m)   { Write-Host "[OK] $m" -ForegroundColor Green }
function Warn($m) { Write-Host "[!] $m" -ForegroundColor Yellow }
function Fail($m) { Write-Host "[X] $m" -ForegroundColor Red }

Info "Mulai setup lingkungan FindUMB..."

# ---------------------------------------------------------------------------
# 1. Cek winget
# ---------------------------------------------------------------------------
if (-not (Get-Command winget -ErrorAction SilentlyContinue)) {
    Fail "winget tidak ditemukan. Install 'App Installer' dari Microsoft Store dulu."
    exit 1
}
Ok "winget tersedia"

# ---------------------------------------------------------------------------
# 2. Install Git
# ---------------------------------------------------------------------------
if (Get-Command git -ErrorAction SilentlyContinue) {
    Ok "Git sudah terpasang ($(git --version))"
} else {
    Info "Install Git..."
    winget install --id Git.Git -e --source winget --accept-package-agreements --accept-source-agreements
    Ok "Git selesai diinstall"
}

# ---------------------------------------------------------------------------
# 3. Install PHP 8.3 (thread-safe, kompatibel kalau nanti pakai Apache)
# ---------------------------------------------------------------------------
$phpInstalled = Get-Command php -ErrorAction SilentlyContinue
if ($phpInstalled) {
    $ver = (php -r "echo PHP_VERSION;")
    Ok "PHP sudah terpasang: $ver"
} else {
    Info "Install PHP 8.3..."
    winget install --id PHP.PHP.8.3 -e --source winget --accept-package-agreements --accept-source-agreements
    Ok "PHP selesai diinstall"
}

# Muat ulang PATH supaya php langsung kebaca di sesi ini
$env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" +
            [System.Environment]::GetEnvironmentVariable("Path","User")

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Warn "php belum ada di PATH sesi ini."
    Warn "Tutup PowerShell, buka baru, lalu jalankan ulang script ini."
    Warn "Atau tambahkan manual folder PHP ke PATH (System Properties > Environment Variables)."
    exit 1
}
Ok "PHP terdeteksi: $((php -r 'echo PHP_VERSION;'))"

# ---------------------------------------------------------------------------
# 4. Siapkan php.ini + aktifkan ekstensi wajib
# ---------------------------------------------------------------------------
Info "Menyiapkan php.ini..."

$phpDir = Split-Path (Get-Command php).Source -Parent
$iniPath = Join-Path $phpDir "php.ini"

if (-not (Test-Path $iniPath)) {
    $devIni = Join-Path $phpDir "php.ini-development"
    if (Test-Path $devIni) {
        Copy-Item $devIni $iniPath
        Ok "php.ini dibuat dari php.ini-development"
    } else {
        Fail "php.ini-development tidak ditemukan di $phpDir"
        exit 1
    }
} else {
    Ok "php.ini sudah ada: $iniPath"
}

# Ekstensi yang dibutuhkan proyek ini
$extensions = @("curl","fileinfo","mbstring","openssl","pdo_pgsql","pgsql","zip","gd","intl")

$content = Get-Content $iniPath -Raw

# Pastikan extension_dir menunjuk ke folder ext yang benar
$extDir = Join-Path $phpDir "ext"
if ($content -match '(?m)^;?extension_dir\s*=') {
    $content = $content -replace '(?m)^;?extension_dir\s*=.*$', "extension_dir=`"$extDir`""
} else {
    $content += "`nextension_dir=`"$extDir`"`n"
}

$changed = @()
foreach ($ext in $extensions) {
    $dll = Join-Path $extDir "php_$ext.dll"
    if (-not (Test-Path $dll)) {
        Warn "  php_$ext.dll tidak ada di folder ext - dilewati"
        continue
    }
    # Nyalakan kalau masih dikomentari
    if ($content -match "(?m)^;extension=$ext\s*$") {
        $content = $content -replace "(?m)^;extension=$ext\s*$", "extension=$ext"
        $changed += $ext
    } elseif ($content -notmatch "(?m)^extension=$ext\s*$") {
        $content += "`nextension=$ext`n"
        $changed += $ext
    }
}

Set-Content -Path $iniPath -Value $content -Encoding UTF8
if ($changed.Count -gt 0) {
    Ok "Ekstensi diaktifkan: $($changed -join ', ')"
} else {
    Ok "Semua ekstensi sudah aktif"
}

# ---------------------------------------------------------------------------
# 5. Verifikasi ekstensi kritis
# ---------------------------------------------------------------------------
Info "Verifikasi ekstensi..."
$loaded = php -m
$critical = @("pdo_pgsql","pgsql","curl","fileinfo","mbstring","openssl")
$missing = @()
foreach ($c in $critical) {
    if ($loaded -notcontains $c) { $missing += $c }
}

if ($missing.Count -gt 0) {
    Fail "Ekstensi belum aktif: $($missing -join ', ')"
    Warn "Coba tutup & buka PowerShell baru, lalu cek: php -m"
    Warn "Kalau masih gagal, pastikan baris extension=$($missing[0]) ada di $iniPath"
} else {
    Ok "Semua ekstensi wajib aktif: $($critical -join ', ')"
}

# ---------------------------------------------------------------------------
# 6. Install Composer (tidak tersedia di winget, jadi ambil composer.phar)
# ---------------------------------------------------------------------------
Info "Menyiapkan Composer..."

$composerDir = Join-Path $env:LOCALAPPDATA "composer"
$composerPhar = Join-Path $composerDir "composer.phar"

if (-not (Test-Path $composerDir)) { New-Item -ItemType Directory -Path $composerDir | Out-Null }

if (Test-Path $composerPhar) {
    Ok "composer.phar sudah ada"
} else {
    Info "Download composer.phar..."
    try {
        Invoke-WebRequest -Uri "https://getcomposer.org/download/latest-stable/composer.phar" `
            -OutFile $composerPhar -UseBasicParsing
        Ok "composer.phar terunduh"
    } catch {
        Fail "Gagal download composer.phar: $_"
        Warn "Download manual dari https://getcomposer.org/download/ lalu simpan ke $composerDir"
        exit 1
    }
}

# Buat wrapper 'composer.bat' supaya bisa dipanggil sebagai 'composer'
$composerBat = Join-Path $composerDir "composer.bat"
Set-Content -Path $composerBat -Encoding ASCII -Value "@echo off`r`nphp `"%~dp0composer.phar`" %*"

$userPath = [System.Environment]::GetEnvironmentVariable("Path","User")
if ($userPath -notlike "*$composerDir*") {
    [System.Environment]::SetEnvironmentVariable("Path", "$userPath;$composerDir", "User")
    $env:Path += ";$composerDir"
    Ok "Folder Composer ditambahkan ke PATH"
}

Ok "Composer siap: $((php $composerPhar --version))"

# ---------------------------------------------------------------------------
# 7. Ringkasan
# ---------------------------------------------------------------------------
Write-Host ""
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host " SETUP SELESAI" -ForegroundColor Green
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host " PHP      : $(php -r 'echo PHP_VERSION;')"
Write-Host " php.ini  : $iniPath"
Write-Host " Composer : $composerPhar"
Write-Host " Git      : $(git --version)"
Write-Host ""
Write-Host "Langkah berikutnya (tutup & buka PowerShell baru dulu):" -ForegroundColor Yellow
Write-Host "   cd `"$PSScriptRoot`""
Write-Host "   composer install"
Write-Host "   copy .env.example .env"
Write-Host "   php artisan key:generate"
Write-Host "   php artisan serve"
Write-Host ""
Write-Host "Jangan lupa isi kredensial Supabase di .env (DB_* dan SUPABASE_*)" -ForegroundColor Yellow
