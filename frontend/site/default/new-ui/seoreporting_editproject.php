<?php require 'parts/top.php'; ?>
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT PROJECT</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>seoreporting/<?php echo $projectdetail['id']; ?>/editproject" method="post" class="edit-profile-form cf" style="width: 100%;">
                    <div class="field">
                        <label for="">Project name:</label>
                        <input type="text" name="project_name" id="project_name" placeholder="Project Name" value="<?php echo $projectdetail['project_name']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Project Description:</label>
                        <textarea id="project_description" name="project_description" placeholder="Project Description"><?php echo nl2br($projectdetail['project_description']); ?></textarea>
                        
                    </div>
                    <div class="field">
                        <label for="">Client name:</label>
                        <input type="text" id="client_name" name="client_name" placeholder="Client Name" value="<?php echo $projectdetail['client_name']; ?>">
                        <input type="hidden" name="project_id" value="<?php echo $projectdetail['id']; ?>">
                    </div>
                    <hr class="fl dib w-100" style="margin-top:0;">
                    <div class="fl dib w-100 cf">
                        <button type="submit" onclick="return validate_edit_serp_reporting_project_form();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">UPDATE</span>
                        </button>                       
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>

