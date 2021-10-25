<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function regUser()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = getUserData();
        $data['name'] = trim($_POST['name']);
        $data['email'] = trim($_POST['email']);
        $data['password'] = trim($_POST['password']);
        $data['confirm_password'] = trim($_POST['confirm_password']);

        //Validate Name
        if (empty($data['name'])) {
            $data['name_err'] = 'Please enter name';
        }
        //Validate Email
        if (!simpleMailChech($data['email']) || $this->findUserByEmail($data['email'])) {
            $data['email_err'] = 'Invalid or this email is already taken. Please enter another.';
        }
        //Validate Password
        if (empty($data['password']) || !is_having_min_characters(['count' => 6, 'word' => $data['password']])) {
            $data['password_err'] = 'Password must be at least 6 characters';
        }
        //Validate Confirm Password
        if (empty($data['confirm_password'])) {
            $data['confirm_password_err'] = 'Please confirm password';
        }
        if ($data['password'] !== $data['confirm_password']) {
            $data['confirm_password_err'] = 'Passwords don\'t match';
        }
        //Make sure errors are empty
        if (are_empty_error_fields($data)) {
            //Hasing password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            // unset($data['confirm_password']);
            return $this->registering_user($data);
        }
        return ['success' => 0, 'data' => $data];
    }

    public function logUser()
    {
        //Proces form
        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = getUserData();
        $data['email'] = trim($_POST['email']);
        $data['password'] = trim($_POST['password']);

        //Validate Email
        if (!simpleMailChech($data['email']) || !$this->findUserByEmail($data['email'])) {
            $data['email_err'] = 'Invalid/Wrong email';
        }

        if (isset($_POST['remember'])) {
            $data['remember'] = htmlspecialchars(trim($_POST['remember']));
        }

        //Validate Password
        if (empty($data['password'])) {
            $data['password_err'] = 'Please enter password';
        }

        if (are_empty_error_fields($data)) {
            //Validated
            //Check and set logged users
            $loggedUser = $this->login($data['email'], $data['password']);
            if ($loggedUser) {
                return ['success' => true, 'user' => $loggedUser, 'remember' => $data['remember']];
            }
            return ['success' => false];
        }
    }

    public function registering_user($data)
    {
        if ($this->register($data)) {
            $user_id = $this->getLastUserId();
            return [
                'success' => true,
                'user_id' => $user_id,
                'name' => $data['name'],
                'password' => $data['password'],
                'confirm_password' => $data['confirm_password'],
                'email' => $data['email']
            ];
        }
        return ['success' => 0, 'data' => $data];
    }

    //Registering User
    public function register($data)
    {
        $this->db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        //Bind values
        $this->db->bind(":name", $data['name'], null);
        $this->db->bind(":email", $data['email'], null);
        $this->db->bind(":password", $data['password'], null);

        //Execute query
        if ($this->db->execute()) {
            return true;
        }

        return false;
    }


    //Login User
    public function login($email, $password)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email, null);

        $row = $this->db->single();
        $hashed_password = $row->password;

        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    //Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email, null);

        $row = $this->db->single();

        //Check row
        if ($this->db->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getLastUserId()
    {
        return $this->db->lastInsertedId();
    }

    public function getAllUsers()
    {
        $query = "SELECT u.id, u.name, u.email, u.role FROM users AS u";
        setQuery(['query' => $query, 'db' => $this->db]);
        return execute($this->db);
    }

    public function updateUserActivity($user_id)
    {
        $last_activity = date('Y-m-d H:i:s');
        $query = "UPDATE users AS u 
                      SET u.last_activity = :last_activity
                      WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['last_activity' => $last_activity, 'user_id' => $user_id], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return 1;
        }
        return 0;
    }

    public function uploadUsrPhoto($user_id)
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $user_id = escapeField($user_id);
        $result_is_valid_img = img_available_checker();
        if (!$result_is_valid_img['success']) return $result_is_valid_img;
        list(
            'name' => $name,
            'type' => $type,
            'tmp_name' => $tmp,
            'size' => $size
        ) = $_FILES['profile_photo'];
        $extensions = array("jpeg", "jpg", "png");
        $file_ext = get_file_extension($name);
        $result_is_valid_img_ext = check_img_extension(['file_ext' => $file_ext, 'valid_extensions' => $extensions]);
        if (!$result_is_valid_img_ext['success']) return $result_is_valid_img_ext;
        $result_is_valid_img_size = check_img_size(['img_size' => $size, 'limit' => IMAGE_MAX_SIZE]);
        if (!$result_is_valid_img_size['success']) return $result_is_valid_img_size;
        $user_dir = generate_user_dir_name($user_id);
        $directory = generate_img_user_dir($user_dir);
        try {
            $old_photo = $this->get_user_current_photo($user_id);
            if ($old_photo) deleteImgPermament(['directory' => $directory, 'img_name' => $old_photo]);
            create_dir_if_not_exists($directory);
            $name = rename_img($file_ext);
            move_uploaded_file($tmp, $directory . '/' . $name);
            $result = $this->updateUserProfilePhoto(['img_name' => $name, 'user_id' => $user_id]);
            if ($result) {
                update_img_name_in_session($name);
                return ['success' => true];
            }
            return ['sucess' => false];
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return ['success' => false, 'msg' => $ex->getMessage()];
            exit;
        }
    }

    public function updateUserProfilePhoto($data)
    {
        list('img_name' => $img_name, 'user_id' => $user_id) = $data;
        $query = "UPDATE users AS u SET u.profile_img = :img
                  WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['img' => $img_name, 'user_id' => $user_id], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return 1;
        }
        return 0;
    }

    public function get_user_current_photo($user_id)
    {
        $query = "SELECT u.profile_img FROM users AS u WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $user_id], 'db' => $this->db]);
        $result = execute($this->db);
        return $result[0]->profile_img;
    }

    public function getUserDataById($user_id)
    {
        $id = escapeField($user_id);
        $query = "SELECT u.id, u.name, u.email, u.created_at, u.role, u.last_activity, u.profile_img
                  FROM users AS u WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $id], 'db' => $this->db]);
        $result = execute($this->db);
        if (count($result) > 0) return $result[0];
        return false;
    }

    public function getUserDataByEmail($user_email)
    {
        $email = escapeField($user_email);
        $query = "SELECT u.id, u.name, u.email, u.password, u.created_at, u.role, u.last_activity, u.profile_img
                  FROM users AS u WHERE u.email = :user_email";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_email' => $email], 'db' => $this->db]);
        $result = execute($this->db);
        if (count($result) > 0) return $result[0];
        return false;
    }

    public function getAllAdminUsers()
    {
        $role = 'admin';
        $query = "SELECT u.id, u.name, u.email FROM users AS u WHERE u.role = :role";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['role' => $role], 'db' => $this->db]);
        return execute($this->db);
    }

    public function getLastNMonthsResult($params)
    {
        list('user' => $user_id, 'period' => $period) = $params;
        $query = "SELECT DATE(b.date) AS bets_date,COUNT(b.id) AS bets_count,MONTH (b.date) AS month_num,
                  MONTHNAME(b.date) AS month_name,COUNT(IF (b.is_bet_win=1,b.is_bet_win,NULL)) AS wins,
                  ROUND((COUNT(IF (b.is_bet_win=1,b.is_bet_win,NULL))/COUNT(b.id)*100)) AS success_rate 
                  FROM bets AS b 
                  INNER JOIN strategies AS s ON s.id=b.strategy_id 
                  WHERE MONTH (b.date) BETWEEN " . $period[0] . " AND " . $period[1] ." AND s.user_id=:user_id GROUP BY month_name";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $user_id], 'db' => $this->db]);
        try {
            return execute($this->db);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }    
    }

    public function changePassword($user_id)
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = escapeField($user_id);
        $newPassword = $_POST['newPassword'];
        $confirmNewPassword = $_POST['confirmNewPassword'];
        $errors = [
            'password_err' => '',
            'new_password_err' => '',
            'confirm_new_password_err' => ''
        ];
        if ($newPassword !== $confirmNewPassword) {
            $errors['new_password_err'] = 'passwords don\'t mach';
            $errors['confirm_new_password_err'] = 'passwords don\'t mach';
            return ['success' => false, 'msg' => 'passwords don\'t mach', 'errors' => $errors];
        }
        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users AS u SET u.password = :password WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $id, 'password' => $hashedPass], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return ['success' => true, 'password' => $newPassword, 'msg' => 'Password updated successful'];
        }
        return ['success' => false, 'msg' => 'There is some error updating password', 'errors' => $errors];
    }

    public function resetPassword($user_id)
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = escapeField($user_id);
        $errors = [
            'email_err' => ''
        ];
        $email = escapeField($_POST['email']);
        if (!isset($_POST['email']) || !$id || !$this->findUserByEmail($email)) {
            $err_msg = 'Invalid mail or user';
            $errors['email_err'] = $err_msg;
            return ['success' => false, 'errors' => $errors, 'msg' => $err_msg];
        }
        $newPassword = genRndPassword();
        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users AS u SET u.password = :password WHERE u.id = :user_id AND u.email = :email";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $id, 'password' => $hashedPass, 'email' => $email], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return ['success' => true, 'password' => $newPassword, 'msg' => 'Password reset successful'];
        }
        return ['success' => false, 'msg' => 'There is some error reseting password', 'errors' => $errors];
    }

    public function changeEmail($user_id)
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = escapeField($user_id);
        $errors = [
            'changed_email_err' => ''
        ];
        $current_user_email = escapeField($_SESSION['user_email']);
        $updated_email = $_POST['changed_email'];
        if ($current_user_email === $updated_email) {
            $errors['changed_email_err'] = 'Both emails are equal';
            $err_msg = 'New email is the same like the current one';
            return ['success' => false, 'msg' => $err_msg, 'changed_email_err' => $err_msg];
        }
        if (!$id) {
            $errors['changed_email_err'] = 'Both emails are equal';
            $err_msg = 'User id is not valid. Try log in again';
            return ['success' => false, 'msg' => $err_msg, 'changed_email_err' => ''];
        }
        if (!$updated_email) {
            $errors['changed_email_err'] = 'Both emails are equal';
            $err_msg = 'New email is not valid';
            return ['success' => false, 'msg' => $err_msg, 'changed_email_err' => $err_msg];
        }
        $query = "UPDATE users AS u SET u.email = :email WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $id, 'email' => $updated_email], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return ['success' => true, 'msg' => 'Email updated successful'];
        }
        return ['success' => false, 'msg' => 'There is some error updating email', 'errors' => $errors];
    }

    public function makeUserAdmin()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $user_id = escapeField($_POST['make_admin']);
        $role = 'admin';
        $errors = [
            'make_admin_err' => ''
        ];
        if (!$user_id) {
            $err_msg = 'Invalid or non-existing user';
            $errors['make_admin_err'] = $err_msg;
            return ['success' => false, 'msg' => $err_msg, 'changed_email_err' => $err_msg];
        }
        $query = "UPDATE users AS u SET u.role = :role WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $user_id, 'role' => $role], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return ['success' => true, 'msg' => 'User is admin now'];
        }
        return ['success' => false, 'msg' => 'There is some error making user admin', 'errors' => $errors];
    }

    public function removeAdmin($user_id)
    {
        if (!$user_id) {
            return ['success' => false, 'msg' => 'Invalid user'];
        }
        $role = 'user';
        $query = "UPDATE users AS u SET u.role = :role WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $user_id, 'role' => $role], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return ['success' => true, 'msg' => 'User is NOT admin now'];
        }
        return ['success' => false, 'msg' => 'There is some error removing user from admins'];
    }

    public function sendChangedPassEmail($data)
    {
        require_once APPROOT . '/libraries/PhpMailSender.php';
        $phpMailer = new PhpMailSender(MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD);
        list('user_id' => $user_id, 'pass' => $pass) = $data;
        $user = $this->getUserDataById($user_id);
        $msg = '<h1>Hello, ' . $user->name . '.</h1>'
            . '<p>You successfully changed your password</p>'
            . '<p>Your new <strong>password:</strong> ' . $pass . '</p>'
            . '<p>You can now log from <a class="btn btn-primary" href="' . URLROOT . '/users/login">here</a></p>'
            . '<p>Wish you a best luck and nice time in the app</p>';
        $result = sendMail(
            [
                'mail_obj' => $phpMailer,
                'subject' => 'Wellcome',
                'body' => $msg,
                'receiver' => $user->email,
                'attachments' => []
                // 'attachments' => ['images/user_9/162422098552210185260cfa5399cef9.jpeg']
            ]
        );
        return $result;
    }

    public function sendResetPassEmail($data)
    {
        require_once APPROOT . '/libraries/PhpMailSender.php';
        $phpMailer = new PhpMailSender(MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD);
        list('user_id' => $user_id, 'pass' => $pass) = $data;
        $user = $this->getUserDataById($user_id);
        $msg = '<h1>Hello, ' . $user->name . '.</h1>'
            . '<p>You successfully reset your password</p>'
            . '<p>Your new <strong>password:</strong> ' . $pass . '</p>'
            . '<p>You can now log from <a class="btn btn-primary" href="' . URLROOT . '/users/login">here</a>
               and to change it later.</p>'
            . '<p>Wish you a best luck and nice time in the app</p>';
        $result = sendMail(
            [
                'mail_obj' => $phpMailer,
                'subject' => 'Wellcome',
                'body' => $msg,
                'receiver' => $user->email,
                'attachments' => []
                // 'attachments' => ['images/user_9/162422098552210185260cfa5399cef9.jpeg']
            ]
        );
        return $result;
    }

    public function getUserPassById($user_id)
    {
        $id = escapeField($user_id);
        $query = "SELECT u.password FROM users AS u WHERE u.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $id], 'db' => $this->db]);
        $result = execute($this->db);
        if (count($result) > 0) return $result[0];
        return false;
    }

    public function getUsersByRole($role)
    {
        $query = "SELECT u.id, u.name, u.email FROM users AS u WHERE u.role = :role";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['role' => $role], 'db' => $this->db]);
        return execute($this->db);
    }

    public function compareUserPasswords($user_current_pass_db)
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!isset($_POST['user_acc_password']) || !$_POST['user_acc_password']) {
            return ['success' => false, 'msg' => 'Invalid password', 'errors' => ['user_acc_pass_err' => 'Not valid']];
        }
        $pass_from_html = escapeField($_POST['user_acc_password']);
        if (password_verify($pass_from_html, $user_current_pass_db)) {
            return ['success' => true, 'msg' => 'Passwords match'];
        }
        return ['success' => false, 'msg' => 'Passwords don\'t match', 'errors' => ['user_acc_pass_err' => 'Passwords don\'t match']];
    }

    public function removeUserPermamently($user_id)
    {
        $query = "DELETE users.* FROM users WHERE users.id = :user_id";
        setQuery(['query' => $query, 'db' => $this->db]);
        bindParams(['params' => ['user_id' => $user_id], 'db' => $this->db]);
        if (is_exec_query_success($this->db)) {
            return ['success' => true, 'msg' => 'Your profile successfull removed'];
        }
        return ['success' => false, 'msg' => 'There is some error removing your account'];
    }
}
