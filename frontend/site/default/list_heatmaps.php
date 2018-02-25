<?php defined('BASEPATH') OR exit('No direct script access allowed'); $this->load->view(get_template_directory().'header_new');
?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-header bg-green">
                    <h3>List heatmaps
                     <a href="<?php echo base_url(); ?>heatmaps/add"><button class="btn btn-sm btn-rounded btn-success pull-right">Add heatmaps</button></a>
                        <?php if($firstdomain): ?>
                         <a href="<?php echo base_url(); ?>heatmaps/<?php echo $firstdomain['id']; ?>"><button class="btn btn-sm btn-rounded btn-info pull-right">View heatmaps</button></a>   
                     <?php endif; ?>
                     </h3>

                </div>
                <div class="panel-content pagination2">
                    <table class="table table-hover table-dynamic ds-list-table">
                        <thead>
                            <tr>

                                <th>Page</th>
                                <th>Url</th>
                                <th>Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($heatmaps):
                            foreach($heatmaps as $heat): ?>
                            <tr>
                                <td><?php echo $heat['page_name']; ?></td>
                                <td><?php echo $heat['embed_url']; ?></td>
                                <td><?php echo date('d M Y H:i',strtotime($heat['created'])); ?></td>
                                <td>
                                    <a href="<?php echo site_url(); ?>heatmaps/edit/<?php echo $heat['id']; ?>" title="Edit" class="btn btn-sm btn-rounded btn-blue"><i class="icon-pencil"></i></a>
                                    <a href="<?php echo site_url(); ?>heatmaps/delete/<?php echo $heat['id']; ?>" title="Delete" class="btn btn-sm btn-rounded btn-danger"  onclick="return confirmdelete();"><i class="icon-trash"></i></a>
                                    <a href="<?php echo site_url(); ?>heatmaps/<?php echo $heat['id']; ?>" title="Delete" class="btn btn-sm btn-rounded btn-warning"><i class="icon-eye"></i></a>

                                </td>
                            </tr>
                                <?php endforeach;
                                    else:
                                ?>  
                           	<tr>
                                <td></td>
                                <td>No pages added</td>
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
		if(confirm('Are you sure you want to delete heatmap?')){
			return true;
		}else{
			return false;
		}
	}
</script>