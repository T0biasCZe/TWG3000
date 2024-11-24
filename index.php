<?php
include '../utility.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);
include '../../forcehttps.php';
?>

<!DOCTYPE html>
<head>
    <title>Svycarsko</title>
    <link rel="stylesheet" type="text/css" href="./fonts/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="./stylopis.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../../css/wordart.css?v=<?php echo time(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta property="og:type" content="website">
    <meta property="og:url" content="https://tobikcze.eu/mkranking/">
    <meta property="og:title" content="Svycarsko 24">
    <meta property="og:description" content="Fotky ze svycarska 2024.09.23 - 2024.09.26">
    <meta property="og:image" content="https://tobikcze.eu/galerie/svycarsko/gfx/vlajka_hq.gif">

    <link rel="icon" type="image/gif" href="./gfx/vlajka_cropped.gif">
</head>
<body>
    <div class="header" style="margin-top: -50px !important; margin-bottom: 20px;">
        <div class="header_cell"><a href="/">Domů</a></div>
        <div class="header_cell"><a href="/galerie/">Galerie</a>
            <div class="dropdown">
                <div class="dropdown-content">
                    <a href="/galerie/vlaky/">vlaky</a>
                    <a href="/galerie/svycarsko/">Švýcarsko</a>
                </div>
            </div>
        </div>
        <div class="header_cell"><a href="/videa/">Videa</a></div>
        <div class="header_cell"><a href="/projekty.php">Projekty</a>
            <div class="dropdown">
                <div class="dropdown-content">
                    <a href="#">Link 1</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a>
                </div>
            </div>
        </div>
        <div class="header_cell"><a href="/audio/">Hudba</a></div>
    </div>
	
    <div class="wordart rainbow bluebow " style="font-size: 300%; margin-right: -20px;" >
        <span class="text" data-text="Švýcarsko">Švýcarsko</span>
    </div>

    <h2 id="loginprompt">Zadejte přístupový kód</h2>
    <form action="login.php" method="POST" id="login">
        <label for="password" id="kodlabel">Kód:</label><br>
        <input type="password" id="password" name="password">
        <button type="button" id="showPassword" ><img class="showpassimg" src="./gfx/imageres_82.png"></button><br><br>
        <input type="submit" value="Login" id="submit">
        <script>
            /*on click the button, toggle the password visibility*/
            document.getElementById('showPassword').addEventListener('click', function() {
                var passwordField = document.getElementById('password');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        </script>
    </form>
    <style>
        #showPassword{
            display: inline-block; 
            height: 24px; 
            width: 24px; 
            transform: scale(1.25); 
            background: none; 
            border: none;
        }
        #showPassword img{
            display: inline-block; 
            transform: scale(22); 
            width: 1px; 
            height: 1px; 
            margin-left: 1px; 
            margin-top: -7px; 
            margin-bottom: -4px; 
            vertical-align: middle;
        }
        #showPassword img:hover{
            filter: brightness(1.2);
            background: #ffffff66;
            border-radius: 0.2px;
        }
        #showPassword img:active{
            filter: brightness(0.7);
            background: #00000066;
            border-radius: 0.2px;
        }

        @media screen and (max-width: 640px) {
            #password{
                width: 70%;
                font-size: 1.6em;
            }
            #kodlabel{
                font-size: 1.6em;
            }
            #submit{
                font-size: 1.4em;
            }
            #loginprompt{
                font-size: 1.8em;
            }

            #showPassword{
                transform: scale(1.8);
            }
            #showPassword img{
                margin-top: -10px;
            }
        }
    </style>


    <!-- Optionally, a message for login status -->
    <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<p style="color:green;">Login successful!</p>';
            } else {
                echo '<p style="color:red;">Neplatný kód.</p>';
            }
        }
    ?>

</body>