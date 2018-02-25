<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'parts/top.php'; ?>

<div class="content-row">
    <div class="content-column w-100">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>ADD GROUP</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>addgroups" method="post" onsubmit="return validate();" class="edit-profile-form cf" style="width: 100%;">
                    <div class="field">
                        <label for="">Group name:</label>
                        <input type="text" name="group_name" placeholder="Group Name">
                    </div>
                    <div class="field">
                    </div>
                    <hr class="fl dib w-100" style="margin-top:0;">
                    <div class="fl dib w-100 cf">
                        <button type="submit" onclick="return validate_group_form();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">SAVE GROUP</span>
                        </button>
                        <a href="<?php echo site_url(); ?>listgroups" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>