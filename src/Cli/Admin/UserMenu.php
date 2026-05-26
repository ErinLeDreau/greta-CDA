<?php

namespace App\Cli\Admin;

use App\Cli\AuthContext;
use App\Managers\RepositoryManager;
use App\Services\UserService;

class UserMenu
{
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;
    private UserService $userService;

    public function __construct(RepositoryManager $repositoryManager,AuthContext $authContext) 
    {
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;
        $this->userService = new UserService($repositoryManager);
    }
    public function displayMenu(): void
    {
        while (true) {

            echo PHP_EOL;
            echo "===== USERS =====" . PHP_EOL;
            echo "1. List users" . PHP_EOL;
            echo "2. Create user" . PHP_EOL;
            echo "3. Delete user" . PHP_EOL;
            echo "0. Back" . PHP_EOL;

            $choice = readline("Choice: ");

            switch ($choice) {
                case "1":
                    $this->listUsers();
                    break;

                case "2":
                    $this->createUser();
                    break;

                case "3":
                    $this->deleteUser();
                    break;

                case "0":
                    return;
            }
        }
    }

    private function listUsers(): void
    {
        $users = $this->repositoryManager
            ->userRepository
            ->findAll();

        foreach ($users as $user) {
            echo $user->getId()
                . " | " . $user->getEmail()
                . " | " . $user->getRole()->value
                . PHP_EOL;
        }
    }

    private function createUser(): void
    {
        while (true) {
            try {
                $firstname = readline("Firstname: ");
                $lastname = readline("Lastname: ");
                $email = readline("Email: ");
                $password = readline("Password: ");

                $roleChoice = readline("Role (1 = ADMIN, 2 = TEACHER): ");

                $roleMap = [
                    '1' => \App\Models\Enums\UserRoleEnum::ADMIN,
                    '2' => \App\Models\Enums\UserRoleEnum::TEACHER,
                ];

                if (!isset($roleMap[$roleChoice])) {
                    throw new \InvalidArgumentException("Invalid role choice");
                }
    
                $dto = new \App\DTO\UserDTO(
                    null,
                    $firstname,
                    $lastname,
                    $email,
                    $password,
                    $roleMap[$roleChoice]
                );

                $this->userService->createUser($dto);

                echo "User created successfully" . PHP_EOL;
                break;

            } catch (\InvalidArgumentException $e) {
                echo "Input error: " . $e->getMessage() . PHP_EOL;
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }
        }
    }

   private function deleteUser(): void
{
    try {
        $id = readline("User ID to delete: ");

        if (!is_numeric($id)) {
            throw new \InvalidArgumentException("ID must be a number");
        }

        $confirm = readline("This action will delete all linked Reservations.". PHP_EOL ."Are you sure? (y/n): ");

        if (strtolower($confirm) !== 'y') {
            echo "Cancelled." . PHP_EOL;
            return;
        }

        $this->userService->deleteUser((int)$id);

        echo "User deleted successfully" . PHP_EOL;

    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
}
}