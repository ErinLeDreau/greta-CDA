<?php

namespace App\Managers;

use App\Repositories\UserRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReservationRepository;

class RepositoryManager
{
    public UserRepository $userRepository;
    public CategoryRepository $categoryRepository;
    public MaterialRepository $materialRepository;
    public ReservationRepository $reservationRepository;

    /**
     * @param UserRepository $userRepository
     * @param CategoryRepository $categoryRepository
     * @param MaterialRepository $materialRepository
     * @param ReservationRepository $reservationRepository
     */
    public function __construct(
        UserRepository $userRepository, 
        CategoryRepository $categoryRepository,
        MaterialRepository $materialRepository, 
        ReservationRepository $reservationRepository)
    {
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->materialRepository = $materialRepository;
        $this->reservationRepository = $reservationRepository;
    }
}