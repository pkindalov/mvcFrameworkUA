<?php
function setQuery($data)
{
    list('query' => $query, 'db' => $db) = $data;
    try{
        $db->query($query);
    } catch(Exception $ex){
        echo $ex->getMessage();
    }
}

function bindParams($data)
{
    list('params' => $params, 'db' => $db) = $data;
    foreach ($params as $key => $value) {
        try{
            $db->bind(':' . $key, $value, null);
        } catch(Exception $ex){
            echo $ex->getMessage();
        }
    }
}

function is_exec_query_success($db)
{
    try{
        if ($db->execute()) {
            return true;
        }
        return false;
    } catch(Exception $ex){
        echo $ex->getMessage();
    }
}

function execute($db)
{
    $results = $db->execute();
    if ($db->rowCount($results) == 0) {
        return [];
    }
    $results = $db->resultSet();
    return $results;
}

function setPage($page)
{
    return isset($page) ? (intval($page) === 0 ? 1 : intval($page)) : 1;
}

function checkResetPage($page)
{
    return $page <= 0 ? 1 : $page;
}

function calcTotalPages($data)
{
    list('total_count' => $total_count, 'limit' => $limit) = $data;
    return ceil($total_count / $limit);
}

function calcOffset($data)
{
    list('page' => $page, 'page_size' => $page_size) = $data;
    return ($page - 1) * $page_size;
}

function create_insert_query($data)
{
    list('data' => $data, 'table' => $table) = $data;
    $query = 'INSERT INTO ' . $table . ' ( ';
    $columns = array_keys($data);
    $query .= implode(',', $columns) . ') ';
    $query .= 'VALUES( ';
    $query .= append_bind_columns(['columns' => $columns]);
    $query .= ');';
    return $query;
}

function append_bind_columns($data)
{
    list('columns' => $columns) = $data;
    $query = '';
    foreach ($columns as $key => $value) {
        $query .= ':' . $value;
        if ($key < count($columns) - 1) {
            $query .=  ', ';
        }
    }
    return $query;
}


function prepareParams($params)
{
    $bindStr = getBindStr($params);
    return $bindStr;
    // $this->stmt->bind_param($bindStr, ...$this->totalRowsQueryParams);
}

function getBindStr($params)
{
    if (count($params) < 1) {
        throw new Exception('Params cannot be a zero');
    }
    $bindStr = '';
    $replaceChar = 's';
    foreach ($params as $param) {
        $firstLetter = getFirstLetter($param);
        if (!validateLetter($firstLetter)) {
            $bindStr .= $replaceChar;
            continue;
        }
        $bindStr .= $firstLetter;
    }
    return $bindStr;
}

function getFirstLetter($word)
{
    return substr(gettype($word), 0, 1);
}

function validateLetter($letter)
{
    $validLetters = ['i', 'd', 's', 'b'];
    return in_array($letter, $validLetters) ? $letter : false;
}
