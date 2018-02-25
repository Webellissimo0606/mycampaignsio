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
<?php include_once dirname(dirname(__FILE__)) . '/inc/initjs.php';?>
<script type="text/javascript">
      $(document).ready(function(){
      	App.init();
      	App.formElements();
      	App.dataTables();
      });
</script>
<script src='<?php echo base_url('frontend/site/default/new-ui/assets/js/campaigns-io-script.js'); ?>'></script>
</body>
</html>