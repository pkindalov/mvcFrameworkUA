<?php

use Facebook\Authentication\AccessToken;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Users extends Controller implements MainFunctionalities
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index(){
        redirect('');
    }

    public function register()
    {
        if (is_query_post()) {
            $result = $this->userModel->regUser();
            if ($result['success']) {
                // $this->settingsModel->createDefaultUserSettings($result['user_id']);
                $this->sendWellcomeEmail($result);
                flash('notifycationBox', 'You are registered successfully. You can login in your profile now');
                redirect('users/login');
                return;
            }
            $this->view('users/register', $result['data']);
        }
        $data = getUserData();
        $this->view('users/register', $data);
    }

    public function sendWellcomeEmail($user)
    {
        require_once APPROOT . '/libraries/PhpMailSender.php';
        $phpMailer = new PhpMailSender(MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD);
        $wellcomeMsg = '<h1>Wellcome to My Bet Analyzer, ' . $user['name'] . '.</h1>'
            . '<p>You can log now with the following data: </p>'
            . '<p><strong>email:</strong> ' . $user['email'] . '</p>'
            . '<p><strong>password:</strong> ' . $user['confirm_password'] . '</p>'
            . '<p>You can now log from <a class="btn btn-primary" href="' . URLROOT . '/users/login">here</a></p>'
            . '<p>Wish you a best luck and nice time in the app</p>';
        $result = sendMail(
            [
                'mail_obj' => $phpMailer,
                'subject' => 'Wellcome',
                'body' => $wellcomeMsg,
                'receiver' => $user['email'],
                'attachments' => []
                // 'attachments' => ['images/user_9/162422098552210185260cfa5399cef9.jpeg']
            ]
        );
        return $result;
    }

    public function login()
    {
        try{
            if (is_query_post()) {
                $result = $this->userModel->logUser();
                if ($result['success']) {
                    $this->createUserSession($result['user']);
                    $this->createUserCookies($result['user'], $result['remember']);
                    redirect('pages/index');
                    return;
                }
                $data = getUserData();
                $data['password_err'] = 'Email or Password incorrect';
                $this->view('users/login', $data);
            }
            $data = getUserData();
            // beautifulPrintArr($data);
            $fb = fb_config();
            if($fb) {
                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['email']; // Optional permissions
                $loginUrl = $helper->getLoginUrl(URLROOT . '/users/fb_callback', $permissions);
                $data['has_fb_login_btn'] = true;
                $data['fb_login_url'] = $loginUrl;
            }
            $google_client = get_google_client();
            if($google_client && $google_client->getclientId()) {
                $data['has_google_login_btn'] = true;
                $data['google_login_url'] = $google_client->createAuthUrl();
            }
            // $data['fb_login_url'] = $loginUrl ? $loginUrl : '';
            $this->view('users/login', $data);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }


    public function logout()
    {
        $this->removeSession();
        session_destroy();
        $this->resetCookie();
    }

    public function profile($user_id)
    {
        session_activity_checker();
        if (!isLoggedIn()) redirect('pages/index');
        $user = $this->userModel->getUserDataById($user_id);
        if (!$user) {
            flash('notifycationBox', 'There is some problem. User not found');
            redirect('pages/index');
            return;
        }
        $user_img_dir = generate_user_dir_name(escapeField($user_id));
        $user->full_img_path = '/' . generate_img_user_dir($user_img_dir) . '/' . $user->profile_img;
        $data = ['user' => $user];
        $this->view('users/profile', $data);
    }

    public function fb_callback()
    {
        $fb = fb_config();
        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            if (!isset($accessToken) || !$accessToken || $helper->getError()) {
                flash('notifycationBox', $helper->getErrorReason(), 'alert alert-danger');
                redirect('users/login');
                exit;
            }
            $fb->setDefaultAccessToken($accessToken);
            $response = $fb->get('/me?locale=en_US&fields=name,email');
            $userNode = $response->getGraphUser();
            $userEmail = escapeField($userNode->getField('email'));
            $userName = escapeField($userNode->getField('name'));
            $this->createOrLogUser(['user_email' => $userEmail, 'user_name' => $userName]);
        } catch (Facebook\Exception\ResponseException $e) {
            // When Graph returns an error
            // echo 'Graph returned an error: ' . $e->getMessage();
            flash('notifycationBox', $e->getMessage(), 'alert alert-danger');
            redirect('users/login');
            exit;
        } catch (Facebook\Exception\SDKException $e) {
            // When validation fails or other local issues
            // echo 'Facebook SDK returned an error: ' . $e->getMessage();
            flash('notifycationBox', $e->getMessage(), 'alert alert-danger');
            redirect('users/login');
            exit;
        }
    }

    public function google_callback()
    {
        if (isset($_GET['code'])) {
            try {
                $client = get_google_client();
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                $client->setAccessToken($token['access_token']);
                // get profile info
                $google_oauth = new Google_Service_Oauth2($client);
                $google_account_info = $google_oauth->userinfo->get();
                $userEmail = escapeField($google_account_info->email);
                $userName = escapeField($google_account_info->name);
                $this->createOrLogUser(['user_email' => $userEmail, 'user_name' => $userName]);
            } catch (Exception $e) {
                flash('notifycationBox', $e->getMessage(), 'alert alert-danger');
                redirect('users/login');
                exit;
            }
        }
        flash('notifycationBox', 'There is some problem with google login', 'alert alert-danger');
        redirect('users/login');
    }

    public function getAllAdminUsers()
    {
        session_activity_checker();
        if (!isOwner()) redirect('pages/index');
        $admin_users = $this->userModel->getAllAdminUsers();
        header('Content-Type: application/json');
        echo json_encode($admin_users);
    }

    public function removeAdmin($user_id)
    {
        session_activity_checker();
        if (!isOwner()) redirect('pages/index');
        $id = escapeField($user_id);
        $result = $this->userModel->removeAdmin($id);
        if ($result['success']) {
            flash('notifycationBox', 'User is not admin anymore', 'alert alert-success');
            redirect('settings/settings');
            return;
        }
        flash('notifycationBox', 'There is some problem removing user from admins', 'alert alert-danger');
        redirect('settings/settings');
    }


    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    private function createOrLogUser($data)
    {
        list('user_email' => $userEmail, 'user_name' => $name) = $data;
        if (!$this->userModel->findUserByEmail($userEmail)) {

            //if not user found to create him/her a new account.
            $data['name'] = $name;
            $data['email'] = $userEmail;
            $genPass = genRndPassword();
            $hashedPass = password_hash($genPass, PASSWORD_DEFAULT);
            $data['password'] = $hashedPass;
            $data['confirm_password'] = $genPass;
            $newUser = $this->userModel->registering_user($data);
            // $this->settingsModel->createDefaultUserSettings($newUser['user_id']);
            $this->sendWellcomeEmail($newUser);
            redirect('pages/index');
        }
        $user = $this->userModel->getUserDataByEmail($userEmail);
        if ($user) {
            flash('notifycationBox', 'logged successful', 'alert alert-success');
            $this->createUserSession($user);
            $this->createUserCookies($user, false);
            redirect('pages/index');
            return;
        }
        redirect('pages/index');
    }

    private function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_profile_img'] = $user->profile_img;
        $this->userModel->updateUserActivity($user->id);
        redirect('pages/index');
    }

    private function createUserCookies($user, $remember)
    {
        if (!empty($remember) && $remember) {
            $hour = $this->addTime();
            setcookie('userid', $user->id, $hour, '/');
            setcookie('name', $user->name, $hour, '/');
            setcookie('active', 1, $hour, '/');
        }
    }
    private function resetCookie()
    {
        $hour = $this->subtractTime();
        setcookie('userid', "", $hour, '/');
        setcookie('name', "", $hour, '/');
        setcookie('active', "", $hour, '/');
        redirect('users/login');
    }

    private function addTime()
    {
        return time() + 3600; //expires in an hour
    }

    private function subtractTime()
    {
        return time() - 3600;
    }

    private function removeSession()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_profile_img']);
    }
}
