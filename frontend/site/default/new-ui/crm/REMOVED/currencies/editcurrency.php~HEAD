<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'parts/top.php';
?>
<head>
	<head>
    <style type="text/css">
        .error{
            color: #ec3939;
            padding: 5px
        }
        input[type=text], select, textarea {
          max-width: 97%;
      }
    </style>
</head>

</head>
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT CURRENCY</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>editcurrency" method="post" class="edit-profile-form cf" id="editcurrency" style="width: 100%;">

                  <input type="hidden" name="default_currency" value="<?php echo $defaultcurr['currencycode']; ?>">
                    <div class="field">
                        <label for="">CURRENCY CODE: </label>
                        <input type="text" name="currency_code"  value="<?php echo $currency['currencycode']; ?>">
                    </div>
                    <div class="field">
                        <label for="">PREFIX:</label>
                        <input type="text" name="prefix"  value="<?php echo $currency['prefix']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Suffix:</label>
                        <input type="text" name="suffix"  value="<?php echo $currency['suffix']; ?>">
                    </div>
                    <div class="field">
                        <label for="">Format:</label>
                        <select name="format" id="format">
                        <?php if ($currency['format'] == 1) {
                           echo"<option value='1' selected> 1234.56 </option>
                                <option value='2'> 1,234.56 </option>
                                <option value='3'> 1.234,56</option>
                                <option value='4'> 1,234 </option>";
                             }
                             if ($currency['format'] == 2) {
                           echo"<option value='1'> 1234.56 </option>
                                <option value='2' selected> 1,234.56 </option>
                                <option value='3'> 1.234,56</option>
                                <option value='4'> 1,234 </option>";
                             }if ($currency['format'] == 3) {
                           echo"<option value='1'> 1234.56 </option>
                                <option value='2'> 1,234.56 </option>
                                <option value='3' selected> 1.234,56</option>
                                <option value='4'> 1,234 </option>";
                             }if ($currency['format'] == 4) {
                           echo"<option value='1'> 1234.56 </option>
                                <option value='2'> 1,234.56 </option>
                                <option value='3'> 1.234,56</option>
                                <option value='4' selected> 1,234 </option>";
                             } ?>
                            
                        </select>
                    </div>
                    <div class="field">
                        <label for="">BASE CONV. RATE::</label>
                        <input type="text" name="baseconvrate"  value="<?php echo $currency['baseconvrate']; ?>">
                    </div>
                    
                    <div class="field">
                    </div>
                    <input type="hidden" name="currencyid" value="<?php echo $currency['id']; ?>">
                    <!-- <hr class="w-100"> -->
                    <div class="field  w-100">
                        <button type="submit" onclick="return validate_edit_user_form();" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">UPDATE CURRENCY</span>
                        </button>
                        <a href="<?php echo site_url(); ?>listcurrency" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    </div>    
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/js/jquery.validate.min.js"></script> 
<script>
   $(document).ready(function () {

    $("#editcurrency").validate({
    rules: {
        currency_code: {
          required: true
        },
         prefix: {
          required: true
        },
         format: {
          required: true
        }
      },
   messages: {
            currency_code: {
                required: "This field is required"
            },
             prefix: {
                required: "This field is required"
            },
             format: {
                required: "This field is required"
            }
        },
    submitHandler: function(form) { 
        form.submit()
    }
});
});

</script>


<?php require 'parts/bottom.php'; ?>