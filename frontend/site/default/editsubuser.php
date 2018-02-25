<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'parts/top.php';
?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT SUBUSER</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>editsubuser" method="post" class="edit-profile-form cf" style="width: 100%;">
                    <div class="field">
                        <label for="">First name:</label>
                        <input type="text" name="first_name" placeholder="First name" value="<?php echo $user['first_name']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Last name:</label>
                        <input type="text" name="last_name" placeholder="Last name" value="<?php echo $user['last_name']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Email address:</label>
                        <input type="text" name="email" placeholder="Email address" value="<?php echo $user['email']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Username:</label>
                        <input type="text" name="username" placeholder="Username" value="<?php echo $user['username']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Password:</label>
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div class="field">
                        <label for="">Confirm password:</label>
                        <input type="password" name="confirm_password" placeholder="Confirm password">
                    </div>
                    <input type="hidden" name="subuserid" value="<?php echo $user['id']; ?>">
                    <hr class="w-100">
                    <button type="submit" onclick="return validate_edit_user_form();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">UPDATE SUBUSER</span>
                    </button>
                    <a href="<?php echo site_url(); ?>listsubuser" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>