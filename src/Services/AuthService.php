<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\Managers\RepositoryManager;
use App\Models\User;
use Exception;

class AuthService
{
    private RepositoryManager $repositoryManager;

    /**
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager) 
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param LoginDTO $loginDTO
     * @throws Exception
     * @return User
     */
    public function login(LoginDTO $loginDTO): User 
    {
        $this->validateLoginDTO($loginDTO);

        $user = $this->repositoryManager
            ->userRepository
            ->findByEmail($loginDTO->email);

        if (!$user) {
            throw new Exception(
                "Invalid credentials"
            );
        }

        if (!password_verify($loginDTO->password,$user->getPasswordHash())) 
        {
            throw new Exception(
                "Invalid credentials"
            );
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user->getId();

        return $user;
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
    }

    /**
     * @return User|null
     */
    public function getAuthenticatedUser(): ?User
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return $this->repositoryManager
            ->userRepository
            ->findById(
                (int) $_SESSION['user_id']
            );
    }

    /**
     * @throws Exception
     * @return User
     */
    public function requireAuthentication(): User
    {
        $user = $this->getAuthenticatedUser();

        if (!$user) {
            throw new Exception(
                "Authentication required"
            );
        }

        return $user;
    }

    /**
     * @throws Exception
     * @return User
     */
    public function requireAdmin(): User
    {
        $user = $this->requireAuthentication();

        if (!$user->isAdmin()) {
            throw new Exception(
                "Admin access required"
            );
        }

        return $user;
    }

    /**
     * @param LoginDTO $loginDTO
     * @throws Exception
     * @return void
     */
    private function validateLoginDTO(
        LoginDTO $loginDTO
    ): void {

        if (empty(trim($loginDTO->email))) 
        {
            throw new Exception(
                "Email is required"
            );
        }

        if (!filter_var($loginDTO->email,FILTER_VALIDATE_EMAIL)) 
        {
            throw new Exception("Invalid email");
        }

        if (empty($loginDTO->password)) 
        {
            throw new Exception("Password is required");
        }
    }
}