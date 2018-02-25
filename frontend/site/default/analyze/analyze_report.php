<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>
<div class="page-container"> 
    <!-- Content -->
    <div class="page-content">
        <div class="page-content-inner"> 
            <!-- Page header -->
            <div class="page-header">
                <div class="page-title profile-page-title">
                    <h2>Analyze Your Domain</h2>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12 col-lg-10 col-lg-offset-1">
                    <?php 
                        echo '<pre>';
                        print_r($reports);
                        echo '</pre>';
                    ?>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?php $this->load->view(get_template_directory() . 'footer'); ?>