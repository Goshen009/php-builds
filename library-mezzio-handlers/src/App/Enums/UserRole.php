<?php

namespace App\Enums;

enum UserRole: string {
    case Admin = 'admin';
    case Regular = 'regular';
}