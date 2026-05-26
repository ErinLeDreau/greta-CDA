<?php

namespace App\Cli;

use App\Cli\AdminMenu;
use App\Cli\AuthContext;
use App\Cli\AuthMenu;
use App\Cli\PublicMenu;
use App\Cli\TeacherMenu;
use App\Managers\RepositoryManager;

class Menu
{
    private AuthContext $authContext;
    private RepositoryManager $repositoryManager;

    private AuthMenu $authMenu;
    private PublicMenu $publicMenu;
    private TeacherMenu $teacherMenu;
    private AdminMenu $adminMenu;

    public function __construct(
        RepositoryManager $repositoryManager,
        AuthContext $authContext
    ) {
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;

        $this->authMenu = new AuthMenu($repositoryManager, $authContext);

        $this->publicMenu = new PublicMenu($repositoryManager, $authContext);

        $this->teacherMenu = new TeacherMenu($repositoryManager, $authContext);

        $this->adminMenu = new AdminMenu(
            $repositoryManager,
            $authContext
        );
    }

    public function display(): void
    {
        while (true) {

            echo PHP_EOL;
            echo "=== MAIN MENU ===" . PHP_EOL;

            if (!$this->authContext->currentUser) {
                echo "1. Login" . PHP_EOL;
                echo "0. Exit" . PHP_EOL;
            } else {
                echo "1. Public area" . PHP_EOL;

                if ($this->authContext->currentUser) {
                    echo "2. Teacher panel" . PHP_EOL;
                }

                if ($this->authContext->currentUser->isAdmin()) {
                    echo "3. Admin panel" . PHP_EOL;
                }

                echo "9. Logout" . PHP_EOL;
                echo "0. Exit" . PHP_EOL;
            }

            $choice = readline("Choice: ");

            $this->handle($choice);
        }
    }

    private function handle(string $choice): void
    {
        if (!$this->authContext->currentUser) {
            switch ($choice) {
                case "1":
                    $this->authMenu->login();
                    break;
                case "0":
                    exit;
            }
            return;
        }

        switch ($choice) {
            case "1":
                $this->publicMenu->handle();
                break;

            case "2":
                if ($this->authContext->currentUser) {
                    $this->teacherMenu->handle();
                }
                break;

            case "3":
                if ($this->authContext->currentUser->isAdmin()) {
                    $this->adminMenu->handle();
                }
                break;

            case "9":
                $this->authMenu->logout();
                break;

            case "0":
                exit;
        }
    }
}