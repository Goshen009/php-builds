<?php

namespace App\Controllers;

use PDO;

use Core\{Filter, Router};
use App\Enums\FlashMessageEnum;
use App\Repositories\UserRepository;

class AuthController {
    function __construct(
        private PDO $db,
        private UserRepository $userRepository,
        private Router $router,
        private Filter $filter
    ) { }

    public function register(): void {        
        $filters = [
            'name' => ['string', 'required', 'alphanumeric', 'between : 3, 10', 'unique: users, name'],
            'email' => ['email', 'required', 'email', 'unique: users, email'],
            'password' => ['string', 'required', 'secure'],
            'confirm-password' => ['string', 'required', 'same : password'],
        ];
    
        [$inputs, $errors] = $this->filter->filter($this->db, $_POST, $filters);

        if ($errors) {
            $this->router->redirect(url: 'register.php', items: [
                'inputs' => $inputs,
                'errors' => $errors
            ]);
        }

        $this->userRepository->register($inputs);
        $this->userRepository->login($inputs); // this actually returns a boolean but it's not necessary here because the password will always be correct.

        $this->router->redirect(
            url: 'home.php',
            message: 'Your account has been created successsfully.'
        );
    }

    public function login(): void {
        $filters = [
            'name' => ['string', 'required'],
            'password' => ['string', 'required']
        ];

        [$inputs, $errors] = $this->filter->filter($this->db, $_POST, $filters);

        if ($errors) {
            $this->router->redirect(url: 'login.php', items: [
                'inputs' => $inputs,
                'errors' => $errors
            ]);
        }

        $result = $this->userRepository->login($inputs);

        if ($result) {
            $this->router->redirect(
                url: 'home.php',
                message: 'Your login was successful.'
            );
        } else {
            $this->router->redirect(
                url: 'login.php', 
                items: [
                    'inputs' => $inputs,
                ],
                message: 'Invalid name or password',
                type: FlashMessageEnum::FLASH_ERROR
            );
        }
    }

    public function logout() : void {
        unset($_SESSION['user']);
        session_destroy();
            
        $this->router->redirect(
            url: 'login.php'
        );
    }
}