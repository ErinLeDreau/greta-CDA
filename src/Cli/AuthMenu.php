<?php

namespace App\Cli;

use App\Managers\RepositoryManager;

class AuthMenu
{
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;

    public function __construct(
        RepositoryManager $repositoryManager,
        AuthContext $authContext
    ) {
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;
    }

    public function login(): void
    {
        $email = trim(readline("Email: "));
        $password = trim(readline("Password: "));

        $user = $this->repositoryManager
            ->userRepository
            ->findByEmail($email);

        if (!$user) {
            echo "User not found" . PHP_EOL;
            return;
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            echo "Wrong password" . PHP_EOL;
            return;
        }

        $this->authContext->currentUser = $user;

        echo "Logged in as: " . $user->getEmail() . PHP_EOL;
    }

    public function logout(): void
    {
        if (!$this->authContext->currentUser) {
            echo "No user logged in" . PHP_EOL;
            return;
        }

        echo "Goodbye " . $this->authContext->currentUser->getEmail() . PHP_EOL;

        $this->authContext->currentUser = null;
    }
}