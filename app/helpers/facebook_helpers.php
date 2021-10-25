<?php
    function fb_config()
    {
        try {
            $fb_config = null;
            if(!FB_APP_ID || !FB_APP_SECRET) return $fb_config;
            return $fb_config = new \Facebook\Facebook([
                'app_id' => FB_APP_ID,
                'app_secret' => FB_APP_SECRET,
                'default_graph_version' => 'v2.10',
                'state' => gen_cross_forgery_token()
                //'default_access_token' => '{access-token}', // optional
              ]);

        } catch (Exception $ex) {
            echo $ex->getMessage();
            $fb_config = null;
            return $fb_config;
        }
    }

