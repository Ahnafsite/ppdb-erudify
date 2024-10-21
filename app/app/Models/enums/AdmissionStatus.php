<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum AdmissionStatus: string implements HasLabel
{
    case VERIFIED = 'VERIFIED';
    case PENDING = 'PENDING';
    case REVISION = 'REVISION';
    case REJECTED = 'REJECTED';
    case ACCEPTED = 'ACCEPTED';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::VERIFIED => 'Terverifikasi',
            self::PENDING => 'Menunggu Verifikasi',
            self::REVISION => 'Revisi',
            self::REJECTED => 'Ditolak',
            self::ACCEPTED => 'Diterima',
        };
    }
}