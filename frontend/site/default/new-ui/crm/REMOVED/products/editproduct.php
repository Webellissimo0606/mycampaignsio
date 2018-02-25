<?php
if( ! isset( $current_page ) ){
    global $current_page;
    $current_page = 'business-products-edit';
}

require FCPATH . '/frontend/site/default/new-ui/parts/top.php';
?>

<head>
	<head>
    <style type="text/css">
        .error{
            color: #ec3939;
            padding: 5px
        }
    </style>
</head>

</head>
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT PRODUCT</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <form action="<?php echo site_url(); ?>editproduct" method="post" class="edit-profile-form cf" id="editproduct" style="width: 100%;">

                    <div class="relative w-100 fl">

                        <div class="field">
                            <label for="">TYPE: </label>
    	                    <select name="type" id="type" value="<?php echo $product['type']; ?>">
    	                    	<?php if ( $product['type'] == 1) {
    	                    		echo"<option value='1' selected> Service </option>
                            	    	 <option value='2'> Product </option>";
    	                        	 }
    	                        	  if ( $product['type'] == 2) {
    		                    		echo"<option value='1'> Service </option>
    	                        	    	 <option value='2' selected> Product </option>";
    	                        	 } 
                            	 ?>
                            	
                            </select>
                        </div>
                        <div class="field">
                            <label for="">CODE:</label>
                            <input type="text" name="code"  value="<?php echo $product['code']; ?>">
                        </div>

                    </div>

                    <div class="relative w-100 fl">
                    
                        <div class="field ">
                            <label for="">DESCRIPTION:</label>
                            ​<textarea name="description" id="description" placeholder="Description" rows="5" cols="170"> <?php echo $product['description']; ?> </textarea>
                        </div> 
                        <div class="field">
                            <label for="">NAME:</label>
                            <input type="text" name="name"  value="<?php echo $product['name']; ?>">
                        </div>

                    </div>
                    
                    <?php /* ?>
                    <div class="field">
                        <label for="">PRICE:</label>
                        <input type="text" name="price"  value="<?php echo $product['price']; ?>">
                    </div>
                    <?php */ ?>
                    
                    <input type="hidden" name="productid" value="<?php echo $product['id']; ?>">
                    
                    <hr class="relative fl w-100" style="margin-top:0;">

                    <div class="relative fl w-100">
                        <button type="submit" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">UPDATE PRODUCT</span>
                        </button>
                        <a href="<?php echo site_url(); ?>listproduct" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    </div>    
                    <br>
<div class="content-row">
    <div class="content-column-main">
        <div class="title">
                <div class="left-pos">
                    <h3> PRICES </h3>
                </div>
        </div>
        <div class="content-column-inner">
            <?php /*foreach ($currencies as $key => $currency):*/ ?>
                <?php foreach ($productprices as $key => $productprice): ?>
                    
                
                <div class="field" style="width: 50%">
                    <label for=""><?php echo $productprice['currencycode'] ?></label>
                    <input type="text" name="currency[<?php echo $productprice['id'] ?>]" placeholder="" value="<?php echo $productprice['monthly'] ?>">  <!-- here $productprice['id'] is actually the currency id -->
                </div>
                <?php endforeach ?>
            <?php/* endforeach*/ ?>
        </div>    
    </div>
</div>  
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/js/jquery.validate.min.js"></script> 
<script>
   $(document).ready(function () {

    $("#editproduct").validate({
    rules: {
        type: {
          required: true
        },
         code: {
          required: true
        },
         name: {
          required: true
        },
         description: {
          required: true
        },
        price: {
          required: true,
          number: true
        }
      },
   messages: {
            type: {
                required: "This field is required"
            },
             code: {
                required: "This field is required"
            },
             name: {
                required: "This field is required"
            },
             description: {
                required: "This field is required"    
            },
             price: {
                required: "This field is required",
                number: "Please enter valid number"
            }
        },
    submitHandler: function(form) { 
        form.submit()
    }
});
});

</script>

<?php require FCPATH . '/frontend/site/default/new-ui/parts/bottom.php'; ?>