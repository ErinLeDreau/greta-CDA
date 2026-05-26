<?php

namespace App\Cli;

use App\Managers\RepositoryManager;

class PublicMenu
{
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;
    public function __construct(RepositoryManager $repositoryManager,AuthContext $authContext) 
    {
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;
    }

    public function handle(): void
    {
        echo PHP_EOL . "=== PUBLIC ===" . PHP_EOL;

        $materials = $this->repositoryManager->materialRepository->findAll();

        foreach ($materials as $m) {
            echo $m->getId() . " - " . $m->getName() . PHP_EOL;
        }

        $reservations = $this->repositoryManager->reservationRepository->findAllFuture();

        echo PHP_EOL . "Future reservations:" . PHP_EOL;

        foreach ($reservations as $r) {
            echo $r->getId() . " | " . $r->getStartDate()->format('Y-m-d') ." | ". $r->getMaterial()->getName() . PHP_EOL;
        }
    }
}