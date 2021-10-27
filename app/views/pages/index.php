<?php require_once APPROOT . '/views/inc/header.php' ?>
<?php if (isLoggedIn()) : ?>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="jumbotron jumbotron-fluid text-center">
                <h4>Wellcome, <?php echo $_SESSION['user_name']; ?></h4>
                <?php if (is_user_profile_img()) : ?>
                    <img class="img-responsive img-rounded avatarBiggerNoHover" name="current_user_profile_img" src="<?php echo URLROOT; ?>/images/user_<?php echo $_SESSION['user_id']; ?>/<?php echo $_SESSION['user_profile_img'] ?>" alt="profile photo" />
                <?php else : ?>
                    <img class="img-responsive img-rounded avatarBiggerNoHover " name="current_user_profile_img" src="<?php echo URLROOT; ?>/images/default_user.jpg" alt="default profile photo" />
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9">
            <div class="jumbotron jumbotron-fluid text-center">
                <div class="container">
                    <h1 class="display-3"><?php echo $data['title']; ?></h1>
                    <p class="lead"><?php echo $data['description'] ?></p>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['title']; ?></h1>
            <p class="lead"><?php echo $data['description'] ?></p>
        </div>
    </div>
<?php endif; ?>
<?php require_once APPROOT . '/views/inc/footer.php' ?>
