<?php
  $crm_assets_path = base_url('assets/crm') . '/';
?>
</div>
<script src="<?php echo $crm_assets_path; ?>lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>js/main.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>js/app-ui-notifications.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/jquery.nestable/jquery.nestable.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/moment.js/min/moment.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/bootstrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
<?php include_once dirname(dirname(__FILE__)) . '/inc/initjs.php';?>
<script type="text/javascript">
      $(document).ready(function(){
      	App.init();
      	App.formElements();
      });
</script>
</body>
</html>