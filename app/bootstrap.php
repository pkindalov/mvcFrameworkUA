<?php
    //Load config
    require_once 'config/config.php';

    $envFile = dirname(__DIR__) . '/.env';
    if (file_exists($envFile)) {
        $envVars = parse_ini_file($envFile);
        foreach ($envVars as $key => $value) {
            putenv("$key=$value");
        }
    }

    //Load Helpers
    require_once 'helpers/url_helpers.php';
    require_once 'helpers/session_helpers.php';
    require_once 'helpers/controller_helpers.php';
    require_once 'helpers/data_helpers.php';
    require_once 'helpers/query_helpers.php';
    require_once 'helpers/content_helpers.php';
    require_once 'helpers/datetime_helpers.php';
    require_once 'helpers/spreadsheat_helpers.php';
    require_once 'helpers/image_helpers.php';
    require_once 'helpers/mail_helpers.php';
    require_once 'helpers/user_helpers.php';
    require_once 'helpers/facebook_helpers.php';
    require_once 'helpers/google_helpers.php';
    require_once '../vendor/autoload.php';


    //Load libraries
    // require_once 'libraries/Core.php';
    // require_once 'libraries/Controller.php';
    // require_once 'libraries/Database.php';
    
    //Autoload Core libraries
    spl_autoload_register(function($className){
        require_once 'libraries/' . $className .'.php';
    });
