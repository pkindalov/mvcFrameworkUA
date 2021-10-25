<?php
function img_available_checker()
{
    if (
        !isset($_FILES['profile_photo'])
        || !$_FILES['profile_photo']['name']
        ||  $_FILES['profile_photo']['error'] > 0
    ) {
        return ['success' => false, 'msg' => 'no image found'];
    }
    return ['success' => true];
}

function get_file_extension($file_name)
{
    $file_name_arr  = explode('.', $file_name);
    return strtolower(array_pop($file_name_arr));
}

function check_img_extension($data)
{
    list('file_ext' => $file_ext, 'valid_extensions' => $extensions) = $data;
    if (!in_array($file_ext, $extensions)) {
        $err_msg = 'image extension - ' . $file_ext . ' not allowed, please choose a ' . join(',', $extensions) . ' file.';
        return ['success' => false, 'msg' => $err_msg];
    }
    return ['success' => true];
}

function check_img_size($data)
{
    list('img_size' => $size, 'limit' => $limit) = $data;
    if ($size > $limit) {
        $err_msg = 'File size must be less or equal to 2 MB';
        return ['success' => false, 'msg' => $err_msg];
    }
    return ['success' => true];
}

function generate_user_dir_name($user_id)
{
    return 'user_' . $user_id;
}

function generate_img_user_dir($user_dir)
{
    return 'images/' . $user_dir;
}

function deleteImgPermament($data)
{
    list('directory' => $directory, 'img_name' => $img_name) = $data;
    unlink($directory . '/' . $img_name);
}

function create_dir_if_not_exists($directory)
{
    if (!file_exists($directory)) mkdir($directory, 0777);
}

function rename_img($file_ext)
{
    return time() . uniqid(rand()) . '.' . $file_ext;
}

function update_img_name_in_session($name)
{
    $_SESSION['user_profile_img'] = $name;
}

function deleteDirAndFiles($dir)
{
    if(!is_dir($dir)) return;
    // $dir = 'samples' . DIRECTORY_SEPARATOR . 'sampledirtree';
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator(
        $it,
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dir);
}
