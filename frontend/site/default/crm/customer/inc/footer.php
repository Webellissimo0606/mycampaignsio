</div>
<script src="<?php echo base_url(); ?>assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/app-dashboard.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/app-ui-notifications.js" type="text/javascript"></script> 
<script src="<?php echo base_url(); ?>assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
<script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      	App.dashboard();
      });	
</script>
<!-- Notifications-->
<?php  if ($this->session->flashdata('login_notification')) {
	if ($this->session->userdata('root')) {?>
		<script type="text/javascript">
			  $.gritter.add({
				title: 'Vuuv! <?php echo $this->session->userdata('staffname'); ?>',
				text: '<?php echo $this->session->userdata('admin_notification'); ?>',
				image: App.conf.assetsPath + '/' +  App.conf.imgPath + '/root_avatar.png',
				class_name: 'clean img-rounded',
				time: '',
			  });
		</script>	
<?php } ?>
<script type="text/javascript">
      $.gritter.add({
        title: '<?php echo lang('hello');?> <?php echo $this->session->userdata('staffname'); ?>',
        text: '<?php echo $this->session->flashdata('login_notification'); ?>',
        image: '<?php echo base_url(); ?>uploads/staffavatars/<?php echo $this->session->userdata('staffavatar'); ?>',
        class_name: 'clean img-rounded',
        time: '',
      });
</script>
<script type="text/javascript">
$.gritter.add({
        title: '<?php echo lang('crmwelcome');?>',
        text: "<?php echo lang('welcomemessage');?>",
        image: '<?php echo base_url(); ?>uploads/staffavatars/<?php echo $this->session->userdata('staffavatar'); ?>',
        time: '',
        class_name: 'img-rounded'
      });
</script>
<?php } ?>
<?php include_once(APPPATH . 'views/inc/initjs.php'); ?>
</body>
</html>