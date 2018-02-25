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
      
      <div class="panel-content refined-tab">
      <div class="nav-tabs3">
        <ul id="myTab6" class="nav nav-tabs">
            <?php foreach($heatmaps as $h): ?>
              <?php if($h['id'] == $current_id)$class="active";else $class=''; ?>
              <li class="<?php echo $class; ?>"><a href="<?php echo '/heatmaps/'.$h['id']; ?>" style="cursor: pointer;"> <i class="icon-layers"></i> <?php echo $h['page_name']; ?></a></li>
            <?php endforeach; ?>
           </ul>
           <div style="position: absolute;right: 0;top: 8px;">
              <a href="<?php echo base_url(); ?>heatmaps/add"><button class="btn btn-sm btn-rounded btn-success pull-right">Add heatmaps</button></a>
              <a href="<?php echo base_url(); ?>heatmaps/list"><button class="btn btn-sm btn-rounded btn-info pull-right">List heatmaps</button></a>   
           </div>
             
                     
                     

      </div>
      </div>
      <div class="panel-content">
        <div class="row">
          
          <div class="col-md-12 col-sm-12 col-xs-12">
          <iframe src="<?php echo $heatmap['embed_url']; ?>" height="1000px" style="border:0px;width:100%"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      
<?php $this->load->view(get_template_directory().'footer_new'); ?>
