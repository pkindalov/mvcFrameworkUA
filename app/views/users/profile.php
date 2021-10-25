<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <script>
        const baseUrl = '<?php echo URLROOT; ?>';
        const userId = '<?php echo $data['user']->id; ?>';
    </script>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="jumbotron jumbotron-fluid text-center">
            <h4>Wellcome, <?php echo $_SESSION['user_name'] . ' <br /> ' . $data['user']->email; ?></h4>
            <?php if (is_user_profile_img()) : ?>
                <img class="img-responsive img-rounded avatarBiggerNoHover" name="current_user_profile_img" src="<?php echo URLROOT; ?>/images/user_<?php echo $_SESSION['user_id']; ?>/<?php echo $_SESSION['user_profile_img'] ?>" alt="profile photo" />
            <?php else : ?>
                <img class="img-responsive img-rounded avatarBiggerNoHover " name="current_user_profile_img" src="<?php echo URLROOT; ?>/images/default_user.jpg" alt="default profile photo" />
            <?php endif; ?>
            <h5 class="mt-3"><?php echo $data['user']->role; ?></h5>
        </div>
    </div>
</div>




<?php require APPROOT . '/views/inc/footer.php'; ?>