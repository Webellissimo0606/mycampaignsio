<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

<!-- Page container -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
                <!-- Page header -->
            <div class="panel-header bg-green">
                <h3>Assigned Sites</h3>
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
                <div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $message; ?></div>
            <?php } ?>

            <div class="panel-content pagination2 table-responsive"> 
                <table class="table table-hover table-dynamic ds-list-table">
                    <thead>
                        <tr>
                            <th class="dt-head-center">#</th>
                            <th>Name</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($assigned_sites)) {
                            foreach ($assigned_sites as $site) {
                                ?>
                                <tr>
                                    <td><?php echo $site->id; ?></td>
                                    <td><?php echo $site->domain_name; ?></td>
                                    <td><?php echo '<a href="'.$site->domain_name.'">'.str_replace('http://','',$site->domain_name).'</a>'; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3" align="center">Site is empty</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function deleteConfirm(url)
    {
        if (confirm('Do you want to Delete this Site ?'))
        {
            window.location.href = url;
        }
    }
</script>

<?php $this->load->view(get_template_directory() . 'footer_new'); ?>
