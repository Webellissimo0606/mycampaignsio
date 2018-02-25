<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header');
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel"> 
      <div class="panel-header bg-green">
        <h3>Edit Backlink page</h3>
      </div>
      
      <div class="panel-content">
        <form class="validate" id="editlinkpage" action="<?php echo site_url(); ?>editlinkpage" method="post" onsubmit="return validate();">
          <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Choose domain:</label>

                <select name="link_domain_id" id="link_domain_id" class="form-control" onchange="getBacklinkDomains(this);">
                <option value="">choose domain</option>
                  <?php foreach($domains as $domain): ?>
                  <option value="<?php echo $domain['id']; ?>" 
                    <?php if($domainId == $domain['id'])echo 'selected="selected"'; ?>
                    ><?php echo $domain['domain']; ?></option>
                <?php endforeach; ?>
                </select>
              </div>
            </div>

             <div class="col-md-8 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Client:</label>
                <select class="form-control"   data-search="true" name="backlink_domain_id" id="backlink_domain_id">
                    <option value="">Choose a client</option>
                    <option value="<?php echo $page['backlink_domain_id'] ?>" selected="selected"><?php echo $page['backlink_domain']; ?></option>
                </select>
                  
                
              </div>
            </div>
            <div class="col-md-2 col-xs-12 col-sm-12" style="margin-top:30px;">
                <a href="javascript:void(0);" data-toggle="modal" class="success" style="font-size:20px;text-decoration: none;" data-target="#myModal"><i class="icon-plus" rel="tooltip" data-placement="top" title="Add new client"></i></a>  
            </div>

             <div class="col-md-12 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Domain link page:</label>
                <input type="text" name="link" id="link" class="form-control" placeholder="link page url" value="<?php echo $page['link']; ?>">
              </div>
            </div>

            <div class="col-md-12 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Keyword:</label>
                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword" value="<?php echo $page['keyword'] ?>">
              </div>
            </div>

            <div class="col-md-12 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Date:</label>
                <input type="text" name="date" id="date" class="form-control" placeholder="YYYY-MM-DD" data-date-format="yyyy-mm-dd" value="<?php echo $page['keyword_added_date']; ?>">
              </div>
            </div>

            <div class="col-md-12 col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Position:</label>
                <input type="text" name="position" id="position" class="form-control" placeholder="position" value="<?php echo $page['position'] ?>">
                <input type="hidden" name="domain_id" value="<?php echo $domainId; ?>">
                <input type="hidden" name="page_id" value="<?php echo $pageId; ?>">
              </div>
            </div>

            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <button name="button" type="Submit" class="btn btn-warning" id="button" value="true" style="margin-top:25px;">Edit Backlink</button>
              </div>
            </div>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Client</h4>
      </div>
      <div class="modal-body">
        <form action="javascript:void(0);" name="frm_add_backlink" id="frm_add_backlink" method="post" onsubmit="return validatebacklink();">
          <div class="row">
              <div class="col-sm-12">
                  <div class="form-group">
                      <label>Client</label>
                      <input type="text" name="backlink_domain" class="form-control" placeholder="Add client">
                  </div>
              </div>
              <div class="col-md-6 col-xs-12 col-sm-12">
                <div class="form-group">
                  <button name="button" type="Submit" class="btn btn-warning" id="button" value="true" style="margin-top:25px;">Add Client</button>
                </div>
              </div>
          </div>
        </form>
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
    $('#date').datepicker({dateFormat: 'yy-mm-dd'});

    if($('#link_domain_id').val() != '') {
        $.post('/getbacklinkdomains',{'backlink_domain': $('#link_domain_id').val()},function(data){
          if(data.status){
            var html = '';
            for(var i=0;i<data.payload.length;i++){
              html+='<option value="'+data.payload[i]['id']+'">'+data.payload[i]['domain']+'</option>';  
            }
            
          } else {
            html='<option value=""> No client available</option>';
          }
          $('#backlink_domain_id').append(html);
        },'json');
    }

});
function validatebacklink()
{
  if($('#backlink_domain').val() == ''){
    alert("Please enter a client domain");
    return false;

  } else {
      $.post('/addbacklinkdomain',$('#frm_add_backlink').serialize(),function(data){
        if(data.status) {
            if($('#link_domain_id').val() != '') {
              
              $.post('/getbacklinkdomains',{'backlink_domain': $('#link_domain_id').val()},function(data){
                if(data.status){
                  var html = '';
                  for(var i=0;i<data.payload.length;i++){
                    html+='<option value="'+data.payload[i]['id']+'">'+data.payload[i]['domain']+'</option>';  
                  }
                  
                } else {
                  html='<option value=""> No client available</option>';
                }
                $('#backlink_domain_id').html(html);
              },'json');
            }
        }else{
          alert(data.msg);
        }
        $('#myModal').modal('hide');
      },'json')
  }
}

  function validate()
  {
    if($('#domain_id').val() =='') {
      alert('Please choose a domain');
      return false;
    }
    if($('#backlink_domain_id').val() =='') {
      alert('Please enter backlink domain');
      return false;
    }
    if($('#link').val() == ''){
        alert('Please enter valid domain page link');
        return false;
    } 
    return true;
  }

  function getBacklinkDomains(e){
    $.post('/getbacklinkdomains',{'backlink_domain': $(e).val()},function(data){
      if(data.status){
        var html = '';
        for(var i=0;i<data.payload.length;i++){
          html+='<option value="'+data.payload[i]['id']+'">'+data.payload[i]['domain']+'</option>';  
        }
        
      } else {
        html='<option value=""> No client available</option>';
      }
      $('#backlink_domain_id').html(html);
    },'json');
  }



</script>
