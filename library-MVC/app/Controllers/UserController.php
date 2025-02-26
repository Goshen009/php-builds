<?php

namespace App\Controllers;

use PDO;
use App\Enums\FlashMessageEnum;
use Core\{Router, Filter, Helpers};
use App\Repositories\UserRepository;

class UserController {
    function __construct(
        private PDO $db,
        private UserRepository $userRepository,
        private Router $router,
        private Filter $filter
    ) { }

    public function make_admin() {
        $this->userRepository->set_admin_status($_GET['id'], 1);

        $this->router->redirect(
            url: 'edit-users.php',
            message: "You have made '{$_GET['name']}' an admin."
        );
    }

    public function make_regular() {
        $this->userRepository->set_admin_status($_GET['id'], 0);

        $this->router->redirect(
            url: 'edit-users.php',
            message: "You have made '{$_GET['name']}' a regular user."
        );
    }

    public function delete_user() {
        $this->userRepository->delete_user($_GET['id']);

        $this->router->redirect(
            url: 'edit-users.php',
            message: "You have deleted '{$_GET['name']}' from the database."
        );
    }

    public function edit_profile() {
        $filters = [
            'name' => ['string', 'required', 'alphanumeric', 'between : 3, 10', "changed: {$_SESSION['user']['name']}, users, name"],
            'email' => ['email', 'required', 'email', "changed: {$_SESSION['user']['email']}, users, email"],
        ];

        [$inputs, $errors] = $this->filter->filter($this->db, $_POST, $filters);

        if ($errors) {
            $this->router->redirect(url: 'edit-profile.php', items: [
                'inputs' => $inputs,
                'errors' => $errors
            ]);
        }

        $result = $this->userRepository->edit_profile(Helpers::get_current_user()->id, $inputs);

        if ($result) {
            $this->router->redirect(
                url: 'home.php',
                message: 'Your profile has been edited.'
            );
        } else {
            $this->router->redirect(
                url: 'edit_profile.php', 
                items: [
                    'inputs' => $inputs,
                ],
                message: 'There was an issue editing your profile',
                type: FlashMessageEnum::FLASH_ERROR
            );
        }
    }

    public function change_password() {
        $filters = [
            'current-password' => ['string', 'required'],
            'new-password' => ['string', 'required', 'secure'],
            'confirm-password' => ['string', 'required', 'same : new-password'],
        ];

        [$inputs, $errors] = $this->filter->filter($this->db, $_POST, $filters);

        if ($errors) {
            $this->router->redirect(url: 'change-password.php', items: [
                'errors' => $errors
            ]);
        }

        $result = $this->userRepository->change_password(Helpers::get_current_user()->id, $inputs['current-password'], $inputs['new-password']);

        if ($result) {
            $this->router->redirect(
                url: 'home.php',
                message: 'Your password has been changed.'
            );
        } else {
            $this->router->redirect(
                url: 'change-password.php',
                message: 'Invalid password',
                type: FlashMessageEnum::FLASH_ERROR
            );
        }
    }
}