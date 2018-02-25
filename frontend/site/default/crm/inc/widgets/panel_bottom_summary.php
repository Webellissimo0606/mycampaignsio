<div class="ciuis-an-x">
	<div class="ciuis-panel-summary-bg col-md-3 col-xs-6 text-center blr-5 tlr-5 md-pt-15 md-mr-10">
		<span>
			<?php echo lang('outstandinginvoices'); ?>
		</span>
		<h1 class="txt-scale-xs no-margin-top text-danger xs-28px figures"><span class="money-area"><?php echo $oft; ?></span></h1>
		<p class="secondary-text">
			<span class="label label-danger"><?php echo $tef; ?></span> <?php echo lang('xinvoicenotpaid'); ?>
		</p>
		<div class="col-md-12">
			<div>
				<div class="progress">
					<div style="width:<?php echo $ofy ?>%" class="progress-bar progress-bar-danger progress-bar-striped active">%
						<?php echo $ofy ?>
						<?php echo lang('notpaid'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ciuis-panel-summary-bg col-md-3 col-xs-6 text-center brr-5 trr-5 md-pt-15 md-mr-10">
		<?php echo lang('monthlyturnover'); ?>
		<h1 class="txt-scale-xs no-margin-top xs-28px figures"> 
				<span class="money-area"><?php echo $akt; ?></span></h1>
		<p class="secondary-text">
			<?php echo lang('lastmonth'); ?><br>
			<strong><span class="money-area"><?php echo $oak; ?></span></strong>
			<span class="text-<?php if ($akt>$oak){ echo 'success';}else{ echo 'danger';} ?>">
				<?php if ($akt>$oak){ echo '<i class="icon ion-arrow-up-c"></i>';}else{ echo '<i class="icon ion-arrow-down-c"></i>';} ?> (
				<?php  $oao = $akt - $oak; if(empty($oak)) {echo ''.lang('notyet').'';} else echo floor($oao / $oak * 100); ?>% )</span>
		</p>
		<br>
	</div>
	<div class="ciuis-panel-summary-bg col-md-3 col-xs-6 text-center rad-5 md-pt-15 pull-right">
		<?php echo lang('monthlyexpense'); ?>:
		<h1 class="txt-scale-xs no-margin-top xs-28px figures"><span class="money-area"><?php echo $mex; ?></span></h1>
		<p class="secondary-text">
			<?php echo lang('lastmonth'); ?><br>
			<strong><span class="money-area"><?php echo $pme; ?></span></strong>
			<span class="text-<?php if ($mex>$pme){ echo 'warning';}else{ echo 'danger';} ?>">
				<?php if ($mex>$pme){ echo '<i class="icon ion-arrow-up-c"></i>';}else{ echo '<i class="icon ion-arrow-down-c"></i>';} ?> (
				<?php  $exp = $mex - $pme;  if(empty($pme)) {echo ''.lang('notyet').'';} else echo floor($exp / $pme * 100); ?>% )</span>
		</p>
		<br>
	</div>
	<div class="ciuis-panel-summary-bg col-md-3 col-xs-6 text-center rad-5 md-pt-15">
		<?php echo lang('annualturnover'); ?>
		<h1 class="txt-scale-xs no-margin-top xs-28px figures"><span class="money-area"><?php echo $ycr; ?></span></h1>
		<p class="secondary-text">
			<?php echo lang('lastyear'); ?> <br>
			<strong><span class="money-area"><?php echo $oyc; ?></span></strong>
			<span class="text-<?php if ($ycr>$oyc){ echo 'success';}else{ echo 'danger';} ?>">
				<?php if ($ycr>$oyc){ echo '<i class="icon ion-arrow-up-c"></i>';}else{ echo '<i class="icon ion-arrow-down-c"></i>';} ?> (
				<?php  $mna = $ycr - $oyc;  if(empty($oyc)) {echo ''.lang('notyet').'';} else echo floor($mna / $oyc * 100); ?>% )</span>
		</p>
		<br>
	</div>
</div>