<?php
include 'utility.php';

$file = $_GET['file'];

//check if the password is correct. the passwords are in validpasswords.php
include 'validpasswords.php';

//check if there is ?psw in the url, if not abort loading the rest of the page
if (!isset($_GET['psw'])) {
    logPristup('Pokus o stažení ' . $file . ' bez hesla', 'N/A');
    echo '<h1>Chybějící přístupový kód</h1>';
    echo '<h1><a href="./">zpatky na prihlaseni</a></h1>';
    die();
}
if (!in_array($_GET['psw'], $validPasswords)) {
    logPristup('Pokus o stažení ' . $file . 'špatným heslem', $_GET['psw']);
    echo '<h1>Špatný přístupový kód</h1>';
    echo '<h1><a href="./">zpatky na prihlaseni</a></h1>';
    die();
}
logPristup('Stahování ' . $file, $_GET['psw']);

// Set the download headers
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Length: ' . filesize($file));
// Read the file
readfile($file);
?>