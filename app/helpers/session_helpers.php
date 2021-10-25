<?php
session_start();

//Flash message helper
//EXAMPLE flash('register-success', 'You are now registered');
//DISPLAY IN VIEW - echo flash('register-success');

function flash($name = '', $message = '', $class = 'alert alert-success')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        return 1;
    } else {
        return 0;
    }
}


function isAdmin()
{
    if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
        return 1;
    }
    return 0;
}

function isOwner()
{
    if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'owner') {
        return 1;
    }
    return 0;
}


function session_activity_checker()
{
    // last request was more than 30 minutes ago
    $half_hour_in_seconds = 1800;
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $half_hour_in_seconds)) {
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}


function session_activity_checker2()
{
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
        return;
    }
    if (time() - $_SESSION['CREATED'] > 1800) {
        // session started more than 30 minutes ago
        session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
        $_SESSION['CREATED'] = time();  // update creation time
    }
}

function is_user_profile_img()
{
    return isset($_SESSION['user_profile_img']) && $_SESSION['user_profile_img'];
}

function removeSession()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    unset($_SESSION['user_profile_img']);
}

function resetCookie()
{
    $hour = subtractTime();
    setcookie('userid', "", $hour, '/');
    setcookie('name', "", $hour, '/');
    setcookie('active', "", $hour, '/');
    redirect('users/login');
}

function subtractTime()
{
    return time() - 3600;
}
