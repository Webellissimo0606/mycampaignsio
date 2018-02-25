<?php require 'parts/top.php'; ?>
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>TASK LIST</h3>
                </div>
                <div class="left-pos">
                    <label>Filter: </label>
                    <select id="filter_status" name="filter_status" onchange="window.location.href='/seoreporting/listjobs?status='+this.value">
                      <option value="">Choose filter</option>  
                      <option value="COMPLETE" <?php if($filter == 'COMPLETE')echo 'selected="selected"'; ?>>COMPLETE</option>  
                      <option value="INCOMPLETE" <?php if($filter == 'INCOMPLETE')echo 'selected="selected"'; ?>>INCOMPLETE</option>  
                    </select>
                </div>
                <div class="right-pos">
                    <a href="<?php echo base_url(); ?>seoreporting/getprojects" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">LIST PROJECT</small></a>

                    <a href="<?php echo base_url(); ?>seoreporting/addjob" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW JOB</small></a>
                </div>
            </div>
            <div class="content-column-inner" style="width:60%;vertical-align: top;">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Recurring</th>
                                <th>Added on</th>
                                <th>Due on</th>
                                <th>Status</th>
                                <th>Cost</th>
                                <th data-sortable="false"></th>
                            </tr>
                        </thead>
                        <tbody> <?php
                            if( is_array($tasks) && ! empty($tasks) ){
                                $counter = 0;
                                foreach($tasks as $task){
                                    $counter++;
                                    ?>
                                    <tr>
                                        <td class="tl"><?php echo $task['task_name']; ?></td>
                                        <td class="tl"><?php echo $task['project_name']; ?></td>
                                        <td>
                                            <select name="recurring" id="change_recurring_status" onchange="changeRecurringStatus(this, '<?php echo $task['task_id']; ?>')">
                                                <option value="TRUE" <?php if($task['recurring'] == 'TRUE')echo 'selected="selected"';  ?>>TRUE
                                                </option>
                                                <option value="FALSE" <?php if($task['recurring'] == 'FALSE')echo 'selected="selected"'; ?>>FALSE</option>
                                            </select>   
                                        </td>
                                        <td>
                                            <?php echo date('d M Y',strtotime($task['start_date'])); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($task['recurring'] == 'TRUE'){
                                             echo date('d M Y',strtotime($task['end_date'])); 
                                            }else{
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $task['status']; ?>
                                        </td>
                                        
                                        <td><?php echo '$'.$task['cost']; ?></td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="getTaskDetail('<?php echo $task['task_id']; ?>')" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">View Tasks</small></a>

                                            <a href="<?php echo site_url(); ?>seoreporting/<?php echo $task['task_id']; ?>/deletejob" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_delete_task()"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>

                                        </td>
                                    </tr>
                                     <?php
                                }
                            }
                            else{ ?>
                                <tr>
                                    <td colspan="8">No entries found</td>
                                </tr> <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-column-inner" style="width:35%;vertical-align: top;" id="load_view_task">
                
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>
