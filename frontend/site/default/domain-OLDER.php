<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header'); ?>
 <div class="row">
   <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="panel">
        <div class="panel-header bg-green">
          <h3><i class="icon-globe"></i> Your Domains</h3>
        </div>
        
        <div class="panel-content">
          <div class="table pagination2"> 
            <table class="table table-hover ds-list-table table-dynamic" width="100%" >
              <thead>  
                <tr>  
                  <th>Domain</th>  
                  <th>Action</th>  
                  <th>On Page Analysis</th>  
                  <th>SERPS</th>  
                  <th>Uptime</th> 
                  <th>Malware</th>  
                  <th>Webmaster</th>  
                  <th>Analytics</th>  
                </tr>  
              </thead>  
              <tbody>  
                <?php foreach($domains as $domain): ?>
                <tr>  
                    <td>
                      <a hre="#"><?php echo $domain['domain_name']; ?></a>
                    </td>  

                    <td>
                      <a data-target="<?php echo base_url(); ?>auth/site/viewsite/<?php echo $domain['domain_id'];?>" href="<?php echo base_url(); ?>auth/site/viewsite/<?php echo $domain['domain_id'];?>" rel="tooltip" data-placement="top" title="Login Wordpress admin" class="btn btn-sm btn-rounded btn-wp" target="_blank"><i class="fa fa-wordpress"></i></a>
                      
                      <a href="javascript:void(0);" rel="tooltip" data-placement="top" title="View Analytics Script" class="btn btn-sm btn-rounded btn-success" onclick="openPiwikCode('/analytics/code/<?php echo $domain['domain_id'];?>');"><i class="icon-info"></i></a>

                      <a data-target="<?php echo base_url(); ?>auth/dashboard/<?php echo $domain['domain_id'];?>" href="<?php echo base_url(); ?>auth/dashboard/<?php echo $domain['domain_id'];?>" rel="tooltip" data-placement="top" title="View Site Stats" class="btn btn-sm btn-rounded btn-warning"><i class="icon-eye"></i></a>
                      
                      <a data-target="<?php echo base_url(); ?>auth/edit_project/<?php echo $domain['domain_id'];?>" href="<?php echo base_url(); ?>auth/edit_project/<?php echo $domain['domain_id'];?>" rel="tooltip" data-placement="top" title="Edit Site" class="btn btn-sm btn-rounded btn-blue"><i class="icon-pencil"></i></a>
                      <?php if(!isset($user_session['parent_id'])): ?>
                      
                      <a  data-target="<?php echo base_url(); ?>auth/deletedomain/<?php echo $domain['domain_id'];?>" href="<?php echo base_url(); ?>auth/deletedomain/<?php echo $domain['domain_id'];?>" rel="tooltip" data-placement="top" title="Delete Site" class="btn btn-sm btn-rounded btn-danger"  onclick="return deleteDomain();"><i class="icon-trash"></i></a>
                      <?php endif; ?>

                      <a data-target="<?php echo $domain['domain_name'];?>" href="<?php echo $domain['domain_name'];?>" rel="tooltip" data-placement="top" title="Visit Site" class="btn btn-sm btn-rounded btn-default"><i class="icon-share-alt"></i></a>
                    
                    </td>

                    <td>
                      <?php if($domain['monitorOnPageIssues'] == 1): ?>
                      <span class="btn btn-rounded btn-success btn-sm"><i class="icon-check"></i><small class="hide">1</small></span>
                      <?php else: ?>
                        <span class="btn btn-sm btn-rounded btn-danger"><i class="icon-close"></i><small class="hide">2</small></span>
                      <?php endif; ?>
                    </td>  


                    <td>
                      <?php if($domain['keywordexist'] == true): ?>
                      <span class="btn btn-rounded btn-success btn-sm"><i class="icon-check"></i><small class="hide">1</small></span>
                      <?php else: ?>
                        <span class="btn btn-sm btn-rounded btn-danger"><i class="icon-close"></i><small class="hide">2</small></span>
                      <?php endif; ?>
                    </td>  

                    <td>
                      <?php if($domain['server_status']== 'UP' || $domain['server_status'] == 'DOWN'): ?>
                      <span class="btn btn-rounded btn-success btn-sm"><i class="icon-check"></i><small class="hide">1</small></span>
                      <?php else: ?>
                      <span class="btn btn-sm btn-rounded btn-danger"><i class="icon-close"></i><small class="hide">2</small></span>
                      <?php endif; ?>
                    </td>   


                    <td> <?php if($domain['monitorMalware'] == 1): ?>
                      <span class="btn btn-rounded btn-success btn-sm"><i class="icon-check"></i><small class="hide">1</small></span>
                     <?php else: ?>
                     <span class="btn btn-sm btn-rounded btn-danger"><i class="icon-close"></i><small class="hide">2</small></span>
                     <?php endif; ?> </span>
                    </td>  



                    <td> 
                      <?php if($domain['connectToGoogle'] == 1): ?>
                      <span class="btn btn-rounded btn-success btn-sm"><i class="icon-check"></i><small class="hide">1</small></span>  
                      <?php else: ?>
                        <span class="btn btn-sm btn-rounded btn-danger"><i class="icon-close"></i><small class="hide">2</small></span>  
                      <?php endif; ?>
                    </td>


                    <td>
                      <?php if($domain['ga_account']!=0): ?>
                      <span class="btn btn-rounded btn-success btn-sm"><i class="icon-check"></i><small class="hide">1</small></span>
                      <?php else: ?>
                      <span class="btn btn-sm btn-rounded btn-danger"><i class="icon-close"></i><small class="hide">2</small></span>
                      <?php endif; ?>
                    </td>  
                   
                </tr>  
                
                <?php endforeach; ?>
                
              </tbody>  
           
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
       
<script type="text/javascript">
  function openPiwikCode(url){
    var myWindow = window.open(url, "", "width=600,height=400");
  }
</script>

<script>
  function deleteDomain()
  {
   if(confirm('Are you sure you want to delete this domain?')){
     return true;
   } else {
     return false;
   }
  }
</script>

<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 $this->load->view(get_template_directory() . 'footer_new');
 ?>