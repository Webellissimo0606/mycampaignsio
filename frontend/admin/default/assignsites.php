<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_theme_directory() . 'header');
?>

<!-- Page container -->

<div class="page-container">

    <!-- sidebar -->
    <?php $this->load->view(get_theme_directory() . 'left_sidebar'); ?>
    <!-- /sidebar --> 

    <!-- Page content -->
    <div class="page-content">

        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3>Assign Sites<small>Assign <?php echo $this->config->item('website_name'); ?> sites</small></h3>
            </div>
        </div>
        <!-- /page header -->

        <?php if (isset($errors) && $errors != '') { ?>
            <div class="alert alert-danger fade in block-inner alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $errors; ?></div>
        <?php } ?>
        <?php if (isset($success) && $success != '') { ?>
            <div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($message) && $message != '') { ?>
            <div class="alert alert-info fade in block-inner alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $message; ?></div>
        <?php } ?>

        <!-- Breadcrumbs line -->
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="<?php echo site_url('admin/dashboard'); ?>">Home</a></li>
                <li class="">Users</li>
                <li class="active">Assign Sites</li>
            </ul>
        </div>
        <!-- /breadcrumbs line -->
        <?php
        $attributes = array('class' => 'validate  assign-sites', 'id' => 'assign-sites');
        echo form_open($this->uri->uri_string(), $attributes)
        ?>
        <div class="post_contents">           
            <div class="row">
                <div class="col-xs-5 col-lg-12">
                    <div class="pull-right">            
                        <div class="form-group">
                            <label for="sel1">Client List:</label>
                            <select name="client_users" class="form-control" id="sel1">
                                <option value="">Select Client</option>
                                <?php
                                foreach ($client_users as $client_user) {
                                    echo '<option value = "' . $client_user->id . '">' . $client_user->username . '</option>';
                                }
                                ?> 
                            </select>
                        </div>
                    </div>                  
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    <select name="from[]" id="undo_redo" class="form-control" size="13" multiple="multiple">
                        <?php
                        foreach ($sites as $site) {
                            echo '<option value = "' . $site->id . '">' . $site->domain_name . '</option>';
                        }
                        ?>          
                    </select>
                </div>

                <div class="col-xs-2">
                    <button type="button" id="undo_redo_undo" class="btn btn-primary btn-block">undo</button>
                    <button type="button" id="undo_redo_rightAll" class="btn btn-info btn-block"><b>>></b></button>
                    <button type="button" id="undo_redo_rightSelected" class="btn btn-info btn-block"><b>></b></button>
                    <button type="button" id="undo_redo_leftSelected" class="btn btn-info btn-block"><b><</b></button>
                    <button type="button" id="undo_redo_leftAll" class="btn btn-info btn-block"><b><<</b></button>
                    <button type="button" id="undo_redo_redo" class="btn btn-warning btn-block">redo</button>
                </div>

                <div class="col-xs-5">
                    <select name="to[]" id="undo_redo_to" class="form-control" size="13" multiple="multiple"></select>
                </div>
                <div class="col-xs-5 col-lg-12">
                    <div class="pull-right">            
                        <div class="form-group">
                            <br/>
                            <input type="submit" class="btn btn-warning" value="Submit" name="assign_stie">
                        </div>        
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <?php $this->load->view(get_theme_directory() . 'footer'); ?>