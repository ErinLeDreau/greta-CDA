<?php

namespace App\Managers;

use App\Core\Database\DatabaseFactory;
use App\Repositories\UserRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReservationRepository;

class RepositoryManagerFactory
{
    public static function create(): RepositoryManager
    {
        $db = DatabaseFactory::create();

        $userRepository = new UserRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $materialRepository = new MaterialRepository($db, $categoryRepository);
        $reservationRepository = new ReservationRepository($db, $userRepository, $materialRepository);

        return new RepositoryManager(
            $userRepository ,
            $categoryRepository,
            $materialRepository,
            $reservationRepository
        );
    }
}