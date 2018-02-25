<?php require 'parts/top.php'; ?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>ADD SUBUSER</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>addsubuser" method="post" class="edit-profile-form cf" style="width: 100%;">
                    <div class="field">
                        <label for="">First name:</label>
                        <input type="text" name="first_name" placeholder="First name">
                    </div>
                    <div class="field">
                        <label for="">Last name:</label>
                        <input type="text" name="last_name" placeholder="Last name">
                    </div>
                    <div class="field">
                        <label for="">Email address:</label>
                        <input type="text" name="email" placeholder="Email address">
                    </div>
                    <div class="field">
                        <label for="">Username:</label>
                        <input type="text" name="username" placeholder="Username">
                    </div>
                    <div class="field">
                        <label for="">Password:</label>
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div class="field">
                        <label for="">Confirm password:</label>
                        <input type="password" name="confirm_password" placeholder="Confirm password">
                    </div>
                    <hr class="fl dib w-100" style="margin-top:0;">
                    <div class="fl dib w-100 cf">
                        <button type="submit" onclick="return validate_add_user_form();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">SAVE SUBUSER</span>
                        </button>
                        <a href="<?php echo site_url(); ?>listsubuser" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>