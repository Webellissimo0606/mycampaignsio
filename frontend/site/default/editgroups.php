<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'parts/top.php';
?>

<div class="content-row">
    <div class="content-column w-100">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT GROUP</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>editgroups" method="post" class="edit-profile-form cf" style="width: 100%;">
                    <div class="field">
                        <label for="">Group name:</label>
                        <input type="text" name="group_name" placeholder="Group name" value="<?php echo $groupdetail['group_name']; ?>">
                    </div>
                    <input type="hidden" name="groupid" value="<?php echo $groupdetail['id']; ?>">
                    <hr class="relative dib w-100">
                    <button type="submit" onclick="return validate_group_form();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">UPDATE GROUP</span>
                    </button>
                    <a href="<?php echo site_url(); ?>listgroups" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>