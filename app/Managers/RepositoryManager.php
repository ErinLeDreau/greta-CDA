<?php

namespace App\Managers;

use App\Repositories\UserRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReservationRepository;

class RepositoryManager
{
    public UserRepository $users;
    public MaterialRepository $materials;
    public CategoryRepository $categories;
    public ReservationRepository $reservations;

    public function __construct(
        UserRepository $users, 
        MaterialRepository $materials, 
        CategoryRepository $categories, 
        ReservationRepository $reservations)
    {
        $this->users = $users;
        $this->materials = $materials;
        $this->categories = $categories;
        $this->reservations = $reservations;
    }
}