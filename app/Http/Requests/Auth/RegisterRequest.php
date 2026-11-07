<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $domains = config('findumb.campus_email_domains', []);

        $emailRules = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'];

        // BR-01 — hanya domain kampus yang diizinkan (DEC-016 masih konfigurabel).
        if (! empty($domains)) {
            $emailRules[] = function (string $attribute, mixed $value, \Closure $fail) use ($domains) {
                $domain = strtolower((string) substr((string) strrchr((string) $value, '@'), 1));

                if (! in_array($domain, array_map('strtolower', $domains), true)) {
                    $fail('Email harus menggunakan domain resmi kampus ('.implode(', ', $domains).').');
                }
            };
        }

        return [
            'name' => ['required', 'string', 'max:120'],
            'nim' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => $emailRules,
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email kampus wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui aturan penggunaan.',
        ];
    }
}
