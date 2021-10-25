<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <?php flash('notifycationBox'); ?>
      <h2>Login</h2>
      <p>Please <i class="fa fa-pencil" aria-hidden="true"></i> fill in your credentials to log in</p>
      <form action="<?php echo URLROOT; ?>/users/login" method="post">
        <div class="form-group">
          <label for="email"><i class="fa fa-envelope" aria-hidden="true"></i> Email: <sup>*</sup></label>
          <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
          <span id="email_span_err" class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>
        <div class="form-group">
          <label for="password"><i class="fa fa-key" aria-hidden="true"></i> Password: <sup>*</sup></label>
          <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
          <span id="password_span_err" class="invalid-feedback"><?php echo $data['password_err']; ?></span>
        </div>
        <div class="form-group">
          <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="rememberme">
            <label class="form-check-label" for="rememeberme">Remember me <i class="fa fa-repeat" aria-hidden="true"></i></label>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <button type="submit" name="loginBtn" class="btn btn-success btn-block"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
            <?php if ($data['has_fb_login_btn']) : ?>
              <a class="btn btn-primary btn-block mt-3" href="<?php echo $data['fb_login_url']; ?>"><i class="fa fa-facebook" aria-hidden="true"></i> Log with Facebook</a>
            <?php endif; ?>
            <?php if ($data['has_google_login_btn']) : ?>
              <a class="btn btn-warning btn-block mt-3" href="<?php echo $data['google_login_url']; ?>"><i class="fa fa-google" aria-hidden="true"></i> Log with Google</a>
            <?php endif; ?>
          </div>
          <div class="col"><a href="<?php echo URLROOT; ?>/users/register" class="btn btn-light btn-block">No account? Register <i class="fa fa-plus" aria-hidden="true"></i></a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="<?php echo URLROOT; ?>/js/login.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>


<!-- <?php require_once APPROOT . '/views/inc/header.php' ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Login</h2>
            <p>Please fill out this form to login</p>
            <form action="<?php echo URLROOT . '/users/login' ?>" method="post">
                <div class="form-group">
                    <label for="email">Email: <sub>*</sub></label>
                    <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?>" value="<?php echo $data['email']; ?>" />
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password: <sub>*</sub></label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?>" value="<?php echo $data['password']; ?>" />
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Login" class="btn btn-success btn-block" />
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-light btn-block">Don't have an account? Register</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php' ?> -->