<?php
$crm_assets_path = base_url('assets/crm') . '/';
?>
    </div>
        
    <script src="<?php echo $crm_assets_path; ?>lib/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>js/main.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/plugins/buttons/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/plugins/buttons/js/buttons.html5.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/plugins/buttons/js/buttons.flash.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/plugins/buttons/js/buttons.print.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/plugins/buttons/js/buttons.colVis.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datatables/plugins/buttons/js/buttons.bootstrap.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>js/app-tables-datatables.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>js/app-ui-notifications.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/jquery.niftymodals/dist/jquery.niftymodals.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/moment.js/min/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="<?php echo $crm_assets_path; ?>lib/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src='https://code.highcharts.com/highcharts.js'></script>

    <?php include_once dirname(dirname(__FILE__)) . '/inc/initjs.php';?>

    <script type="text/javascript">
        $(document).ready(function(){
            App.init();
            App.formElements();
        });
    </script>

    <!-- Notifications-->
    <?php if ($this->session->flashdata('login_notification')) {

    	if ($this->session->userdata('admin')) { ?>
    		<script type="text/javascript">
                $.gritter.add({
    				title: 'Vuuv! <?php echo $this->session->userdata('staffname'); ?>',
    				text: '<?php echo $this->session->userdata('admin_notification'); ?>',
    				image: App.conf.assetsPath + '/' +  App.conf.imgPath + '/root_avatar.png',
    				class_name: 'clean img-rounded',
    				time: ''
                });
    		</script>
        <?php } ?>

        <script type="text/javascript">
            $.gritter.add({
                title: '<?php echo lang('hello'); ?> <?php echo $this->session->userdata('staffname'); ?>',
                text: '<?php echo $this->session->flashdata('login_notification'); ?>',
                image: '<?php echo base_url(); ?>uploads/staffavatars/<?php echo $this->session->userdata('staffavatar'); ?>',
                class_name: 'clean img-rounded',
                time: ''
            });
        </script>

        <script type="text/javascript">
            $.gritter.add({
                title: '<?php echo lang('crmwelcome'); ?>',
                text: "<?php echo lang('welcomemessage'); ?>",
                image: '<?php echo base_url(); ?>uploads/staffavatars/<?php echo $this->session->userdata('staffavatar'); ?>',
                time: '',
                class_name: 'img-rounded'
            });
        </script>

        <script>
            var staffname = "<?php echo $message = sprintf(lang('welcome_once_message'), $this->session->userdata('staffname')) ?> ";
            <?php echo 'speak(staffname);'; ?>
            <?php if ($newreminder > 0) {echo 'speak(reminder);';}?>
            <?php if ($openticket > 0) {echo 'speak(oepnticket);';}?>
        </script>

    <?php } ?>

    <script src='<?php echo base_url('frontend/site/default/new-ui/assets/js/campaigns-io-script.js'); ?>'></script>
</body>
</html>