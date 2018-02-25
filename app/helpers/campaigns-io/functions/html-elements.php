<?php
if( ! function_exists('checkbox_component') ){
	function checkbox_component($name='', $checked=false){ 
	    $id = '' !== $name ? 'id-' . $name : 'tmp-id-' . substr(uniqid(), -4);
	    ?>
	    <div class="optio-check-component">
	        <input id="<?php echo $id; ?>" class="optio-check" type="checkbox" name="<?php echo $name; ?>" <?php echo $checked ? 'checked' : ''; ?> value="1"/>
	        <label for="<?php echo $id; ?>" class="optio-check-btn"></label>
	    </div> <?php
	}
}