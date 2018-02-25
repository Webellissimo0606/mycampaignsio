<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header_new');
?>
<style type="text/css">
  .error{
    border-color:#ff0000;
  }
</style>
      
<div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-header">
        <h3>Edit heatmaps</h3>
      </div>

      <div class="panel-content">
        <div class="row">
          
          <div class="col-md-12 col-sm-12 col-xs-12">
          <form action="/analytics/heatmaps/edit" method="post" onsubmit="return validate();">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Page name:</label>
                 <input type="text" name="page_name" id="page_name" class="form-control" value="<?php echo $heatmap['page_name']; ?>">
                </div>
              </div>
              </div>
               <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Hotjar url:</label>
                  <input type="text" name="embed_url" id="embed_url" class="form-control" value="<?php echo $heatmap['embed_url']; ?>">
                  <input type="hidden" name="id" value="<?php echo $heatmap['id']; ?>">
                </div>
              </div>
             </div> 
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <input type="submit" class="btn btn-default" name="" value="Update">
                </div>
              </div>
            </div>  
            <?php echo form_close(); ?> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      
<?php $this->load->view(get_template_directory().'footer_new'); ?>
<script type="text/javascript">
  function validate()
  {
   
        var error  = false;
        if($('#page_name').val() == '') {
          $('#page_name').addClass('error');
          error = true;
        } 
        if($('#embed_url').val() == '') {
          $('#embed_url').addClass('error');
          error = true;
        }
        if (error == true) {
            return false
        }else{
          return true;
        }

    }
</script>
