<?php
function hashIpTo8Bit($ipAddress) {
    // Convert the IP address to a 32-bit integer
    $ipInt = ip2long($ipAddress);

    // Simple hash function: XOR the bytes and take the result modulo 256
    $hash = ($ipInt & 0xFF) ^ (($ipInt >> 8) & 0xFF) ^ (($ipInt >> 16) & 0xFF) ^ (($ipInt >> 24) & 0xFF);

    return $hash;
}

// Define the path to the log file
$logFile = '/config/www/galerie/svycarsko/access_log.txt';
$logFileExcluded = '/config/www/galerie/svycarsko/access_log_excluded.txt';

function logPristup($message, $inputPassword){
	global $logFile, $logFileExcluded;

	// Get the current URL
	$pageURL = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// Check if the `CF-Connecting-IP` header is set to get the real visitor IP address
	if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
		$ipAddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
	} else {
		// Fallback to `REMOTE_ADDR` if `CF-Connecting-IP` is not set
		$ipAddress = $_SERVER['REMOTE_ADDR'];
	}
	$hashedIp = hashIpTo8Bit($ipAddress);


	// Get the current date and time
	$dateTime = date("Y-m-d H:i:s");

	// Format the log entry
	/*$logEntry = "$dateTime - IP: $ipAddress - HIP: $hashedIp - Page: $pageURL\n";*/
	$logEntry = "$dateTime - IP: $ipAddress - HIP: $hashedIp - $message - $inputPassword\n";


	include '../../excludedIPs.php';
	// Check if the IP address is in the excluded list, or if part of the IP address is in the excluded list (eg "192.168.")
	$isExcluded = false;
	foreach ($excludedIPs as $excludedIP) {
		if (strpos($ipAddress, $excludedIP) === 0) {
			$isExcluded = true;
			break;
		}
	}

	if ($isExcluded) {
		// Write the log entry to the excluded log file
		file_put_contents($logFileExcluded, $logEntry, FILE_APPEND | LOCK_EX);
	} else {
		// Write the log entry to the normal log file
		file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
	}
}
?>