<?php 
if( ! isset( $current_page ) ){
    global $current_page;
    $current_page = 'business-clients-edit';
}

require FCPATH . '/frontend/site/default/new-ui/parts/top.php';
?>

    <style type="text/css">
        .error{
            color: #ec3939;
            padding: 5px
        }
    </style>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>ADD CLIENT</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>addclient" method="POST" class="edit-profile-form cf" id="addclient" style="width: 100%;">
                    <div class="field">
                        <label for="">First name: </label> 
                        <input type="text" name="first_name" id="first_name" placeholder="First name" value="">
                        <span class="first_name" value=""></span>
                    </div>
                    <div class="field">
                        <label for="">Last name:</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last name" value="">
                        <span class="last_name"></span>
                    </div>
                    <div class="field">
                        <label for="">Company name:</label>
                        <input type="text" name="company_name" id="company_name" placeholder="Company name" value="">
                        <span class="company_name"></span>
                    </div>
                    <div class="field">
                        <label for="">Phone Number:</label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" value="">
                        <span class="phone_number"></span>
                    </div>
                    <div class="field">
                        <label for="">Email address:</label>
                        <input type="text" name="email" id="email" placeholder="Email address" value="">
                        <span class="email"></span>
                    </div>
                    <div class="field">
                        <label for="">Credit Limit:</label>
                        <input type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="">
                        <span class="credit_limit"></span>
                    </div>
                    <div class="field">
                        <label for="">Terms:</label>
                        <select name="terms">
                            <option value="0">On Delivery</option>
                            <option value="7"> 7 Days</option>
                            <option value="14">14 Days</option>
                            <option value="30">30 Days</option>
                            <option value="60">60 Days</option>
                        </select>
                    </div>

                    <hr class="relative fl w-100" style="margin-top:0;">
                    
                    <div class="relative fl w-100">
                        <input type="submit" name="saveclient" value="SAVE CLIENT"  class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1">
                       <!--  <button type="submit"  class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1" id="submit"><span class="white">SAVE CLIENT</span>
                        </button> -->
                        <a href="<?php echo site_url(); ?>listclient" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
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

    $("#addclient").validate({
    rules: {
        first_name: {
          required: true
        },
         last_name: {
          required: true
        },
         company_name: {
          required: true
        },
         phone_number: {
          required: true,
          number: true
        },
        email: {
          required: true,
          email: true
        },
         credit_limit: {
          required: true,
          number: true
        },


      },
   messages: {
            first_name: {
                required: "This field is required"
            },
             last_name: {
                required: "This field is required"
            },
             company_name: {
                required: "This field is required"
            },
             phone_number: {
                required: "This field is required",
                number: "Please enter valid number"
            },
             email: {
                required: "This field is required",
                email: "Please enter valid email"
            },
            credit_limit: {
                required: "This field is required",
                number: "Please enter valid number"
            },
        },
    submitHandler: function(form) { 
        form.submit()
    }
});
});

</script>


<?php require FCPATH . '/frontend/site/default/new-ui/parts/bottom.php'; ?>