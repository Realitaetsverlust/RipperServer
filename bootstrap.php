<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

require_once 'Classes/Ripper.php';
require_once 'Classes/Download.php';

$command = str_replace('/', '', $_SERVER['REDIRECT_SCRIPT_URL']);

switch($command) {
    case 'Ripper':
        // We only want api key validation for triggering the download from youtube, not the file output
        $apiKey = file_get_contents('api.txt');

        if(!isset($_GET['key']) || $_GET['key'] !== $apiKey) {
            abort();
        }

        $exec = new Ripper();
        $exec->exec();
        break;
    case 'Download':
        $exec = new Download();
        $exec->exec();
        break;
    default:
        abort();
        break;
}

function abort() {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

