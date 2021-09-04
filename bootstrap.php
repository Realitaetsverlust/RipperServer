<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

//TODO: Add namespaces and autoloading you lazy cunt >:(
require_once 'Classes/Ripper.php';
require_once 'Classes/Download.php';
require_once 'Classes/AddToLibrary.php';

$command = str_replace('/', '', $_SERVER['REDIRECT_SCRIPT_URL']);

switch($command) {
    case 'Ripper':
        // We only want api key validation for triggering the download from youtube, not the file output
        $apiKey = file_get_contents('api.txt');

        if(!isset($_GET['key']) || $_GET['key'] !== $apiKey) {
            abort();
        }

        //Also validating the passed video name and ID against the evil stuff
        $videoName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
        $videoId = filter_input(INPUT_GET, 'videoId', FILTER_SANITIZE_STRING);

        $exec = new Ripper();
        echo $exec->exec($videoId, $videoName);
        break;
    case 'Download':
        $exec = new Download();
        $exec->exec();
        break;
    case 'AddToLibrary':
        $apiKey = file_get_contents('api.txt');
        if(!isset($_GET['key']) || $_GET['key'] !== $apiKey) {
            abort();
        }

        //Also validating the passed video name and ID against the evil stuff
        $videoName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
        $videoId = filter_input(INPUT_GET, 'videoId', FILTER_SANITIZE_STRING);

        $exec = new AddToLibrary();
        $exec->exec($videoName, $videoId);
        break;
    default:
        abort();
        break;
}

function abort() {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

