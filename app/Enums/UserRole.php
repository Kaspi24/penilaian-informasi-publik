<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPERADMIN = "SUPERADMIN";
    case ADMIN      = 'ADMIN';
    case JURY       = 'JURY';
    case RESPONDENT = 'RESPONDENT';

    public function toString() {
        return match ($this) {
            UserRole::SUPERADMIN    => "SUPERADMIN",
            UserRole::ADMIN         => "ADMIN",
            UserRole::JURY          => "JURI",
            UserRole::RESPONDENT    => "RESPONDEN"
        };
    }
}