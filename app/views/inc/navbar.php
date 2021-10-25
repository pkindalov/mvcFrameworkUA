<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URLROOT; ?>"><i class="fa fa-ticket" aria-hidden="true"></i> <?php echo SITENAME; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="navbar-nav mr-auto">
        <?php if (isset($_SESSION['user_id'])) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/profile/<?php echo $_SESSION['user_id']; ?>"><?php echo (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Profile'); ?>
              <?php if (is_user_profile_img()) : ?>
                <img class="img-responsive img-rounded avatar" src="<?php echo URLROOT; ?>/images/user_<?php echo $_SESSION['user_id']; ?>/<?php echo $_SESSION['user_profile_img'] ?>" alt="profile photo" />
              <?php else : ?>
                <img class="img-responsive img-rounded avatar" src="<?php echo URLROOT; ?>/images/default_user.jpg" alt="profile photo" />
              <?php endif; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/settings/settings">Settings <i class="fa fa-sliders" aria-hidden="true"></i></a>
          </li>
          <!-- <?php //if (isAdmin() || isOwner()) : ?>
           
          <?php //endif; ?> -->
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php if (isLoggedIn()) : ?>
          
      
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout <i class="fa fa-sign-out" aria-hidden="true"></i></a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>">Home <i class="fa fa-home" aria-hidden="true"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About <i class="fa fa-info" aria-hidden="true"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register <i class="fa fa-plus" aria-hidden="true"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login <i class="fa fa-sign-in" aria-hidden="true"></i></a>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>
