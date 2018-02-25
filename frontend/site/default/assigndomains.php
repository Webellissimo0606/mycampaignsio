<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<?php require 'parts/top.php'; ?>
    <form action="<?php echo base_url(); ?>assigndomain" method="post">
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>Assign Domain To User</h3>
                </div>
             </div>   
                <div class="content-column-inner">
                  <div class="field">
                    <select id="user" name="user" class="form-control" data-search="true">
                      <option value="">Select user to assign domain</option>
                      <?php foreach($subusers as $subuser): ?>
                        <option value="<?php echo $subuser['id']; ?>" <?php if($subuserid == $subuser['id'])echo 'selected="selected"'; ?>><?php echo $subuser['email'];  ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="field" style="margin-top:20px;margin-bottom:20px;">
                    <a style="color:#fff;" href="javascript:void(0);" onclick="checkall()">Check all</a> 
                    <a style="color:#fff;" href="javascript:void(0);" onclick="uncheckall()">Uncheck all</a>
                  </div>

                  <div class="field">
                    <?php foreach($domains as $domain): ?>
                      <?php if(in_array($domain['domain_id'], $subuserdomains)): ?> 
                        <div class="col-md-6 col-sm-6 col-xs-12" >
                          <input type="checkbox" id="domain_<?php echo $domain['domain_id'];?>" name="domain[]" value="<?php echo $domain['domain_id'] ?>" checked="checked"  class="checkbox_select cs1">
                          <label for="domain_<?php echo $domain['domain_id']; ?>"><?php echo $domain['domain_name']; ?></label>
                        </div>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
               

            </div>
      </div>
  </div>


<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>Assign Domain To Group</h3>
                </div>
            </div>    
               <div class="content-column-inner">
                 <div class="field">
                   <select id="group" name="group" class="form-control" data-search="true">
                     <option value="">Select group to assign domain</option>
                     <?php foreach($groups as $group): ?>
                       <option value="<?php echo $group['id']; ?>" <?php if($groupid == $group['id'])echo 'selected="selected"'; ?>><?php echo $group['group_name'];  ?></option>
                     <?php endforeach; ?>
                   </select>  
                 </div>
                 <div class="field" style="margin-top:20px;margin-bottom:20px;">
                    <a style="color:#fff;" href="javascript:void(0);" onclick="checkallgroup()">Check all</a> 
                    <a style="color:#fff;" href="javascript:void(0);" onclick="uncheckallgroup()">Uncheck all</a>
                 </div>
                 <div class="field">
                   <?php foreach($domains as $domain): ?>
                     <?php if(in_array($domain['domain_id'], $groupdomains)): ?> 
                       <div class="col-md-12 col-sm-12 col-xs-12" >
                         <input type="checkbox" id="domain_<?php echo $domain['domain_id'];?>" name="domain[]" value="<?php echo $domain['domain_id'] ?>" checked="checked"  class="checkbox_select cs3">
                         <label for="domain_<?php echo $domain['domain_id']; ?>"><?php echo $domain['domain_name']; ?></label>
                       </div>
                     <?php endif; ?>
                   <?php endforeach; ?>
                 </div>
               </div>                           
            </div>
      </div>
  </div>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>Your domains</h3>
                </div>
             </div>   
              <div class="content-column-inner">
                
                  <?php foreach($domains as $domain): 

                    if($subuserdomains){
                      $checkdomains = $subuserdomains;
                    }else if($groupdomains){
                      $checkdomains = $groupdomains; 
                    }else{
                      $checkdomains = array();
                    }
                  ?>
                  <div class="field">
                  <?php if(!in_array($domain['domain_id'], $checkdomains)): ?> 
                    <input type="checkbox" id="domain_<?php echo $domain['domain_id'];?>" name="domain[]" value="<?php echo $domain['domain_id'] ?>"  class="checkbox_select">
                    <label for="domain_<?php echo $domain['domain_id']; ?>"><?php echo $domain['domain_name']; ?></label>
                  
                  <?php endif; ?>
                  <?php endforeach; ?>
                </div>
                 <hr class="fl dib w-100" style="margin-top:0;">
                <div class="fl dib w-100 cf">
                  <button type="submit" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">Assign Domain</span>
                  </button>
                </div>
              </div>
              
                
            </div>
      </div>
  </div>


</form>


<?php require 'parts/bottom.php'; ?>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('#user').change(function(){
      if($(this).val() != ''){
        window.location.href = '<?php echo base_url(); ?>assigndomain/'+$(this).val();
      } 
    })

    $('#group').change(function(){
      if($(this).val() != ''){
        window.location.href = '<?php echo base_url(); ?>assigndomain/0/'+$(this).val();
      } 
    })
  });
  function checkall()
  {
    $('.cs1').prop('checked',true);
    
  }
  function uncheckall()
  {
    $('.cs1').prop('checked',false);
    
  }

  function checkallgroup()
  {
    
    $('.cs3').prop('checked',true);
  }
  function uncheckallgroup()
  {
    $('.cs3').prop('checked',false);
  }
</script>