<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header');
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel"> 
      <div class="panel-header bg-green">
        <h3>Add Groups</h3>
      </div>
      
      <div class="panel-content">
        <form class="validate" id="addGroup" action="<?php echo site_url(); ?>addgroups" method="post" onsubmit="return validate();">
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Group name:</label>
                <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Group Name">
              </div>
            </div>

            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <button name="button" type="Submit" class="btn btn-warning" id="button" value="true" style="margin-top:25px;">Add Group</button>
              </div>
            </div>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view(get_template_directory().'footer_new'); ?>
<script type="text/javascript">
  function validate()
  {
    if($('#group_name').val() == ''){
        alert('Please enter group name');
        return false;
    } 
    return true;
  }

</script>
