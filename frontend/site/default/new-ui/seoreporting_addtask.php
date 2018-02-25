<?php require 'parts/top.php'; ?>
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>ADD TASK</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>seoreporting/addjob" method="post" class="edit-profile-form cf" style="width: 100%;">
                    <div class="field">
                        <label for="">Choose project:</label>
                        <select name="project" id="proeject" class="form-control">
                            <?php foreach($projects as $project): ?>
                            <option value="<?php echo $project['id']; ?>"><?php echo $project['project_name']; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="">Task name:</label>
                        <input type="text" name="task_name" id="task_name" placeholder="Task Name">
                    </div>
                    <div class="field">
                        <label for="">Notes:</label>
                        <textarea id="notes" name="notes" placeholder="Notes"></textarea>
                    </div>
                    <div class="field">
                        <label for="recurring"><input type="checkbox" id="recurring" name="recurring" value="on"> Recurring</label>
                    </div>
                    <div class="field">
                        <label>Recurring Days</label>
                        <select name="recurring_days" id="recurring_days">
                            <?php for($i=1;$i<=31;$i++): ?>
                             <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label>Cost</label>
                        <input type="text" name="cost" id="cost" >
                    </div>
                    <hr class="fl dib w-100" style="margin-top:0;">
                    <div class="fl dib w-100 cf">
                        <button type="submit" onclick="return validate_add_serp_reporting_task();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">ADD</span>
                        </button>                       
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>

