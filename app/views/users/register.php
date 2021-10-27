<?php require APPROOT . '/views/inc/header.php'; ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Create An Account</h2>
        <p>Please fill <i class="fa fa-pencil" aria-hidden="true"></i> out this form to register with us</p>
        <form action="<?php echo URLROOT; ?>/users/register" method="post">
          <div class="form-group">
            <label for="name"><i class="fa fa-pencil" aria-hidden="true"></i> Name: <sup>*</sup></label>
            <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
            <span id="name_span_err" class="invalid-feedback"><?php echo $data['name_err']; ?></span>
          </div>
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
            <label for="confirm_password"><i class="fa fa-key" aria-hidden="true"></i> Confirm Password: <sup>*</sup></label>
            <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
            <span id="confirm_password_span_err" class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
          </div>

          <div class="row">
            <div class="col">
              <button type="submit" name="regBtn" class="btn btn-success btn-block"><i class="fa fa-plus" aria-hidden="true"></i>  Register</button>
            </div>
            <div class="col">
              <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light btn-block">Have an account? Login <i class="fa fa-sign-in" aria-hidden="true"></i></a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script src="<?php echo URLROOT;?>/js/register.js"></script>  
<?php require APPROOT . '/views/inc/footer.php'; ?>
