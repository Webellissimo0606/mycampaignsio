<?php defined('BASEPATH') OR exit('No direct script access allowed'); $this->load->view(get_template_directory().'header');
?>
<?php if($this->session->flashdata('success_msg')): ?>
   <div class="alert alert-success">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">&times;</span>
     </button>
   <?php echo $this->session->flashdata('success_msg'); ?></div>
   <?php elseif($this->session->flashdata('error_msg')): ?>
       <div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       <?php echo $this->session->flashdata('error_msg'); ?></div>

<?php endif; ?>   
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-header bg-green">
                    <h3>Backlink urls <a href="<?php echo base_url(); ?>addlinkpage"><button class="btn btn-sm btn-rounded btn-success pull-right">Add Backlink Page</button></a></h3>
                </div>
                <div class="panel-content pagination2">
                    <table class="table table-hover table-dynamic ds-list-table">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Client Domain</th>
                                <th>Backlink URL</th>
                                <th>Keyword</th>
                                <th>Keyword Added Date</th>
                                <th>Position</th>
                                <th>Added Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($link_pages):
                              foreach($link_pages as $domain_pages): ?>
                            <tr>
                                <td><?php echo $domain_pages['domain']; ?></td>
                                <td><?php echo $domain_pages['backlink_domain']; ?></td>
                                <td><?php echo $domain_pages['link']; ?></td>
                                <td><?php echo $domain_pages['keyword']; ?></td>
                                <td><?php echo ($domain_pages['keyword_added_date'])?date('d M Y',strtotime($domain_pages['keyword_added_date'])):''; ?></td>
                                <td><?php echo $domain_pages['position']; ?></td>
                                <td><?php echo date('d M Y H:i',strtotime($domain_pages['created'])); ?></td>
                                <td>
                                    <a href="<?php echo site_url(); ?>deletelinkpage/<?php echo $domain_pages['link_domain_id']; ?>/<?php echo $domain_pages['link_page_id']; ?>" rel="tooltip" data-placement="top" title="Delete page" class="btn btn-sm btn-rounded btn-danger"  onclick="return confirmdelete();"><i class="icon-trash"></i></a>

                                    <a href="<?php echo site_url(); ?>editlinkpage/<?php echo $domain_pages['link_domain_id']; ?>/<?php echo $domain_pages['link_page_id']; ?>" rel="tooltip" data-placement="top" title="Edit page" class="btn btn-sm btn-rounded btn-success"  ><i class="icon-pencil"></i></a>
                                   
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
		if(confirm('Are you sure you want to delete this page?')){
			return true;
		}else{
			return false;
		}
	}
</script>