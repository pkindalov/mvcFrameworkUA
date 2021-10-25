<?php
function is_query_post()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST' ? 1 : 0;
}

function getSortParam($sort)
{
    $sortQuery = '';
    switch ($sort) {
        case 'strategy':
            $sortQuery = 'strategy_id ';
            break;
        case 'win':
            $sortQuery = 'is_bet_win';
            break;
        case 'coef':
            $sortQuery = 'coef';
            break;
        case 'price':
            $sortQuery = 'stake';
            break;
        case 'date':
            $sortQuery = 'date';
            break;
        default:
            $sortQuery = 'strategy_id';
            break;
    }
    return $sortQuery;
}

function getDateFromNEl($data)
{
    list ('data' => $data, 'number' => $number, 'format' => $format) = $data;
    if(!isset($data[$number])){
        throw new Error('Element not exists');
    }
    $date = date($format, strtotime($data[$number]->date));
    return $date;
}


function getUserData()
{
    return [
        'name' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'has_fb_login_btn' => false,
        'has_google_login_btn' => false,
        'fb_login_url' => '',
        'google_login_url' => ''
    ];
}
