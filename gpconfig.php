<?php
session_start();

// Include Librari Google Client (API)
include_once 'libraries/google-client/Google_Client.php';
include_once 'libraries/google-client/contrib/Google_Oauth2Service.php';

$client_id = '78944508095-mr487f9tgfr8jeb3g6ejspuf494rs6pm.apps.googleusercontent.com'; // Google client ID
$client_secret = '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'; // Google Client Secret
$redirect_url = 'https://sim-mutu.stikes-yrsds.ac.id/auth'; // Callback URL

// Call Google API
$gclient = new Google_Client();
$gclient->setClientId($client_id); // Set dengan Client ID
$gclient->setClientSecret($client_secret); // Set dengan Client Secret
$gclient->setRedirectUri($redirect_url); // Set URL untuk Redirect setelah berhasil login

$google_oauthv2 = new Google_Oauth2Service($gclient);
?>
