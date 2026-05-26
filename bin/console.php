<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Managers\RepositoryManagerFactory;
use App\Cli\Menu;
use App\Cli\AuthContext;

$repositoryManager = RepositoryManagerFactory::create();
$authContext = new AuthContext();

$menu = new Menu($repositoryManager, $authContext);
$menu->display();