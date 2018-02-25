<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_theme_directory().'header');
?>

<!-- Page container -->

<div class="page-container">

<!-- sidebar -->
<?php $this->load->view(get_theme_directory().'left_sidebar'); ?>
<!-- /sidebar --> 

<!-- Page content -->
<div class="page-content">

<!-- Page header -->
<div class="page-header">
  <div class="page-title">
    <h3>User Groups <small><?php echo $this->config->item('website_name');?> user groups</small></h3>
  </div>
</div>
<!-- /page header -->

<?php if(isset($errors) && $errors!='') { ?>
<div class="alert alert-danger fade in block-inner alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo $errors; ?></div>
<?php } ?>
<?php if(isset($success) && $success!='') { ?>
<div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo $success; ?></div>
<?php } ?>

<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('admin/dashboard');?>">Home</a></li>
    <li class="active">User Groups</li>
  </ul>
</div>
<!-- /breadcrumbs line -->

<div class="post_contents"> 
  
  <!-- Striped and bordered datatable -->
  <div class="block">
    <div class="datatable-usergroups">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="dt-head-center">#</th>
            <th>User Group</th>
            <th class="alignCenter">Status</th>
            <th class="alignCenter">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($usergroups)) { foreach($usergroups as $usergroup) { ?>
          <tr>
            <td><?php echo $usergroup->id;?></td>
            <td><?php echo $usergroup->name;?></td>
            <td class="text-center"><?php if($usergroup->status==1) { ?>
              <span class="label label-success">Active</span>
              <?php } else { ?>
              <span class="label label-danger">In Active</span>
              <?php } ?></td>
            <td><div class="table-controls">
                <?php $c_user_group = $this->ci_auth->usergroup_id(); ?>
                <?php if(((isset($usergroup->canEdit) && $usergroup->canEdit==1) || (isset($usergroup->canDelete) && $usergroup->canDelete==1)) && $usergroup->id!=$c_user_group ) { ?>
                <?php if(isset($usergroup->canEdit) && $usergroup->canEdit==1) { ?>
                <a title="" class="btn btn-info btn-icon btn-xs tip" href="<?php echo site_url('admin/usergroups/editusergroup/'.$usergroup->id);?>" data-original-title="Print"><i class="icon-pencil"></i></a>
                <?php } if(isset($usergroup->canDelete) && $usergroup->canDelete==1) { ?>
                <a title="" id="confirm-delete" class="btn btn-danger btn-icon btn-xs tip" href="#" data-href="<?php echo site_url('admin/usergroups/deleteusergroup/'.$usergroup->id);?>" data-toggle="modal" data-target="#confirmDelete" data-original-title="Export"><i class="icon-remove2"></i></a>
                <?php } ?>
                <?php } else { ?>
                --
                <?php } ?>
              </div></td>
          </tr>
          <?php } } else { ?>
          <tr>
            <td colspan="4" align="center">User groups is empty</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /striped and bordered datatable --> 
  
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete User Group</h4>
      </div>
      <div class="modal-body"><div class="modalbodycont"><p>Delete user groups will also delete the users on the group.</p><p>Do you want to continue?</p></div></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <a class="btn btn-danger btn-ok">Yes</a>
      </div>
    </div>
  </div>
</div>
<script>
	$('#confirmDelete').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
</script>
    
<?php $this->load->view(get_theme_directory().'footer'); ?>
