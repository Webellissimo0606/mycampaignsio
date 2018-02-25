<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="row">
                <div class="col-sm-12" style="margin:10px;">
                    <?php if ($this->session->flashdata('type') == 'error'): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('msg'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('type') == 'success'): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>   
                    <?php endif; ?>
                </div>
            </div>
            <div id="keywords_list" class="keywords-style">
                <div class="panel">
                    <form action="/uptime/statusreport" method="post" enctype="multipart/form-data" id="frm_status_report" name="frm_status_report">
                        <span class="timetabs <?php if($days == 1)echo 'active'; ?>" data-day="1">Today</span>
                        <span class="timetabs <?php if($days == 7)echo 'active'; ?>" data-day="7">7 Days</span>
                        <span class="timetabs <?php if($days == 30)echo 'active'; ?>" data-day="30">1 Month</span>
                        <span class="timetabs <?php if($days == 365)echo 'active'; ?>" data-day="365">1 Year</span>
                        <input type="hidden" value="" name="days" id="days_report_status">
                    </form>

                    <div class="panel-header">
                          <h3>Uptime Results</h3>
                    </div>  

                    <div class="tab-content">
                        <div class="panel-content pagination2 table-responsive">               
                            <table class="table table-hover table-dynamic ds-list-table">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Domain</th>
                                        <th>Uptime</th>
                                        <th>Loadtime</th>
                                    </tr> 
                                </thead> 
                                <tbody>
                                    <?php
                                      if($uptime):  
                                     foreach($uptime as $key=>$up): ?>
                                    <tr>
                                        <td><div style="background-color:#00ff00;width:20px;height:20px;border-radius:20px;">&nbsp;</div></td>
                                        <td>200</td>
                                        <td><?php echo $up['module']; ?></td>
                                        <td><?php echo $up['domain_name']; ?></td>
                                        <td><?php echo ceil(($up['total_stats']*100)/$totaltime[$key]['total_stats']);?>%</td>
                                        <td><?php echo ceil($up['avg_load_time']/1000); ?>s</td>
                                    </tr>
                                    <?php endforeach;
                                    else:
                                         ?>
                                     <tr>
                                         <td colspan="6">No data found</td>
                                     </tr>
                                    <?php endif; ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="panel-header">
                          <h3>Downtime Results</h3> 
                    </div>

                    <div class="tab-content">
                        <div class="panel-content pagination2 table-responsive">           
                            <table class="table table-hover table-dynamic ds-list-table">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Domain</th>
                                        <th>Uptime</th>
                                        <th>Loadtime</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      if($downtime):  
                                     foreach($downtime as $key=>$down): ?>
                                    <tr>
                                        <td><div style="background-color:#ff0000;width:20px;height:20px;border-radius:20px;">&nbsp;</div></td>
                                        <td>200></td>
                                        <td><?php echo $down['module']; ?></td>
                                        <td><?php echo $down['domain_name']; ?></td>
                                        <td><?php echo ceil(($down['total_stats']*100)/$totaltime[$key]['total_stats']);?> %</td>
                                        <td><?php echo ceil($down['avg_load_time']/1000);?>s</td>
                                    </tr>
                                    <?php endforeach; 
                                        else:
                                    ?>
                                    <tr>
                                        <td colspan="6">No downtime found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.timetabs').click(function(){
            $('#days_report_status').val($(this).attr('data-day'));
            $('#frm_status_report').submit();
        })
    });

</script>
<!-- /#page-content-wrapper -->

<!-- /#wrapper -->
<?php $this->load->view(get_template_directory() . 'footer_new'); ?>


