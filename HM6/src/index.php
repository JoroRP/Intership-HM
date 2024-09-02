<?php declare(strict_types=1);

namespace HM6;

use HM6\MainPanel;
use HM6\User;
use HM6\Subject;

require __DIR__ . '/Subject.php';
require __DIR__ . '/User.php';
require __DIR__ . '/MainPanel.php';


$mainPanel = new MainPanel();

$mainPanel->login();
//$mainPanel->adminMenu();





