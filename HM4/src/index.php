<?php declare(strict_types=1);

namespace HM4;

use HM4\MainPanel;
use HM4\User;
use HM4\Subject;

require __DIR__ . '/Subject.php';
require __DIR__ . '/User.php';
require __DIR__ . '/MainPanel.php';


$mainPanel = new MainPanel();

$mainPanel->login();
//$mainPanel->adminMenu();





