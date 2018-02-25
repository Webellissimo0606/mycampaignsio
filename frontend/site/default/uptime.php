<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

<div class="row"> 
    <!-- Content -->
    <div class="col-md-12">
        <div class="panel"> 
            <!-- Page header -->
            <div class="panel-header bg-green">
              <h3>Add site</h3>
            </div>
            <div class="panel-content">
              <?php
                $attributes = array('id' => 'addSiteUptime');
                echo form_open(site_url('uptime/uptime/addsiteuptime'), $attributes)
              ?>
                <div class="form-group">
                  <input type="text" value="" id="site_name" name="site_name" placeholder="Name"/>
                </div>
                <div class="form-group">
                  <input type="url" value="" id="site_url" name="site_url" placeholder="http://"/>
                </div>
               <div class="form-group">
                  <input type="text" value="" id="site_keywords" name="site_keywords" placeholder="Keywords"/>
                </div>
                
              <div class="form-group">
                 <button type="submit" name="submit" style="width: 94%">Add Site</button>
              </div>
              <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>