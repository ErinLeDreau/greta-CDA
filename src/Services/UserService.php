<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Managers\RepositoryManager;
use App\Models\User;
use App\Models\Enums\UserRoleEnum;
use DateTime;
use Exception;

class UserService
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
     * @param UserDTO $dto
     * @throws Exception
     * @return User
     */
    public function createUser(UserDTO $dto): User
    {
        $this->validateUserDTO($dto);

        $existingUser = $this->repositoryManager
            ->userRepository
            ->findByEmail($dto->email);

        if ($existingUser) {
            throw new Exception("Email already used");
        }

        $user = new User(
            0,
            $dto->firstname,
            $dto->lastname,
            $dto->email,
            password_hash($dto->password, PASSWORD_BCRYPT),
            $dto->role,
            new DateTime(),
            new DateTime()
        );

        return $this->repositoryManager
            ->userRepository
            ->create($user);
    }

    /**
     * @param UserDTO $dto
     * @throws Exception
     * @return User
     */
    public function updateUser(UserDTO $dto): User
    {
        if (!$dto->id) {
            throw new Exception("User id is required");
        }

        $this->validateUserDTO($dto, false);

        $user = $this->repositoryManager
            ->userRepository
            ->findById($dto->id);

        if (!$user) {
            throw new Exception("User not found");
        }

        $existingUser = $this->repositoryManager
            ->userRepository
            ->findByEmail($dto->email);

        if (
            $existingUser
            && $existingUser->getId() !== $user->getId()
        ) {
            throw new Exception("Email already used");
        }

        $user->setFirstname($dto->firstname);
        $user->setLastname($dto->lastname);
        $user->setEmail($dto->email);

        $user->setRole($dto->role);

        if (!empty($dto->password)) 
        {
            $user->setPasswordHash(password_hash($dto->password,PASSWORD_BCRYPT));
        }

        $user->setUpdatedAt(new DateTime());

        return $this->repositoryManager
            ->userRepository
            ->update($user);
    }

    /**
     * @param int $id
     * @throws Exception
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $user = $this->repositoryManager
            ->userRepository
            ->findById($id);

        if (!$user) {
            throw new Exception("User not found");
        }
        $this->repositoryManager->reservationRepository->deleteByUserId($id);
        $this->repositoryManager->userRepository->delete($id);
    }

    /**
     * @param UserDTO $dto
     * @param bool $requirePassword
     * @throws Exception
     * @return void
     */
    private function validateUserDTO(UserDTO $dto,bool $requirePassword = true): void 
    {
        if (empty(trim($dto->firstname))) 
        {
            throw new Exception("Firstname is required");
        }

        if (empty(trim($dto->lastname))) 
        {
            throw new Exception("Lastname is required");
        }

        if (empty(trim($dto->email))) 
        {
            throw new Exception("Email is required");
        }

        if (!filter_var($dto->email,FILTER_VALIDATE_EMAIL)) 
        {
            throw new Exception("Invalid email");
        }

        if ($requirePassword && empty($dto->password)) 
        {
            throw new Exception("Password is required");
        }

        if (!$dto->role) {
            throw new Exception("Invalid role");
        }
    }
}