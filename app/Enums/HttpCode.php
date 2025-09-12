<?php
namespace App\Enums;

enum HttpCode: int
{
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case CONFLICT = 409;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case INTERNAL_ERROR = 500;

    public function description(): string
    {
        return match ($this) {
            self::OK             => 'Request berhasil diproses',
            self::CREATED        => 'Resource berhasil dibuat',
            self::BAD_REQUEST    => 'Request tidak valid',
            self::UNAUTHORIZED   => 'Autentikasi gagal',
            self::FORBIDDEN      => 'Akses ditolak',
            self::NOT_FOUND      => 'Resource tidak ditemukan',
            self::CONFLICT       => 'Terjadi konflik data',
            self::INTERNAL_ERROR => 'Kesalahan server',
        };
    }
}
