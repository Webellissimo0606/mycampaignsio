<?php defined('BASEPATH') OR exit('No direct script access allowed'); $this->load->view(get_template_directory().'header');
?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-header bg-green">
                        <h3>Sub user list <a href="<?php echo base_url(); ?>addsubuser"><button class="btn btn-sm btn-rounded btn-success pull-right">Add New Subuser</button></a></h3>
                    </div>
                    <div class="panel-content pagination2">
                        <table class="table table-hover table-dynamic ds-list-table">
                            <thead>
                                <tr role="row">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Account Plan</th>
                                    <th>Domains</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($userlist):
                                foreach($userlist as $user): ?>
                                    <tr>
                                        <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td>Demo Plan</td>
                                        <td><?php echo $user['total_domains']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url(); ?>editsubuser/<?php echo $user['id']; ?>" title="Edit Subuser" class="btn btn-sm btn-rounded btn-blue"><i class="icon-pencil"></i></a>
                                            <a href="<?php echo site_url(); ?>deletesubuser/<?php echo $user['id']; ?>" title="Delete Subuser" class="btn btn-sm btn-rounded btn-danger"  onclick="return confirmdelete();"><i class="icon-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                                else:
                                ?>  
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


<?php $this->load->view(get_template_directory().'footer_new'); ?>
<script type="text/javascript">
	function confirmdelete()
	{
		if(confirm('Are you sure you want to delete this user?')){
			return true;
		}else{
			return false;
		}
	}

</script>
