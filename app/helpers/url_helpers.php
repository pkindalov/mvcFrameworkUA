<?php
    //Simple page redirect
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}

function get_query_params($url){
   $data =  explode('&', $url);
   $result = [];
   for($i = 1; $i < count($data); $i++){
       $result[] = $data[$i];
   }
   return $result;
}

function extract_query_params_values($query_params){
    $result = [];
    foreach($query_params as $value){
        $data = explode('=', $value);
        $result[$data[0]] = $data[1];
    }
    return $result;
}