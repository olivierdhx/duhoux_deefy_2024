<?php
declare(strict_types=1);

use iutnc\deefy\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'default'; 
}

\iutnc\deefy\repository\DeefyRepository::setConfig(__DIR__ . '/deefy.db.ini');
$dispatcher = new Dispatcher($action);

$dispatcher->run();
