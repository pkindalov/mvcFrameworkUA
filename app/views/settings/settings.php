<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row my-5">
    <div class="col-lg-12 col-md-12 col-sm-12">
        User Settings
        <hr />
        <p>Change your profile image</p>
        <div name="current_user_profile_img_cont" class="text-center mt-4 mb-4">
            <?php if (is_user_profile_img()) : ?>
                <img class="img-responsive img-rounded avatarBigger" name="current_user_profile_img" src="<?php echo URLROOT; ?>/images/user_<?php echo $_SESSION['user_id']; ?>/<?php echo $_SESSION['user_profile_img'] ?>" alt="profile photo" />
            <?php else : ?>
                <img class="img-responsive img-rounded avatarBigger " name="current_user_profile_img" src="<?php echo URLROOT; ?>/images/default_user.jpg" alt="default profile photo" />
            <?php endif; ?>
        </div>
        <form action="<?php echo URLROOT; ?>/settings/uploadUserPhoto/<?php echo $_SESSION['user_id']; ?>" method="post" enctype="multipart/form-data">
            <div class="custom-file">
                <input type="file" name="profile_photo" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <button name="uploadBtn" class="btn btn-primary btn-block col-lg-1 col-md-1 col-sm-1 mt-3 mb-3" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
        </form>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php echo ($data['reset_pass_tab_active'] ? 'active' : ''); ?>" data-toggle="tab" href="#resetPass">Reset Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($data['change_pass_tab_active'] ? 'active' : ''); ?>" data-toggle="tab" href="#changePassword">Change Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($data['change_email_tab_active'] ? 'active' : ''); ?>" data-toggle="tab" href="#changeEmail">Change Email</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger <?php echo ($data['rem_account_tab_active'] ? 'active' : ''); ?>" data-toggle="tab" href="#removeAccount">Remove Account</a>
            </li>
            <?php if (isOwner()) : ?>
                <li class="nav-item">
                    <a class="nav-link text-warning <?php echo ($data['make_admin_tab_active'] ? 'active' : ''); ?>" data-toggle="tab" href="#makeAdmin">Make Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning <?php echo ($data['remove_admin_tab_active'] ? 'active' : ''); ?>" data-toggle="tab" href="#removeAdmin">Remove Admin</a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane container <?php echo ($data['reset_pass_tab_active'] ? 'active' : 'fade'); ?>" id="resetPass">
                <div class="row my-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form action="<?php echo URLROOT; ?>/settings/resetUserPassword/<?php echo $_SESSION['user_id']; ?>" method="post">
                            <div class="form-group">
                                <label for="email">Enter Your Email: <sup>*</sup></label>
                                <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['errors']['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                                <span id="email_span_err" class="invalid-feedback"><?php echo $data['errors']['email_err']; ?></span>
                            </div>
                            <button name="resetBtn" class="btn btn-primary btn-block col-lg-1 col-md-1 col-sm-1 mt-3 mb-3" type="submit"><i class="fa fa-wrench" aria-hidden="true"></i> Reset</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane container <?php echo ($data['change_pass_tab_active'] ? 'active' : 'fade'); ?>" id="changePassword">
                <div class="row my-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form action="<?php echo URLROOT; ?>/settings/changeUserPassword/<?php echo $_SESSION['user_id']; ?>" method="post">
                            <div class="form-group">
                                <label for="oldPassword">old Password: <sup>*</sup></label>
                                <input type="password" name="oldPassword" class="form-control form-control-lg <?php echo (!empty($data['errors']['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                                <span id="old_password_span_err" class="invalid-feedback"><?php echo $data['errors']['password_err']; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">Enter New Password: <sup>*</sup></label>
                                <input type="password" name="newPassword" class="form-control form-control-lg <?php echo (!empty($data['errors']['new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_password']; ?>">
                                <span id="new_password_span_err" class="invalid-feedback"><?php echo $data['errors']['new_password_err']; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword">Confirm New Password: <sup>*</sup></label>
                                <input type="password" name="confirmNewPassword" class="form-control form-control-lg <?php echo (!empty($data['errors']['confirm_new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_new_password']; ?>">
                                <span id="confirm_new_password_span_err" class="invalid-feedback"><?php echo $data['errors']['confirm_new_password_err']; ?></span>
                            </div>
                            <button name="changePwdBtn" class="btn btn-primary btn-block col-lg-1 col-md-1 col-sm-1 mt-3 mb-3" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane container <?php echo ($data['change_email_tab_active'] ? 'active' : 'fade'); ?>" id="changeEmail">
                <div class="row my-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form action="<?php echo URLROOT; ?>/settings/changeEmail/<?php echo $_SESSION['user_id']; ?>" method="post">
                            <div class="form-group">
                                <label for="email">Enter New Email: <sup>*</sup></label>
                                <input type="email" name="changed_email" class="form-control form-control-lg <?php echo (!empty($data['errors']['changed_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                                <span id="changed_email_span_err" class="invalid-feedback"><?php echo $data['errors']['changed_email_err']; ?></span>
                            </div>
                            <button name="changeEmailBtn" class="btn btn-primary btn-block col-lg-1 col-md-1 col-sm-1 mt-3 mb-3" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane container <?php echo ($data['rem_account_tab_active'] ? 'active' : 'fade'); ?>" id="removeAccount">
                <div class="row my-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form action="<?php echo URLROOT; ?>/settings/removeAccount/<?php echo $_SESSION['user_id']; ?>" method="post">
                            <div class="form-group">
                                <label for="password">Enter Password: <sup>*</sup></label>
                                <input type="password" name="user_acc_password" class="form-control form-control-lg <?php echo (!empty($data['errors']['user_acc_pass_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user_acc_pass']; ?>">
                                <span id="user_acc_password_span_err" class="invalid-feedback"><?php echo $data['errors']['user_acc_pass_err']; ?></span>
                            </div>


                            <!-- Button trigger modal -->
                            <button type="button" name="remAccBtn" class="btn btn-danger mb-5 mt-1" data-toggle="modal" data-target="#deleteProfileWarningModal">
                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="deleteProfileWarningModal" tabindex="-1" role="dialog" aria-labelledby="deleteProfileWarningModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteProfileWarningModalLabel">Are you sure?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to <i class="fa fa-trash" aria-hidden="true"></i> delete your profile ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button class="btn btn-danger btn-block col-lg-3 col-md-3 col-sm-3 mt-3 mb-3" type="submit"><i class="fa fa-trash" aria-hidden="true"></i> Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (isOwner()) : ?>
                <div class="tab-pane container <?php echo ($data['make_admin_tab_active'] ? 'active' : 'fade'); ?>" id="makeAdmin">
                    <div class="row my-5">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div id="admin_users_container" class="list-group mb-3"></div>
                            <div>
                                <button id="showHideAllAdmins" class="btn btn-primary mb-5">Show All Admins</button>
                            </div>

                            <form action="<?php echo URLROOT; ?>/settings/makeAdmin/" method="post">
                                <div class="form-group">
                                    <label for="make_admin">Select user to make admin: <sup>*</sup></label>
                                    <select name="make_admin" id="make_admin" class="form-control form-control-lg <?php echo (!empty($data['errors']['make_admin_err'])) ? 'is-invalid' : ''; ?>">
                                        <option value="">No users found</option>
                                        <?php if ($data['users_not_admins'] && count($data['users_not_admins']) > 0) : ?>
                                            <?php foreach ($data['users_not_admins'] as $key => $user) : ?>
                                                <option value="<?php echo $user->id; ?>"><?php echo $user->name . ' - ' . $user->email; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <!-- <input type="email" name="changed_email" class="form-control form-control-lg <?php echo (!empty($data['errors']['make_admin_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>"> -->
                                    <span id="make_admin_err" class="invalid-feedback"><?php echo $data['errors']['make_admin_err']; ?></span>
                                </div>
                                <button name="makeAdminBtn" class="btn btn-primary btn-block col-lg-1 col-md-1 col-sm-1 mt-3 mb-3" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>



            <?php if (isOwner()) : ?>
                <div class="tab-pane container <?php echo ($data['remove_admin_tab_active'] ? 'active' : 'fade'); ?>" id="removeAdmin">
                    <div class="row my-5">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <!-- <div id="admin_users_container" class="list-group mb-3"></div>
                            <div>
                                <button id="showHideAllAdmins" class="btn btn-primary mb-5">Show All Admins</button>
                            </div> -->

                            <form action="<?php echo URLROOT; ?>/settings/removeAdmin/" method="post">
                                <div class="form-group">
                                    <label for="remove_admin">Select user to remove from admins: <sup>*</sup></label>
                                    <select name="remove_admin" id="remove_admin" class="form-control form-control-lg <?php echo (!empty($data['errors']['remove_admin_err'])) ? 'is-invalid' : ''; ?>">
                                        <option value="">No users found</option>
                                        <?php if ($data['users_admins'] && count($data['users_admins']) > 0) : ?>
                                            <?php foreach ($data['users_admins'] as $key => $user) : ?>
                                                <option value="<?php echo $user->id; ?>"><?php echo $user->name . ' - ' . $user->email; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <!-- <input type="email" name="changed_email" class="form-control form-control-lg <?php echo (!empty($data['errors']['make_admin_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>"> -->
                                    <span id="remove_admin_err" class="invalid-feedback"><?php echo $data['errors']['remove_admin_err']; ?></span>
                                </div>
                                <button name="removeAdminBtn" class="btn btn-primary btn-block col-lg-1 col-md-1 col-sm-1 mt-3 mb-3" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>





            <!-- <div class="tab-pane container fade" id="menu2">2</div> -->
        </div>
    </div>
</div>

<script>
    const baseURL = '<?php echo URLROOT; ?>'
</script>
<?php if(isOwner()) : ?>
<script src="<?php echo URLROOT; ?>/js/showHideAdminUsers.js"></script>
<?php endif; ?>
<script src="<?php echo URLROOT; ?>/js/settings.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>