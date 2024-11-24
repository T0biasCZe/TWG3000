<?php
    require_once 'PNGMetadata.php';

    use PNGMetadata\PNGMetadata;

    function scan_dir($dir) {
        $ignored = array('.', '..', '.svn', '.htaccess');

        $files = array();    
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored)) continue;
            //$files[$file] = filemtime($dir . '/' . $file);
            //load the exif data and get the time the photo was taken
            $exif = @exif_read_data($dir . '/' . $file);
            if (isset($exif["DateTimeOriginal"])) {
                $files[$file] = strtotime($exif["DateTimeOriginal"]);
            } else if (isset($exif["DateTimeDigitized"])) {
                $files[$file] = strtotime($exif["DateTimeDigitized"]);
            } else {
                $files[$file] = filemtime($dir . '/' . $file);
            }
        }

        arsort($files);
        $files = array_keys($files);
        $files = array_reverse($files);

        return $files;
    }
    include 'gps.php';
    function listPhotos(){

        $time_start = microtime(true);
        $folder = './photos';
        $template = file_get_contents("template.html");

        $folders = scandir($folder);

        foreach($folders as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            echo '<h1 id="' . $file . '">'.$file.'</h1>';
            //$photos = scandir($folder.'/'.$file);
            $photos = scan_dir($folder.'/'.$file);
            //if folder name is "gameboy" then sort the photos by the name
            if ($file == "Gameboy fotky") {
                sort($photos, SORT_NUMERIC);
            }
            if($file == "3DS Fotky"){
				echo "<script>function bruh(){ 
                    let url = new URL(window.location); 
					url.searchParams.append('noapng', '');
					window.location.replace(url);
                }</script>";
                echo "<button onclick='bruh()'>Klikněte zde pokud se gify nepřehrávají</button>";
            }

            foreach($photos as $photo) {
                if ($photo == '.' || $photo == '..' || $photo == 'preview') {
                    continue;
                }
                if (is_dir($folder.'/'.$file.'/'.$photo)) {
                    continue;
                }
                if (pathinfo($photo, PATHINFO_EXTENSION) == "txt") {
                    continue;
                }


                $previewImagePath = "";
                $fullresImagePath = $folder.'/'.$file.'/'.$photo;


                if (file_exists($folder.'/'.$file.'/tinypreview/'.$photo)) {
                    $previewImagePath = $folder.'/'.$file.'/tinypreview/'.$photo;
                } else if (file_exists($folder.'/'.$file.'/preview/'.$photo)) {
                    $previewImagePath = $folder.'/'.$file.'/preview/'.$photo;
                } else {
                    $previewImagePath = $folder.'/'.$file.'/'.$photo;
                }
                $isPng = false;
                //if the image is png and not jpg then dont read exif
                if (pathinfo($photo, PATHINFO_EXTENSION) == "png" || pathinfo($photo, PATHINFO_EXTENSION) == "gif" || pathinfo($photo, PATHINFO_EXTENSION) == "apng") {
                    $exif = PNGMetadata::extract($folder.'/'.$file.'/'.$photo)->get('exif');
                    $isPng = true;
                } else {
                    $exif = @exif_read_data($folder.'/'.$file.'/'.$photo);
                }
                $exifString = print_r($exif, true);
                //replace new lines and indentation with html equivalents
                $exifString = str_replace("\n", "<br>", $exifString);
                $exifString = str_replace(" ", "&nbsp;", $exifString);

                if($isPng){
                    $exifDate = @$exif["EXIF"];
                }
                else{
                    $exifDate = $exif;
                }

                //use DateTimeOriginal, otherwise DateTimeDigitized, otherwise FileDateTime
                if (isset($exifDate["DateTimeOriginal"])) {
                    $timeTaken = $exifDate["DateTimeOriginal"];
                } else if (isset($exifDate["DateTimeDigitized"])) {
                    $timeTaken = $exifDate["DateTimeDigitized"];
                } else if (isset($exifDate["DateTime"])) {
                    $timeTaken = $exifDate["DateTime"];
                } else {
                    $timeTaken = date("Y-m-d H:i:s", filemtime($folder.'/'.$file.'/'.$photo));
                }

                $downloadUrl = "download.php?file=".urlencode($fullresImagePath)."&psw=".$_GET['psw'];

                if(pathinfo($photo, PATHINFO_EXTENSION) == "apng"){
                    if (isset($_GET['noapng'])) {
                        $gifPath = $folder.'/'.$file.'/gif/'.pathinfo($photo, PATHINFO_FILENAME).'.gif';
                        $out = str_replace("PREVIEWSRC", $gifPath, $template);
                    }
                    else{
                        $out = str_replace("PREVIEWSRC", $previewImagePath, $template);
                    }
                }
                else{
                    $out = str_replace("PREVIEWSRC", $previewImagePath, $template);
                }
                $out = str_replace("DOWNLOADURL", $downloadUrl, $out);
                $out = str_replace("TITLE", $photo, $out);
                $out = str_replace("EXIFPASTE", $exifString, $out);
                $out = str_replace("TIMETAKEN", $timeTaken, $out);

                if (isset($exif["GPSLatitude"], $exif['GPSLongitude'])) {
                    $lat = gps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
                    $lon = gps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
                    $lon = gps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);

                    // Create the URL
                    $url = "https://mapy.cz/letecka?x=$lon&y=$lat&z=19";
                    $out = str_replace("MAPURL", $url, $out);
                    $out = str_replace("COORDS", number_format((float)$lat, 3, '.', '') . ", " . number_format((float)$lon, 3, '.', ''), $out);
                }
                else{
                    $out = str_replace('<img class="spendlik" src="./gfx/spendlik_red.png" alt="pinicon">', "", $out);
                    $out = str_replace('<a href="MAPURL">COORDS</a>', "<br>", $out);
                }

                
                //check if there is a txt file with the same file name as the photo
                if (file_exists($folder.'/'.$file.'/'.$photo.'.txt')) {
                    $popis = file_get_contents($folder.'/'.$file.'/'.$photo.'.txt');
                    $out = str_replace("POPIS", $popis, $out);
                }
                else{
                    $out = str_replace('<p style="margin-top: -25px; margin-bottom: 5px;">POPIS</p>', "", $out);
                }


                echo $out;
            }
        }

        

        echo "<br><br>Page generated in " . (microtime(true) - $time_start) * 1000 . " ms";
    }

    function vytvoritObsah(): void{
        $folderIn = './photos';
        echo "<style> .obsah a:visited, a:link{ color: white; }  .obsah a:active{ color: black; } .obsah a:hover{ text-shadow: 0 0 5px white; } </style> ";
        echo "<div class='obsah'>";
        echo "<h1>Obsah</h1>";

        $folders = scandir($folderIn);
        foreach($folders as $folder){
            if ($folder == '.' || $folder == '..' || !is_dir($folderIn.'/'.$folder)) {
                continue;
            }

            $emojiTag = "";
            
            switch(trim($folder)){
                case "3DS Fotky":
                    $emojiTag = '<img src="./gfx/3dglasses.png" style="margin:auto; display: block; height: 1.2em; margin-bottom: -0.2em;">';
                    break;
                case "Gameboy fotky":
                    $emojiTag = '<img src="./gfx/gbc.png" style="margin:auto; display: block; height: 1.2em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-25 1 Lasagne":
                    $emojiTag = '<img src="./gfx/olymp.png" style="margin:auto; display: block; height: 1em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-24 1 Cern":
                    $emojiTag = '<img src="./gfx/scientist.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-23 2 Bern":
                    $emojiTag = '<img src="./gfx/bern.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-24 2 OSN":
                    $emojiTag = '<img src="./gfx/osn.svg" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-26 1 Syrarna":
                    $emojiTag = '<img src="./gfx/cheese.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-26 2 Cokoladovna":
                    $emojiTag = '<img src="./gfx/chocolate.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-23 1 Vodopady":
                    $emojiTag = '<img src="./gfx/waterfall.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-24 3 Geneva":
                    $emojiTag = '<img src="./gfx/warcrime.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                case "2024-09-25 2 Montreux":
                    $emojiTag = '<img src="./gfx/mic.png" style="margin:auto; display: block; height: 1.2em; max-width: 3em; margin-bottom: -0.2em;">';
                    break;
                

            }

            echo "<a href='#".$folder."'><div style='width: 40px; max-width: 40px; display: inline-block;'>".$emojiTag . "</div> " . $folder . "</a><br>";
        }
        echo "</div>";
    }
?>