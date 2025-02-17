<?php

namespace Core;

class Flash {
    const FLASH = 'FLASH_MESSAGES';

    const FLASH_ERROR = 'error';
    const FLASH_WARNING = 'warning';
    const FLASH_INFO = 'info';
    const FLASH_SUCCESS = 'success';

    static public function create_flash_message(string $name, string $message, string $type): void {
        if (isset($_SESSION[self::FLASH][$name])) {
            unset($_SESSION[self::FLASH][$name]);
        }

        $_SESSION[self::FLASH][$name] = ['message' => $message, 'type' => $type];
    }

    static public function format_flash_message(array $flash_message): string {
        return sprintf('<div class="alert alert-%s">%s</div>',
            $flash_message['type'],
            $flash_message['message']
        );
    }

    static public function display_flash_message(string $name): void {
        if (!isset($_SESSION[self::FLASH][$name])) {
            return;
        }

        $flash_message = $_SESSION[self::FLASH][$name];
        unset($_SESSION[self::FLASH][$name]);

        echo self::format_flash_message($flash_message);
    }

    static public function display_all_flash_messages(): void {
        if (!isset($_SESSION[self::FLASH])) {
            return;
        }

        $flash_messages = $_SESSION[self::FLASH];
        unset($_SESSION[self::FLASH]);

        foreach ($flash_messages as $flash_message) {
            echo self::format_flash_message($flash_message);
        }
    }

    static public function flash(string $name = '', string $message = '', string $type = ''): void {                
        if ($name !== '' && $message !== '' && $type !== '') {
            self::create_flash_message($name, $message, $type);
        } elseif ($name !== '' && $message === '' && $type === '') {
            self::display_flash_message($name);
        } elseif ($name === '' && $message === '' && $type === '') {
            self::display_all_flash_messages();
        }
    }
}

?>