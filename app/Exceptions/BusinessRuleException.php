<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Pelanggaran aturan bisnis yang aman ditampilkan ke pengguna
 * (tidak membocorkan detail internal).
 */
class BusinessRuleException extends RuntimeException
{
    public function __construct(
        public readonly string $userMessage,
        public readonly int $statusCode = 422,
    ) {
        parent::__construct($userMessage);
    }
}
