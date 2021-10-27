<?php
function checkIfArrAndIfEmpty($data)
{
    if (gettype($data) == 'array' && count($data) == 0) {
        return true;
    }

    return false;
}

function is_array_empty($arr)
{
    return count($arr) === 0 ? 1 : 0;
}

function beautifulPrintArr($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function escapeField($field)
{
    return trim(htmlspecialchars($field));
}

function is_having_min_characters($data)
{
    list('count' => $count, 'word' => $word) = $data;
    return mb_strlen($word) >= $count ? 1 : 0;
}

function is_word_empty($word)
{
    return empty($word) ? 1 : 0;
}

function if_contains_url($word)
{
    $is_contains_http = mb_strpos($word, 'http') === 0 ? 1 : 0;
    $is_contains_https = mb_strpos($word, 'https') === 0 ? 1 : 0;
    return $is_contains_http || $is_contains_https;
}

function is_number($field)
{
    return is_numeric($field) ? 1 : 0;
}

function is_zero($number)
{
    return $number === 0 ? 1 : 0;
}

function is_any_error($arr)
{
    $is_there_error = 0;
    foreach ($arr as $key => $value) {
        if (!empty($value)) $is_there_error = 1;
        break;
    }
    return $is_there_error;
}

function wordChecker($data)
{
    list('word' => $word, 'min_chars' => $min_chars) = $data;
    $result = is_word_empty($word) || !is_having_min_characters(['count' => $min_chars, 'word' => $word]);
    return $result ? 1 : 0;
}

function aggrRulesByStrategyName($rules)
{
    $filtered = [];
    foreach ($rules as $key => $obj) {
        $filtered[$obj->title][] = $obj;
    }
    return $filtered;
}

function simpleMailChech($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function are_empty_error_fields($data)
{
    if (!is_array($data)) {
        throw new Error('Parameter must be an array');
        exit;
    }
    $data_keys = array_keys($data);
    $needle = '_err';
    $is_data_valid = true;

    foreach ($data_keys as $value) {
        //check if field name finish on _err
        if (!mb_stripos($value, $needle)) continue;
        if (!empty($data[$value])) {
            $is_data_valid = false;
            break;
        }
    }
    return $is_data_valid;
}

function gen_cross_forgery_token()
{
    return bin2hex(random_bytes(32));
}
