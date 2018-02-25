<div id="tab_positions" class="tab-pane fade"> 

	<ul id="myTab6" class="nav nav-tabs country-tab">

		<?php foreach($searchengines as $searchengine) 
		if($searchengine['name'] == 'g_uk'){
			$name = 'Google UK';
		} else if($searchengine['name'] == 'g_us') {
			$name = 'Google';
		}else if($searchengine['name'] == 'g_ca') {
			$name = 'Google Canada';
		}else if($searchengine['name'] == 'g_au') {
			$name = 'Google Australia';
		}
		?>
		<li class="active"><a href="#" onclick="getSerpByEngine('<?php echo $searchengine['id']; ?>')"> <i class="flags <?php echo $searchengine['name']; ?>"></i><span><?php echo $name; ?></span></a></li>

	</ul>
<div id="tab_content_serp">
					</div>
	
</div> 
<script type="text/javascript">
jQuery(document).ready(function($) {
	$.post('/auth/viewserpreport',function(data){
				$('#tab_content_serp').html(data);
			},'html')
});

  function getSerpByEngine(engineid){
          	$.pos('/auth/viewrserpreport',{search_engine_id:engineid},function(data){
          		$('#tab_content_serp').html(data);
          	},'html')
          }
	
</script>