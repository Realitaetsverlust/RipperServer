<?php

class Download {
    public function exec() {
        //Validate against the evil stuff
        $videoTitle = urldecode(filter_input(INPUT_GET, 'videoTitle', FILTER_SANITIZE_STRING));

        //if file exists, output it, if not, fuck off
        if(file_exists($videoTitle)) {
            header('Content-Type: audio/mp3');
            header("Content-Disposition: attachment;filename='$videoTitle");
            header('Content-length: ' . filesize($videoTitle));
            header('Cache-Control: no-cache');
            header("Content-Transfer-Encoding: chunked");

            readfile($videoTitle);
            exit();
        }

        exit('No Video found!');
    }
}