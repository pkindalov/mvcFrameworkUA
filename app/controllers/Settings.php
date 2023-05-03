<?php
class Settings extends Controller implements MainFunctionalities, Crud
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }
    public function index()
    {
        session_activity_checker();
        if (!isLoggedIn()) redirect('pages/index');
        $users_not_admins = $this->userModel->getUsersByRole('user');
        $users_admins = $this->userModel->getUsersByRole('admin');
        $data = [
            'password' => '',
            'new_password' => '',
            'confirm_new_password' => '',
            'user_acc_pass' => '',
            'email' => '',
            'changed_email' => '',
            'reset_pass_tab_active' => 1,
            'change_pass_tab_active' => 0,
            'rem_account_tab_active' => 0,
            'change_email_tab_active' => 0,
            'make_admin_tab_active' => 0,
            'remove_admin_tab_active' => 0,
            'users_not_admins' => $users_not_admins,
            'users_admins' => $users_admins,
            'errors' => [
                'email_err' => '',
                'password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => '',
                'user_acc_pass_err' => '',
                'changed_email_err' => '',
                'make_admin_err' => '',
                'remove_admin_err' => ''
            ]
        ];
        $this->view('settings/settings', $data);
    }
    public function add()
    {
    }
    public function list($page = null)
    {
    }
    public function edit($user_id)
    {
        session_activity_checker();
        if (!is_query_post() || !isLoggedIn() || empty($user_id)) {
            redirect('pages/index');
        }
        $data = $this->settingsModel->editSettings($user_id);
        if (!is_array_empty($data['data']['errors'])) {
            flash('notifycationBox', join(" ", $data['data']['errors']), 'alert alert-danger');
            $this->view('bets/edit', $data['data']);
            return;
        }
        flash('notifycationBox', 'Settings updated successfull', 'alert alert-success');
        redirect('settings/settings');
    }
    //this will call only when user delete his account
    public function delete($id)
    {
    }

    public function uploadUserPhoto($user_id)
    {
        session_activity_checker();
        if (!is_query_post() || !isLoggedIn() || empty($user_id)) {
            redirect('pages/index');
        }
        $result = $this->userModel->uploadUsrPhoto($user_id);
        if ($result['success']) {
            flash('notifycationBox', 'Profile photo updated successful', 'alert alert-success');
            redirect('settings/settings');
            return;
        }
        flash('notifycationBox', ($result['msg'] ? $result['msg'] : 'There is some problem uploading image'), 'alert alert-danger');
        redirect('settings/settings');
    }

    public function changeUserPassword($user_id)
    {
        session_activity_checker();
        if (!isLoggedIn() || !$user_id) redirect('pages/index');
        try {
            $users_not_admins = $this->userModel->getUsersByRole('user');
            $users_admins = $this->userModel->getUsersByRole('admin');
            $data = [
                'password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'user_acc_pass' => '',
                'email' => '',
                'changed_email' => '',
                'reset_pass_tab_active' => 0,
                'change_pass_tab_active' => 1,
                'rem_account_tab_active' => 0,
                'change_email_tab_active' => 0,
                'make_admin_tab_active' => 0,
                'remove_admin_tab_active' => 0,
                'users_not_admins' => $users_not_admins,
                'users_admins' => $users_admins,
                'errors' => [
                    'email_err' => '',
                    'password_err' => '',
                    'new_password_err' => '',
                    'confirm_new_password_err' => '',
                    'user_acc_pass_err' => '',
                    'changed_email_err' => '',
                    'make_admin_err' => '',
                    'remove_admin_err' => ''
                ]
            ];
            if (!is_query_post()) {
                $this->view('settings/settings', $data);
                return;
            }
            $result = $this->userModel->changePassword($user_id);
            $data['errors'] = $result['errors'];
            if ($result['success']) {
                $this->userModel->sendChangedPassEmail(['user_id' => $user_id, 'pass' => $result['password']]);
                flash('notifycationBox', 'Password updated successful', 'alert alert-success');
                redirect('settings/settings');
                return;
            }
            flash('notifycationBox', 'Password update failed', 'alert alert-danger');
            $this->view('settings/settings', $data);
        } catch (Exception $ex) {
            flash('notifycationBox', $ex->getMessage(), 'alert alert-danger');
            redirect('settings/settings');
        }
    }

    public function resetUserPassword($user_id)
    {
        session_activity_checker();
        if (!isLoggedIn() || !$user_id) redirect('pages/index');
        try {
            $users_not_admins = $this->userModel->getUsersByRole('user');
            $users_admins = $this->userModel->getUsersByRole('admin');
            $data = [
                'password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'user_acc_pass' => '',
                'email' => '',
                'changed_email' => '',
                'reset_pass_tab_active' => 1,
                'change_pass_tab_active' => 0,
                'rem_account_tab_active' => 0,
                'change_email_tab_active' => 0,
                'make_admin_tab_active' => 0,
                'remove_admin_tab_active' => 0,
                'users_not_admins' => $users_not_admins,
                'users_admins' => $users_admins,
                'errors' => [
                    'email_err' => '',
                    'password_err' => '',
                    'new_password_err' => '',
                    'confirm_new_password_err' => '',
                    'user_acc_pass_err' => '',
                    'changed_email_err' => '',
                    'make_admin_err' => '',
                    'remove_admin_err' => ''
                ]
            ];
            if (!is_query_post()) {
                $this->view('settings/settings', $data);
                return;
            }
            $result = $this->userModel->resetPassword($user_id);
            $data['errors'] = $result['errors'];
            if ($result['success']) {
                $this->userModel->sendResetPassEmail(['user_id' => $user_id, 'pass' => $result['password']]);
                flash('notifycationBox', 'Password reset successful', 'alert alert-success');
                redirect('settings/settings');
                return;
            }
            flash('notifycationBox', 'Password reset failed', 'alert alert-danger');
            $this->view('settings/settings', $data);
        } catch (Exception $ex) {
            flash('notifycationBox', $ex->getMessage(), 'alert alert-danger');
            redirect('settings/settings');
        }
    }

    public function changeEmail($user_id)
    {
        session_activity_checker();
        if (!isLoggedIn() || !$user_id) redirect('pages/index');
        try {
            $users_not_admins = $this->userModel->getUsersByRole('user');
            $users_admins = $this->userModel->getUsersByRole('admin');
            $data = [
                'password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'user_acc_pass' => '',
                'email' => '',
                'changed_email' => '',
                'reset_pass_tab_active' => 0,
                'change_pass_tab_active' => 0,
                'rem_account_tab_active' => 0,
                'change_email_tab_active' => 1,
                'make_admin_tab_active' => 0,
                'remove_admin_tab_active' => 0,
                'users_not_admins' => $users_not_admins,
                'users_admins' => $users_admins,
                'errors' => [
                    'email_err' => '',
                    'password_err' => '',
                    'new_password_err' => '',
                    'confirm_new_password_err' => '',
                    'user_acc_pass_err' => '',
                    'changed_email_err' => '',
                    'make_admin_err' => '',
                    'remove_admin_err' => ''
                ]
            ];
            if (!is_query_post()) {
                $this->view('settings/settings', $data);
                return;
            }
          
            $new_user_email = escapeField($_POST['changed_email']);
            if($this->userModel->findUserByEmail($new_user_email)) {
                flash('notifycationBox', 'This email is used.', 'alert alert-danger');
                redirect('settings/settings');
                return;
            }
            $result = $this->userModel->changeEmail($user_id);
            if ($result['success']) {
                flash('notifycationBox', 'Email updated successful', 'alert alert-success');
                redirect('settings/settings');
                return;
            }
            flash('notifycationBox', 'Email updated failed<br />' . $result['changed_email_err'] , 'alert alert-danger');
            $this->view('settings/settings', $data);
        } catch (Exception $ex) {
            flash('notifycationBox', $ex->getMessage(), 'alert alert-danger');
            redirect('settings/settings');
        }
    }

    public function makeAdmin()
    {
        session_activity_checker();
        if (!isOwner()) redirect('pages/index');
        try 
        {
            $users_not_admins = $this->userModel->getUsersByRole('user');
            $users_admins = $this->userModel->getUsersByRole('admin');
            $data = [
                'password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'user_acc_pass' => '',
                'email' => '',
                'changed_email' => '',
                'reset_pass_tab_active' => 0,
                'change_pass_tab_active' => 0,
                'rem_account_tab_active' => 0,
                'change_email_tab_active' => 0,
                'make_admin_tab_active' => 1,
                'remove_admin_tab_active' => 0,
                'users_not_admins' => $users_not_admins,
                'users_admins' => $users_admins,
                'errors' => [
                    'email_err' => '',
                    'password_err' => '',
                    'new_password_err' => '',
                    'confirm_new_password_err' => '',
                    'user_acc_pass_err' => '',
                    'changed_email_err' => '',
                    'make_admin_err' => '',
                    'remove_admin_err' => ''
                ]
            ];
            if (!is_query_post()) {
                $this->view('settings/settings', $data);
                return;
            }
            $result = $this->userModel->makeUserAdmin();
            $data['errors'] = $result['errors'];
            if ($result['success']) {
                flash('notifycationBox', 'User now is admin', 'alert alert-success');
                redirect('settings/settings');
                return;
            }
            flash('notifycationBox', 'There is some problem making user admin', 'alert alert-danger');
            $this->view('settings/settings', $data);

        } catch (Exception $ex)
        {
            flash('notifycationBox', $ex->getMessage(), 'alert alert-danger');
            redirect('settings/settings');
        }
    }

    public function removeAdmin()
    {
        session_activity_checker();
        if (!isOwner()) redirect('pages/index');
        try 
        {
            $users_not_admins = $this->userModel->getUsersByRole('user');
            $users_admins = $this->userModel->getUsersByRole('admin');
            $data = [
                'password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'user_acc_pass' => '',
                'email' => '',
                'changed_email' => '',
                'reset_pass_tab_active' => 0,
                'change_pass_tab_active' => 0,
                'rem_account_tab_active' => 0,
                'change_email_tab_active' => 0,
                'make_admin_tab_active' => 0,
                'remove_admin_tab_active' => 1,
                'users_not_admins' => $users_not_admins,
                'users_admins' => $users_admins,
                'errors' => [
                    'email_err' => '',
                    'password_err' => '',
                    'new_password_err' => '',
                    'confirm_new_password_err' => '',
                    'user_acc_pass_err' => '',
                    'changed_email_err' => '',
                    'make_admin_err' => '',
                    'remove_admin_err' => ''
                ]
            ];
            if (!is_query_post()) {
                $this->view('settings/settings', $data);
                return;
            }
            $result = $this->userModel->removeAdmin(escapeField($_POST['remove_admin']));
            $data['errors'] = $result['errors'];
            if ($result['success']) {
                flash('notifycationBox', $result['msg'], 'alert alert-success');
                redirect('settings/settings');
                return;
            }
            flash('notifycationBox', 'There is some problem removing user from admins', 'alert alert-danger');
            $this->view('settings/settings', $data);

        } catch (Exception $ex)
        {
            flash('notifycationBox', $ex->getMessage(), 'alert alert-danger');
            redirect('settings/settings');
        }
    }

    public function removeAccount($user_id)
    {
        session_activity_checker();
        if (!isLoggedIn()) redirect('pages/index');
        try {
            $id = escapeField($user_id);
            $user_current_pass = $this->userModel->getUserPassById($id)->password;
            $validate_passwords = $this->userModel->compareUserPasswords($user_current_pass);
            if (!$validate_passwords['success']) {
                $users_not_admins = $this->userModel->getUsersByRole('user');
                $users_admins = $this->userModel->getUsersByRole('admin');
                $data = [
                    'password' => '',
                    'new_password' => '',
                    'confirm_new_password' => '',
                    'user_acc_pass' => '',
                    'email' => '',
                    'changed_email' => '',
                    'reset_pass_tab_active' => 0,
                    'change_pass_tab_active' => 0,
                    'rem_account_tab_active' => 1,
                    'change_email_tab_active' => 0,
                    'make_admin_tab_active' => 0,
                    'remove_admin_tab_active' => 0,
                    'users_not_admins' => $users_not_admins,
                    'users_admins' => $users_admins,
                    'errors' => [
                        'email_err' => '',
                        'password_err' => '',
                        'new_password_err' => '',
                        'confirm_new_password_err' => '',
                        'user_acc_pass_err' => '',
                        'changed_email_err' => '',
                        'make_admin_err' => '',
                        'remove_admin_err' => ''
                    ]
                ];
                $data['errors'] = $validate_passwords['errors'];
                flash('notifycationBox', $validate_passwords['msg'], 'alert alert-danger');
                $this->view('settings/settings', $data);
                return;
            }

            //remove user's profile photo
            $directory = 'images' . DIRECTORY_SEPARATOR . 'user_' . $id;
            deleteDirAndFiles($directory);

            $this->userModel->removeUserPermamently($id);

            flash('notifycationBox', 'Your account is successfully removed', 'alert alert-success');
            removeSession();
            resetCookie();
            redirect('pages/index');
        } catch (Exception $ex) {
            flash('notifycationBox', $ex->getMessage(), 'alert alert-danger');
            redirect('settings/settings');
        }
    }
}
