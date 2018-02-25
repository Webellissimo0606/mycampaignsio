<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header');
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel"> 
      <div class="panel-header bg-green">
        <h3>Update Domain</h3>
      </div>
      
      <div class="panel-content">
        <form class="validate" id="editlinkdomain" action="<?php echo site_url(); ?>editlinkdomain" method="post" onsubmit="return validate();">
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Domain link:</label>
                <input type="text" name="domain" id="domain" class="form-control" placeholder="Domain" value="<?php echo $domain['domain'];?>">
                <input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>">
              </div>
            </div>

            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Owner:</label>
                <input type="text" name="owner" id="domain" class="form-control" placeholder="Owner" value="<?php echo $domain['owner'];?>">
                <input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>">
              </div>
            </div>

            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <button name="button" type="Submit" class="btn btn-warning" id="button" value="true" style="margin-top:25px;">Update Domain</button>
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
    if($('#domain').val() == ''){
        alert('Please enter domain link');
        return false;
    } 
    return true;
  }

</script>
