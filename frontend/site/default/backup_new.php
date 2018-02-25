<?php
$this->load->view(get_template_directory() . 'header');
?>
<div class="row"> 
    <!-- Content -->
    <div class="panel">
		<div class="panel-content">
			<iframe src="https://campaigns.io/my-backups" style="border:none;min-height: 900px" width="100%" height="100%" scrolling="yes"></iframe>
		</div>
	</div>
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'footer_new');
?>							
					