<?php

namespace App\Repositories;

use App\Core\Database\Database;
use App\Models\User;
use App\Models\Enums\UserRoleEnum;
use DateTime;

class UserRepository
{

    private Database $database;

    /**
     * @param Database $database
     */
    public function __construct(Database $database) {
        $this->database = $database;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        $data = $this->database->builder()
            ->table('users')
            ->where('id', '=', $id)
            ->first();

        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        $data = $this->database->builder()
            ->table('users')
            ->where('email', '=', $email)
            ->first();

        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $rows = $this->database->builder()
            ->table('users')
            ->get();

        return array_map(fn($r) => $this->hydrate($r), $rows);
    }

    /**
     * @param User $user
     * @return User
     */
    public function create(User $user): User
    {
        $id = $this->database->builder()
            ->table('users')
            ->insert([
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'password_hash' => $user->getPasswordHash(),
                'role' => $user->getRole()->value,
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);

        $user->setId($id);

        return $user;
    }

    /**
     * @param User $user
     * @return int
     */
    public function update(User $user): int
    {
        return $this->database->builder()
            ->table('users')
            ->where('id', '=', $user->getId())
            ->update([
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'password_hash' => $user->getPasswordHash(),
                'role' => $user->getRole()->value,
                'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->database->builder()
            ->table('users')
            ->where('id', '=', $id)
            ->delete();
    }

    /**
     * @param array $data
     * @return User
     */
    private function hydrate(array $data): User
    {
        return new User(
            (int) $data['id'],
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $data['password_hash'],
            UserRoleEnum::from($data['role']),
            new DateTime($data['created_at']),
            new DateTime($data['updated_at'])
        );
    }
}