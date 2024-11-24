<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'validpasswords.php';
include 'utility.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputPassword = $_POST['password'];
    
    if (in_array($inputPassword, $validPasswords)) {
        logPristup("Successfull login", $inputPassword);

        // Redirect with success status
        header('Location: fotky.php?status=success&psw='.$inputPassword);
    } else {
        logPristup("Failed login", $inputPassword);

        // Redirect with error status
        header('Location: index.php?status=error');
    }
}

?>