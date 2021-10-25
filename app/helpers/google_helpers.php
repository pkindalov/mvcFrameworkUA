<?php
function get_google_client()
{
    try {
        $client = null;
        if(!GOOGLE_APP_ID || !GOOGLE_SECRET || !URLROOT) return $client;
        // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId(GOOGLE_APP_ID);
        $client->setClientSecret(GOOGLE_SECRET);
        $client->setRedirectUri(URLROOT . '/users/google_callback');
        $client->addScope("email");
        $client->addScope("profile");
        return $client;
    } catch(Exception $ex) {
        echo $ex->getMessage();
        $client = null;
        return $client;
    }
}
