<?php

namespace App\Managers;

use App\Repositories\UserRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReservationRepository;

class RepositoryManager
{
    public UserRepository $userRepository;
    public MaterialRepository $materialRepository;
    public CategoryRepository $categoryRepository;
    public ReservationRepository $reservationRepository;

    /**
     * @param UserRepository $userRepository
     * @param MaterialRepository $materialRepository
     * @param CategoryRepository $categoryRepository
     * @param ReservationRepository $reservationRepository
     */
    public function __construct(
        UserRepository $userRepository, 
        MaterialRepository $materialRepository, 
        CategoryRepository $categoryRepository, 
        ReservationRepository $reservationRepository)
    {
        $this->userRepository = $userRepository;
        $this->materialRepository = $materialRepository;
        $this->categoryRepository = $categoryRepository;
        $this->reservationRepository = $reservationRepository;
    }
}