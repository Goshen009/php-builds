<?php

namespace App\Enums;

enum FlashMessageEnum: string {
    case FLASH_ERROR = 'error';
    case FLASH_WARNING = 'warning';
    case FLASH_INFO = 'info';
    case FLASH_SUCCESS = 'success';
}