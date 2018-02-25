Welcome to <?php echo $site_name; ?>,

Thanks for joining <?php echo $site_name; ?>. We listed your sign in details below, make sure you keep them safe.
<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Your email address: <?php echo $email; ?>
<?php if (isset($password)) { /* ?>

Your password: <?php echo $password; ?>
<?php */ } ?>



Have fun!
The <?php echo $site_name; ?> Team