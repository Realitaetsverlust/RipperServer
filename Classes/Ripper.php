<?php

class Ripper {
    public function exec() {
        //We just set the content type up here because there's a json in any case
        header('Content-Type: application/json');

        //fetch the info from the request and validate it against the evil stuff
        $videoId = filter_input(INPUT_GET, 'videoId', FILTER_SANITIZE_STRING);

        //quick check to validate the video ID
        if(!preg_match('/[a-zA-Z0-9_-]{11}/', $videoId)) {
            exit('wrong videoId format');
        }

        //Also validating the passed video name against the evil stuff
        $videoName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);

        //If video name is still evaluating to something wrong, let's just use a dummy name
        if($videoName == false) {
            $videoName = 'video';
        }

        // -x = only audio
        // --audio-format=mp3 = duh well what do you think
        // -o = name format. youtube-dl usually appends some string behind the headline, but we want to be the videoname as the filename
        $dlCommand = "youtube-dl -x --audio-format=mp3 -o '{$videoName}.mp3'";
        $url = "https://www.youtube.com/watch?v=$videoId";

        //Quick shell_exec to remove old .mp3 files that might clutter the directory. Easier than using glob().
        shell_exec('rm -f *.mp3');
        $executable = ($dlCommand . ' ' . escapeshellarg($url));

        //Few descriptor definitions
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );

        //We really only need stdout
        $process = proc_open($executable, $descriptorspec, $pipes);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        proc_close($process);

        //Some people might think "why are you parsing the filename from the output of youtube-dl? You already defined the name above"
        //Well, it still struggles with certain filenames. I noticed this with "Pokémon", but I'm pretty sure a lot
        //of non-ASCII characters will lead to issues. Therefore, we have to extract the ACTUAL name from the output of youtube-dl.
        preg_match('/(?<=Post-process file ).*(?= exists)/', $stdout, $match);

        header('Content-Type: application/json');

        if($match == false) {
            echo json_encode([
                'error' => 'There was an issue with youtube-dl! Try execute ' . $executable . ' manually on the target system and check the output. Most likely you just have to update the application with "youtube-dl -U"',
            ]);
            exit();
        }

        echo json_encode([
            'videoTitle' => urlencode($match[0])
        ]);
    }
}