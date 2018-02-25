<?php require 'parts/top.php'; ?>
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

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>ADD CURRENCY</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>addcurrency" method="POST" class="edit-profile-form cf" id="addcurrency" style="width: 100%;">

                    <input type="hidden" name="default_currency" value="<?php echo $defaultcurr['currencycode']; ?>">
                    <div class="field">
                        <label for="">CURRENCY CODE: <!-- <span style="margin-left: 50%"> eg. USD, GBP, etc... </span> --> </label> <input type="text" name="currency_code" id="currency_code" placeholder="Currency Code" value="">
                    </div>
                    <div class="field">
                        <label for="">PREFIX:</label>
                        <input type="text" name="prefix" id="prefix" placeholder="Prefix" value="">
                    </div>
                    <div class="field">
                        <label for="">SUFFIX:</label>
                        <input type="text" name="suffix" id="suffix" placeholder="Suffix" value="">
                    </div>
                    <div class="field">
                        <label for="">FORMAT:</label>
                        <select name="format" id="format">
                        	<option value="1"> 1234.56 </option>
                        	<option value="2"> 1,234.56 </option>
                        	<option value="3"> 1.234,56</option>
                        	<option value="4"> 1,234 </option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="">BASE CONV. RATE:</label>
                        <input type="text" name="baseconvrate" id="baseconvrate" placeholder="Base Conv. Rate" value="">
                        <div style="padding-top: 5px">Leave blank to update automatically</div>
                    </div>
                  
                     <div class="field">
                       
                    </div>
                    <br>
                    <!-- <hr class="w-100"> -->
                    <div class="field w-100">
                        <input type="submit" name="savecurrency" value="SAVE CURRENCY"  class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1">
                       <!--  <button type="submit"  class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1" id="submit"><span class="white">SAVE CLIENT</span>
                        </button> -->
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

    $("#addcurrency").validate({
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

  // $("#baseconvrate").val(currencyval.rates);

</script>


<?php require 'parts/bottom.php'; ?>