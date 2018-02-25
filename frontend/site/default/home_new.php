<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view( get_template_directory() . 'header' );
?>
<!--  Site Content -->
<div class="row">

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4">
                <div class="panel bg-blue ds-total-sites">
                    <div class="panel-content">
                        <div class="panel-header"><h3>Total Domains</h3></div>
                        <div class="panel-content"><span class="ds-numbers"><?php echo $total_domains['totalDomain']; ?></span></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel bg-blue ds-up-sites">
                    <div class="panel-content">
                        <div class="panel-header"><h3>Sites Up</h3></div>
                        <div class="panel-content"><span class="ds-numbers"><?php echo $up_domains['totalUpdomains']; ?></span></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel bg-blue ds-down-sites">
                    <div class="panel-content">
                        <div class="panel-header"><h3>Sites Down</h3></div>
                        <div class="panel-content"><span class="ds-numbers"><?php echo $down_domains; ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                
                    <div class="panel-header"><h3><i class="fa fa-globe"></i> <strong>All </strong> Websites</h3></div>

                    <div class="panel-content table-responsive">
                        
                        <table class="table table-hover ds-list-table">
                            <thead>
                                <tr>
                                    <th>Website Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="domainlist">
                            <tr>
                                <td colspan="5" style="text-align: center;">Loading...</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="row" id="pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel bg-orange">
            
            <div class="panel-header"><h3><i class="icon-arrow-down"></i> Downtime report</h3></div>

            <div class="panel-content padding-top-zero">
                <table id="tablesorter" class="table table-bordered ds-plain-table" >
                    <thead>
                        <tr role="row">
                            <th>Domain</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody id="incidentreport">
                        <tr>
                            <td colspan="2" style="text-align: center;">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel bg-blue ds-wp-report">
            
            <div class="panel-header"><h3><i class="fa fa-wordpress"></i> Wordpress Database Issues</h3></div>

            <div class="panel-content">
                <div class="row">
                    <div class="col-md-6">
                        <span class="counts">0</span>
                        <span class="title">Spam Comments</span>
                    </div>
                    <div class="col-md-6">
                        <span class="counts">0</span>
                        <span class="title">Post Revisions</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span class="counts">0</span>
                        <span class="title">DB Performance</span>
                    </div>
                    <div class="col-md-6">
                        <span class="counts">0</span>
                        <span class="title">Optimize all</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel bg-blue ds-wp-updates">
            <div class="panel-header"><h3><i class="fa fa-wordpress"></i> Wordpress Updates</h3></div>
            <div class="panel-content">
                <div class="row">
                    <div class="col-md-4">
                        <span class="ds-wp-icons"><i class="fa fa-wordpress"></i></span>
                        <span class="ds-wp-title">Loading...</span>
                    </div>

                    <div class="col-md-4">
                        <span class="ds-wp-icons"><i class="fa fa-paint-brush"></i></span>
                        <span class="ds-wp-title">Loading...</span>
                    </div>

                    <div class="col-md-4">
                        <span class="ds-wp-icons"><i class="fa fa-plug"></i></span>
                        <span class="ds-wp-title">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    function escapeHtml(unsafe) {
        return unsafe.replace(/&/g, "&amp;")
                     .replace(/</g, "&lt;")
                     .replace(/>/g, "&gt;")
                     .replace(/"/g, "&quot;")
                     .replace(/'/g, "&#039;");
    }

    function openPiwikCode(url){
        var myWindow = window.open(url, "", "width=600,height=400");
    }

    function searchAllDomain() {
        var domain = $('#searchDomain').val();
        if( domain.length > 2 ) {
            getAllDomains( domain );  
        }
        else if( '' === domain) {
            getAllDomains();
        }
    }

    function getAllDomains(domain, page) {
        
        domain = 'undefined' === typeof domain ? null : domain;
        page = ! page ? 1 : page;

        // Getting user domains.      
        $.post( '<?php echo base_url(); ?>auth/home/alldomains', { domain:domain, page:page }, function(data){

            var html = '';

            if( data.status ){

                var i, url, tootltipTitle, tootltipTitleClassname, pagination;

                for(i=0;i<data.domain.length;i++){

                    url = ("/analytics/code/" + data.domain[i].id );
                    
                    html += '<tr>';
                    html += '<td>' + data.domain[i].domain_name + '</td>';
                    html += '<td>';

                    switch( data.domain[i].server_status ){
                        case "UP":
                            tootltipTitle = 'Site is Up';
                            tootltipTitleClassname = 'online';
                            break;
                        case "DOWN":
                            tootltipTitle = 'Site is Down';
                            tootltipTitleClassname = 'busy';
                            break;
                        default:
                            tootltipTitle = 'Status not available';
                            tootltipTitleClassname = 'away';
                    }

                    html += '<i data-toggle="tooltip" data-placement="top" title="' + tootltipTitle + '" class="' + tootltipTitleClassname + '"></i>';


                    html += '<span  data-toggle="tooltip" data-placement="top" title="Site Load Speed" class="btn btn-rounded btn-sm btn-white">'
                    html += parseFloat(data.domain[i].avg_load_time/1000).toFixed(2);
                    html += '</span>';

                    html += '</td>';
                    
                    html += '<td>';
                    html += '<a href="<?php echo base_url(); ?>domains/' + data.domain[i].id + '/wordpress/login" data-toggle="tooltip" data-placement="top" title="Login Wordpress admin" class="btn btn-sm btn-rounded btn-wp" target="_blank"><i class="fa fa-wordpress"></i></a>';

                    // html += '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="View Analytics Script" class="btn btn-sm btn-rounded btn-success" onclick="openPiwikCode(\'/analytics/code/'+data.domain[i].id+'\');"><i class="icon-info"></i></a>';
                    
                    html += '<a href="<?php echo base_url(); ?>domains/' + data.domain[i].id + '" data-toggle="tooltip" data-placement="top" title="View Site Stats" class="btn btn-sm btn-rounded btn-warning"><i class="icon-eye"></i></a>';
                    
                    <?php if(!isset($user_session['parent_id'])): ?>
                    
                        html += '<a href="<?php echo base_url(); ?>domains/' + data.domain[i].id + '/edit" data-toggle="tooltip" data-placement="top" title="Edit Site" class="btn btn-sm btn-rounded btn-blue"><i class="icon-pencil"></i></a>';
                        html += '<a href="<?php echo base_url(); ?>domains/' + data.domain[i].id + '/delete" data-toggle="tooltip" data-placement="top" title="Delete Site" class="btn btn-sm btn-rounded btn-danger"  onclick="return deleteDomain();"><i class="icon-trash"></i></a>';
                    <?php endif; ?>

                    html += '<a href="//' + data.domain[i].domain_name + '" data-toggle="tooltip" data-placement="top" title="Visit Site" class="btn btn-sm btn-rounded btn-default"><i class="icon-share-alt"></i></a>';
                    html += '</td>';
                    html += '</tr>';
                }
                
                pagination = '<div class="col-md-6">';
                pagination += '<div class="dataTables_info" id="DataTables_Table_2_info" role="status" aria-live="polite">Showing ' + ( data.domain[0].offset * 1 + 1 ) + ' to ' + ( data.domain[0].offset * 1 + 7 ) + ' of ' + data.domain[0].total_rows + ' entries</div>';
                pagination += '</div>';
                pagination += '<div class="col-md-6">';
                pagination += '<div class="dataTables_paginate paging_simple_numbers" id="#">';
                pagination += '<ul class="pagination">';

                if(data.domain[0].offset*1>0){
                    pagination+="<li class=\"paginate_button previous\" id=\"#\"><a href=\"javascript:void(0)\" onclick=\"getAllDomains('"+domain+"', '"+(data.domain[0].currentpage*1-1)+"')\"><i class=\"fa fa-angle-left\"></i> Prev</a></li>";
                }

                pagination+="<li class=\"paginate_button next\" id=\"#\"><a href=\"javascript:void(0)\" onclick=\"getAllDomains('" + domain + "', '" + ( data.domain[0].currentpage * 1 + 1 ) + "')\">Next <i class=\"fa fa-angle-right\"></i></a></li>";
                pagination += '</ul>';
                pagination += '</div>';
                pagination += '</div>';

                $('#pagination').html( pagination );  
                $('#domainlist').html( html );
                $('[data-toggle="tooltip"]').tooltip();
            }
            else{
                html = ' <tr><td colspan="4" style="text-align: center;">Loading...</td></tr>';
                $('#domainlist').html( html );
                $('[data-toggle="tooltip"]').tooltip();
            }
        },'json');   
    }

    function deleteDomain(){
        if( confirm( 'Are you sure you want to delete this domain?' ) ){
            return true;
        }
        else {
            return false;
        }
    }

    getAllDomains();

    $.post( '/auth/home/incidentreport', function(data) {
        var i, addclass, tooltipdata, html = '';

        if( 'success' === data.type ){
            addclass = 'odd';
            for( i=0; i < data.payload.length; i++ ){

                tooltipdata = escapeHtml( data.payload[i].error );

                html += '<tr>';
                html += '<td>';
                html += '<span data-toggle="tooltip" data-placement="top" title="' + tooltipdata + '" class="btn btn-sm btn-rounded btn-danger pull-right m-l-10"><i class="icon-info"></i></span>';
                html+= data.payload[i].domain_name;
                // html += '<td>'+data.payload[i].downtime+' </td>';
                html += '</td>';
                html += '<td>' + data.payload[i].totaloutagetime + '</td>';
                html += '</tr>'; 
            }
        }
        else {
            html += '<tr>';
            html += '<td colspan="4" style="text-align: center;">Loading...</td>';
            html += '</tr>';
        }

        $('#incidentreport').html( html );
        $('[data-toggle="tooltip"]').tooltip();
    },'json');
</script>

<?php $this->load->view( get_template_directory() . 'footer_new'); ?>
