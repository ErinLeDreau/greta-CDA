<?php

namespace App\Cli;

use App\Cli\Admin\CategoryMenu;
use App\Cli\Admin\MaterialMenu;
use App\Cli\Admin\ReservationMenu;
use App\Cli\Admin\UserMenu;
use App\Managers\RepositoryManager;

class AdminMenu
{
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;

    private UserMenu $userMenu;
    private MaterialMenu $materialMenu;
    private CategoryMenu $categoryMenu;
    private ReservationMenu $reservationMenu;

    public function __construct(
        RepositoryManager $repositoryManager,
        AuthContext $authContext,
    ) {
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;

        $this->userMenu = new UserMenu($repositoryManager, $authContext);
        $this->materialMenu = new MaterialMenu($repositoryManager, $authContext);
        $this->categoryMenu = new CategoryMenu($repositoryManager);
        $this->reservationMenu = new ReservationMenu($repositoryManager, $authContext);
    }

    public function handle()
    {
        if (!$this->authContext->currentUser || !$this->authContext->currentUser->isAdmin()) {
            echo "Access denied" . PHP_EOL;
            return;
        }

        while (true) {
            echo PHP_EOL . "===== ADMIN PANEL =====" . PHP_EOL;
            echo "1. Users" . PHP_EOL;
            echo "2. Categories" . PHP_EOL;
            echo "3. Materials" . PHP_EOL;
            echo "4. Reservations" . PHP_EOL;
            echo "0. Back" . PHP_EOL;

            $choice = readline("Choice: ");

            switch ($choice) {
                case "1":
                    $this->userMenu->displayMenu();
                    break;

                case "2":
                    $this->categoryMenu->displayMenu();
                    break;

                case "3":
                    $this->materialMenu->displayMenu();
                    break;

                case "4":
                    $this->reservationMenu->displayMenu();
                    break;

                case "0":
                    return;

                default:
                    echo "Invalid choice" . PHP_EOL;
            }
        }
    }
}