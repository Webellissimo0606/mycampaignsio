<?php 
global $include_filterable_table;
$include_filterable_table = true;

?>
<?php require 'parts/top.php'; ?>
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
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>PROJECT LIST</h3>
                </div>
                <div class="right-pos"><a href="<?php echo base_url(); ?>seoreporting/addproject" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW PROJECT</small></a>
                    <a href="<?php echo base_url(); ?>seoreporting/addjob" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW JOB</small></a>
                </div>
                
            </div>
            <div class="content-column-inner">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc" id="datatable">
                        <thead>
                            <tr>
                                <th data-sortable="false"></th>
                                <th>PROJECT</th>
                                <th>CLIENT NAME</th>
                                <th>CREATED</th>
                                <th data-sortable="false">ACTION</th>
                            </tr>
                        </thead>
                        <tbody> <?php
                            if( is_array($projectlist) && ! empty($projectlist) ){
                                $counter = 0;
                                foreach($projectlist as $project){
                                    $counter++;
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td class="tl"><?php echo $project['project_name']; ?></td>
                                        <td><?php echo $project['client_name']; ?></td>
                                        <td><?php echo date('d M Y H:i',strtotime($project['created'])); ?></td>
                                        <td>
                                            <a href="<?php echo site_url(); ?>seoreporting/<?php echo $project['id']; ?>/editproject" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">EDIT PROJECT</small></a>

                                            <a href="<?php echo site_url(); ?>seoreporting/<?php echo $project['id']; ?>/deleteproject" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_delete_project()"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>
                                        </td>
                                    </tr>
                                     <?php
                                }
                            }
                            else{ ?>
                                <tr>
                                    <td colspan="5">No projects found</td>
                                </tr> <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>

