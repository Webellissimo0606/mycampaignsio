<ul id="ciuis-liquid-menu" class="ciuis-menu hidden-xs hidden-sm">
	<li class="active"><a href="<?php echo base_url('panel'); ?>" class="huppur"><span><?php echo lang('menu_panel'); ?></span></a></li>
	<?php /* ?>
	<li class="active"><a href="<?php echo base_url('businessoverview'); ?>" class="huppur"><span><?php echo lang('menu_panel'); ?></span></a></li>
	<?php */ ?>
	<li class=""><a href="<?php echo base_url('customers'); ?>" class="huppur"><span><?php echo lang('menu_customers'); ?></span></a></li>
	<li class=""><a href="<?php echo base_url('leads'); ?>" class="huppur"><span><?php echo lang('menu_leads'); ?></span></a></li>
	<?php if (!if_admin) {echo '<li class=""><a href="'.base_url().'accounts" class="huppur"></i><span>'.lang('menu_accounts').'</span></a> </li>';}?>
	<li class=""><a href="<?php echo base_url('invoices'); ?>" class="huppur"><span><?php echo lang('menu_invoices'); ?></span></a></li>
	<li class=""><a href="<?php echo base_url('proposals'); ?>" class="huppur"><span><?php echo lang('menu_proposals'); ?></span></a></li>
	<li class=""><a href="<?php echo base_url('expenses'); ?>" class="huppur"><span><?php echo lang('menu_expenses'); ?></span></a></li>
	<li class=""><a href="<?php echo base_url('tickets'); ?>" class="huppur"><span><?php echo lang('menu_tickets'); ?></span></a></li>
	<?php if (!if_admin) {echo '<li class=""><a href="'.base_url().'staff" class="huppur"></i><span>'.lang('menu_staff').'</span></a> </li>';}?>
	<div class="drop-menu pull-right">
	  <a href="javascript:void(0)"><strong style="font-size: 22px"><i class="ion-arrow-right-c"></i></strong></a>
	  <ul>
		<li class=""><a href="<?php echo base_url('products'); ?>" class="huppur"><span><?php echo lang('menu_products'); ?></span></a></li>
		<li class=""><a href="<?php echo base_url('calendar'); ?>" class="huppur"><span><?php echo lang('menu_calendar'); ?></span></a></li>
		<?php if (!if_admin) {echo '<li class=""><a href="'.base_url().'report" class="huppur"><span>'.lang('menu_reports').'</span></a> </li>';}?>
	  </ul>
</div>
</ul>