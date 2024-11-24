<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./stylopis_fotky.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="./fonts/stylesheet.css">
    <title>Fotky svycarsko</title>
    <link rel="icon" type="image/gif" href="./gfx/vlajka_cropped.gif">
	
	<?php
        //check if the password is correct. the passwords are in validpasswords.php
        include 'validpasswords.php';
        $showmeta = false;
        if (isset($_GET['psw'])){
            if (in_array($_GET['psw'], $validPasswords)) {
                $showmeta = true;
                echo '<meta property="og:type" content="website">
                <meta property="og:url" content="https://tobikcze.eu/galerie/svycarsko/">
                <meta property="og:title" content="Svycarsko 24">
                <meta property="og:description" content="Fotky ze svycarska 2024.09.23 - 2024.09.26">
                <meta property="og:image" content="https://tobikcze.eu/galerie/svycarsko/gfx/vlajka_hq.gif">';
            }
        }
        if(!$showmeta){
            echo '<meta property="og:type" content="website">
            <meta property="og:url" content="https://tobikcze.eu/galerie/svycarsko/">
            <meta property="og:title" content="FORBIDDEN - Svycarsko 24">
            <meta property="og:description" content="Linkněte přihlašovací stránku, ne tuhle!">
            <meta property="og:image" content="https://tobikcze.eu/galerie/svycarsko/gfx/imageres_98.png">';
        }
    ?>
</head>
<body>
    <h1 class="margin-bottom: -30px;">Fotky švýcarsko <img src="./gfx/vlajka_cropped.gif" style="display: inline; height: 32px; margin-bottom: -5px;"></h1>
    <small>z důvodu šetření bandwidthu jsou fotky zobrazeny v nižší kvalitě. při stažení budou mít plnou kvalitu</small><br>
    <a href="./">zpátky</a>
    <?php
        include 'utility.php';
        error_reporting(E_ALL);
        ini_set("display_errors", 1);

        //check if the password is correct. the passwords are in validpasswords.php
        include 'validpasswords.php';

        //check if there is ?psw in the url, if not abort loading the rest of the page
        if (!isset($_GET['psw'])) {
            logPristup('Pokus o načtení bez hesla', 'N/A');
            echo '<h1>Chybějící přístupový kód</h1>';
            echo '<h1><a href="./">zpatky na prihlaseni</a></h1>';
            die();
        }
        if (!in_array($_GET['psw'], $validPasswords)) {
            logPristup('Pokus o načtení s špatným heslem', $_GET['psw']);
            echo '<h1>Špatný přístupový kód</h1>';
            echo '<h1><a href="./">zpatky na prihlaseni</a></h1>';
            die();
        }
        logPristup('Načtění s správným heslem', $_GET['psw']);
    ?>

    <?php
        include 'listphotos.php';
        vytvoritObsah();
        listPhotos();
    ?>
    <div id="bigPreview" class="bigPreview" style="display: none;">
        <script>
            function closeBigPreview() {
                document.getElementById('bigPreview').style.display = 'none';
                document.getElementById('bigImage').src = "./gfx/aero_busy_xl_page_01.gif";
            }
        </script>
        <button id="closeButton" class="closeButton" onclick="closeBigPreview()" onmouseover="(function(){
            this.style.cursor = 'pointer';
        }).call(this)"><img src="./gfx/netshell_1603.png"></button>
        <img id="bigImage" class="bigImage" src="" alt="bigPreview" ondblclick="closeBigPreview()">
    </div>
    <script>
        /*for all images with class "obrazek", add an onclick event that will show the image large in a div when clicked in div "bigPreview" */
        var images = document.getElementsByClassName("obrazek");
        for (var i = 0; i < images.length; i++) {
            images[i].onclick = function() {
                //if width of the screen is smaller than 640px, don't show the big preview
                if (window.innerWidth < 640) {
                    return;
                }
                else{
                    document.getElementById('bigPreview').style.display = 'block';
                    document.getElementById('bigImage').src = this.src.replace("tinypreview","preview");
                }
            }
            images[i].onmouseover = function() {
                this.style.cursor = "pointer";
            }
            images[i].ondblclick = function() {
                if(window.innerWidth < 640){
                    this.src = this.src.replace("tinypreview","preview");
                }
            }
        }
    </script>
    
</body>
</html>