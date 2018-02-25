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
                    <h3>List domains <a href="<?php echo base_url(); ?>addlinkpage"><button class="btn btn-sm btn-rounded btn-info pull-right">Add Backlink Page</button></a> <a href="<?php echo base_url(); ?>addlinkdomain"><button class="btn btn-sm btn-rounded btn-success pull-right">Add Domain</button></a>  </h3>
                </div>
                <div class="panel-content pagination2">
                    <table class="table table-hover table-dynamic ds-list-table">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Owner</th>
                                <th>Google Indexed</th>
                                <th>Bing Indexed</th>
                                <th>Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($link_domains):
                            foreach($link_domains as $domain): ?>
                            <tr>
                                <td><?php echo $domain['domain']; ?></td>
                                <td><?php echo $domain['owner']; ?></td>
                                <td><?php echo ($domain['google'] == 1)?'Yes':'-'; ?></td>
                                <td><?php echo ($domain['bing'] == 1)?'Yes':'-'; ?></td>
                                <td><?php echo date('d M Y H:i',strtotime($domain['created'])); ?></td>
                                <td>
                                    <a href="<?php echo site_url(); ?>deletelinkdomain/<?php echo $domain['id']; ?>" rel="tooltip" data-placement="top" title="Delete Domain" class="btn btn-sm btn-rounded btn-danger"  onclick="return confirmdelete();"><i class="icon-trash"></i></a>
                                    <a href="<?php echo site_url(); ?>editlinkdomain/<?php echo $domain['id']; ?>" rel="tooltip" data-placement="top" title="Edit Domain" class="btn btn-sm btn-rounded btn-success" ><i class="icon-pencil"></i></a>
                                    <a href="<?php echo site_url(); ?>listlinkpage/<?php echo $domain['id']; ?>" rel="tooltip" data-placement="top"  title="List Backlink pages" class="btn btn-sm btn-rounded btn-success"><i class="icon-list"></i></a>
                                    <a href="javascript:void(0);" onclick="getRegistrationinfo('<?php echo $domain['id']; ?>');" rel="tooltip" data-placement="top"  title="Registrar information" class="btn btn-sm btn-rounded btn-info"><i class="fa fa-address-book"></i></a>
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
                           	</tr>
                       	    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="registrarModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Registrar information</h4>
          </div>
          <div class="modal-body">
            
              <div class="row">
                   <div class="col-sm-12"> 
                    <p id="registrar_info">
                        
                    </p>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
<?php $this->load->view(get_template_directory().'footer_new'); ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('[rel="tooltip"]').tooltip();
});
	function confirmdelete()
	{
		if(confirm('Are you sure you want to delete this domain?')){
			return true;
		}else{
			return false;
		}
	}
    function getRegistrationinfo(domainId)
    {
        $.post('/getregistrarinfo',{domainId:domainId},function(data){
            if(data.status) {
                var html = data.payload;
                $('#registrar_info').html(html);
            }   else {
                $('#registrar_info').html('Sorry no information available');
            }         
            $('#registrarModal').modal('show');

        },'json')
    }
</script>