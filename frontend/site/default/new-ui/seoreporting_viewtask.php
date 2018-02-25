<style type="text/css">
	.task_list_field{
		margin-bottom:15px;
	}
</style>
<div class="task_list_field">
	<label for="completetask"> <input type="checkbox" name="completetask" value="1" id="completetask" onclick="add_complete_task(this,'<?php echo $tasklog_detail['id']; ?>');" <?php if($tasklog_detail['status'] == 'COMPLETE')echo 'checked="checked"'; ?>> Complete Job</label>
</div>
<div class="task_list_field">
    <b>Task name:</b>  <?php echo $task_detail['task_name']; ?>
</div>
<div class="task_list_field">
   <b>Cost:</b> $ <?php echo $tasklog_detail['cost']; ?>
</div>
<div class="task_list_field">
    <b>Status:</b> <?php echo $tasklog_detail['status']; ?>
</div>
<div class="task_list_field">
    <b>Notes:</b> <?php echo nl2br($tasklog_detail['notes']); ?>
</div>
<div class="task_list_field">
    <b>Start date:</b> <?php echo date('d M Y H:i', strtotime($tasklog_detail['start_date'])); ?>
</div>
<form action="javascript:void(0)" onsubmit="return add_task_notes('<?php echo $tasklog_detail['id']; ?>')">
<div class="task_list_field">
	<label>Add more notes</label><br><br>
	<textarea name="more_notes" id="more_notes" rows="5" cols="50"></textarea><br>
	<input type="submit" name="" value="submit notes" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1">
</div>
</form>
