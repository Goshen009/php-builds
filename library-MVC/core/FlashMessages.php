<?php

namespace Core;

class FlashMessages {
    private function create_flash_message(string $name, string $message, string $type): void {
        if (isset($_SESSION['FLASH_MESSAGES'][$name])) {
            unset($_SESSION['FLASH_MESSAGES'][$name]);
        }

        $_SESSION['FLASH_MESSAGES'][$name] = ['message' => $message, 'type' => $type];
    }

    private function format_flash_message(array $flash_message): string {
        return sprintf('<div class="alert alert-%s">%s</div>',
            $flash_message['type'],
            $flash_message['message']
        );
    }

    private function display_flash_message(string $name): void {
        if (!isset($_SESSION['FLASH_MESSAGES'][$name])) {
            return;
        }

        $flash_message = $_SESSION['FLASH_MESSAGES'][$name];
        unset($_SESSION['FLASH_MESSAGES'][$name]);

        echo $this->format_flash_message($flash_message);
    }

    private function display_all_flash_messages(): void {
        if (!isset($_SESSION['FLASH_MESSAGES'])) {
            return;
        }

        $flash_messages = $_SESSION['FLASH_MESSAGES'];
        unset($_SESSION['FLASH_MESSAGES']);

        foreach ($flash_messages as $flash_message) {
            echo $this->format_flash_message($flash_message);
        }
    }

    public function flash(string $name = '', string $message = '', string $type = ''): void {                
        if ($name !== '' && $message !== '' && $type !== '') {
            $this->create_flash_message($name, $message, $type);
        } elseif ($name !== '' && $message === '' && $type === '') {
            $this->display_flash_message($name);
        } elseif ($name === '' && $message === '' && $type === '') {
            $this->display_all_flash_messages();
        }
    }
}

?>