<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php';?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="panel borderten">
			<div class="panel-body">
				<div class="user-info-list panel panel-default">
					<div class="panel-heading panel-heading-divider md-mr-0 md-ml-0">
						<b>
							<?php echo $lead['leadname']; ?>
						</b>
						<div class="pull-right text-right">
							<div class="btn-group btn-space">
								<button data-toggle="modal" data-target="#remove" class="btn btn-default modal-close remove-lead-button">
									<?php echo lang('delete') ?>
								</button>
								<button data-toggle="modal" data-target="#update-lead" type="submit" class="btn btn-default modal-close lead-edit-button">
									<?php echo lang('edit') ?>
								</button>
								<button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">
									<?php echo lang('action') ?> <span class="icon-dropdown mdi mdi-chevron-down"></span>
								</button>
								<ul role="menu" class="dropdown-menu">
								<li <?php if ($lead['lost'] == 1) {echo 'style="display: none;"';}?> data-markname="<?php echo lang('lost') ?>" data-leadid="<?php echo $lead['id'] ?>" class="mark-as-lost-lead"><a class="mark-as-lost-lead " href="#"><?php echo lang('markleadaslost') ?></a> </li>
								<li <?php if ($lead['lost'] == 0) {echo 'style="display: none;"';}?> data-markname="<?php echo lang('lost') ?>" data-leadid="<?php echo $lead['id'] ?>" class="unmark-as-lost-lead"><a class="unmark-as-lost-lead " href="#"><?php echo lang('unmarkleadaslost') ?></a> </li>
								<li  class="divider"> <a href="#"></a> </li>
								<li <?php if ($lead['junk'] == 1) {echo 'style="display: none;"';}?> data-markname="<?php echo lang('junk') ?>" data-leadid="<?php echo $lead['id'] ?>" class="mark-as-junk-lead"><a class="mark-as-junk-lead" href="#"><?php echo lang('markleadasjunk') ?></a> </li>
								<li <?php if ($lead['junk'] == 0) {echo 'style="display: none;"';}?> data-markname="<?php echo lang('junk') ?>" data-leadid="<?php echo $lead['id'] ?>" class="unmark-as-junk-lead"><a class="unmark-as-junk-lead " href="#"><?php echo lang('unmarkleadasjunk') ?></a> </li>
								</ul>
							</div>
							<?php if ($lead['converted_date'] != NULL) {echo '<button disabled class="btn btn-success modal-close pull-right"><i class="ion-refresh"> </i>' . lang('converted') . '</button>';} else {
	echo '<button  data-target="#convert" data-toggle="modal" class="btn btn-success modal-close pull-right convert-customer-button"><i class="ion-refresh"> </i>' . lang('convertcustomer') . '</button>';
}
?>
						</div>
						<span class="panel-subtitle"><b><?php echo $lead['leadmail']; ?></b></span>
					</div>
					<div class="panel-body row form-horizontal">
						<div class="tab-container">
							<ul class="nav nav-tabs nav-tabs-warning">
								<li class="active"><a href="#general" data-toggle="tab"><strong><?php echo lang('informations') ?></strong></a></li>
								<li><a href="#proposals" data-toggle="tab"><strong><?php echo lang('proposals') ?></strong></a></li>
								<li><a href="#notes" data-toggle="tab"><strong><?php echo lang('notes') ?></strong></a></li>
								<li><a href="#reminders" data-toggle="tab"><strong><i class="ion-ios-bell"></i> <?php echo lang('reminders') ?></strong></a></li>
								<span <?php if ($lead['lost'] == 0) {echo 'style="display:none;"';} else {
	echo '';
}
?> class="label btn-space mark-lost label-danger pull-right md-mt-5"><?php echo lang('lost'); ?></span>
								<span <?php if ($lead['junk'] == 0) {echo 'style="display:none;"';} else {
	echo '';
}
?> class="label btn-space mark-junk label-danger pull-right md-mt-5"><?php echo lang('junk'); ?></span>
							</ul>
							<div class="tab-content md-pr-0 md-pl-0">
								<div id="general" class="tab-pane active cont">
									<div class="form-group col-sm-12 md-pt-0">
										<div class="col-sm-6 lead-line-b lead-line-r">
											<label class="control-label col-sm-5"><strong><?php echo lang('title') ?> :</strong> </label>
											<p class="form-control-static">
												<?php echo $lead['title']; ?>
											</p>
										</div>
										<div class="col-sm-6 lead-line-b">
											<label class="control-label col-sm-5"><strong><?php echo lang('company') ?>:</strong></label>
											<p class="form-control-static text-right">
												<?php echo $lead['company']; ?>
											</p>
										</div>
									</div>
									<div class="form-group col-sm-12 md-pt-0">
										<div class="col-sm-6 lead-line-b lead-line-r">
											<label class="control-label col-sm-5"><strong>Leads Status:</strong></label>
											<div class="pull-left">
												<p class="form-control-static">
													<span class="label label-lg label-default">
														<?php echo $lead['statusname']; ?>
													</span>
												</p>
											</div>
										</div>
										<div class="col-sm-6 lead-line-b">
											<label class="control-label col-sm-5"><strong><?php echo lang('email') ?> : </strong></label>
											<p class="form-control-static text-right">
												<?php echo $lead['leadmail']; ?>
											</p>
										</div>
									</div>
									<div class="form-group col-sm-12 md-pt-0">
										<div class="col-sm-6 lead-line-b lead-line-r">
											<label class="control-label col-sm-5"><strong><?php echo lang('phone') ?> : </strong></label>
											<p class="form-control-static">
												<?php echo $lead['leadphone']; ?> </p>
										</div>
										<div class="col-sm-6 lead-line-b">
											<label class="control-label col-sm-5"><strong><?php echo lang('assignedsmall') ?> :</strong> </label>
											<p class="form-control-static text-right">
												<?php echo $lead['leadassigned']; ?>
											</p>
										</div>
									</div>
									<div class="form-group col-sm-12 md-pt-0">
										<div class="col-sm-6 lead-line-b lead-line-r">
											<label class="control-label col-sm-5"><strong><?php echo lang('zipcode') ?>:</strong></label>
											<p class="form-control-static">
												<?php echo $lead['zip']; ?>
											</p>
										</div>
										<div class="col-sm-6 lead-line-b">
											<label class="control-label col-sm-5"><strong><?php echo lang('city') ?> : </strong></label>
											<p class="form-control-static text-right">
												<?php echo $lead['city']; ?>
											</p>
										</div>
									</div>
									<div class="form-group col-sm-12 md-pt-0">
										<div class="col-sm-6 lead-line-b lead-line-r">
											<label class="control-label col-sm-5"><strong><?php echo lang('state') ?> :</strong></label>
											<p class="form-control-static">
												<?php echo $lead['state']; ?>
											</p>
										</div>
										<div class="col-sm-6 lead-line-b">
											<label class="control-label col-sm-5"><strong><?php echo lang('country') ?> :</strong></label>
											<p class="form-control-static text-right">
												<?php echo $lead['leadcountry']; ?>
											</p>
										</div>
									</div>
									<div class="form-group col-sm-12 md-pt-0">
										<div class="col-sm-6 lead-line-b lead-line-r">
											<label class="control-label col-sm-5"><strong><?php echo lang('website') ?>:</strong></label>
											<p class="form-control-static">
												<?php echo $lead['website']; ?>
											</p>
										</div>
										<div class="col-sm-6 lead-line-b">
											<label class="control-label col-sm-5"><strong>Lead Source :</strong> </label>
											<p class="form-control-static text-right">
												<?php echo $lead['sourcename']; ?>
											</p>
										</div>
									</div>
									<div class="form-group col-sm-12 md-pt-0 ">
										<div class="col-sm-12 lead-line-b">
											<label class="control-label col-sm-5"><strong><?php echo lang('address') ?> :</strong></label>
											<p class="form-control-static text-right">
												<?php echo $lead['address']; ?>
											</p>
										</div>
									</div>
									<div class="col-sm-12 lead-line-b">
										<blockquote style="font-size: 12px;">
											<?php echo $lead['description']; ?>
										</blockquote>
									</div>
								</div>
								<div id="proposals" class="tab-pane">
									<div class="panel panel-default panel-table">
										<div class="panel-body" style="overflow-y: scroll;height: 410px;">
											<table id="table2" class="table table-striped table-hover table-fw-widget" style="margin-top: -20px;">
												<thead>
													<tr>
														<th><?php echo lang('id') ?></th>
														<th><?php echo lang('subject') ?></th>
														<th><?php echo lang('dateofissuance') ?></th>
														<th><?php echo lang('opentill') ?></th>
														<th class="text-right"><?php echo lang('total') ?></th>
													</tr>
												</thead>
												<?php foreach ($proposals as $proposal) {?>
												<tr>
													<td>
														<a class="label label-default" href="<?php echo base_url('proposals/proposal/' . $proposal['id'] . '') ?>"><i class="ion-document"> </i><?php echo lang('proposalprefix'), '-', str_pad($proposal['id'], 6, '0', STR_PAD_LEFT); ?></a>
													</td>
													<td>
														<?php echo $proposal['subject']; ?>
													</td>
													<td>
														<?php echo _adate($proposal['date']); ?>
													</td>
													<td>
														<?php echo _adate($proposal['opentill']); ?>
													</td>
													<td class="text-right">
													<span class="money-area"><?php echo $proposal['total'] ?></span>
													</td>
												</tr>
												<?php }?>
											</table>
										</div>
									</div>
								</div>
								<div id="notes" class="tab-pane cont">
									<?php foreach ($notes as $note) {?>
									<div style="padding: 20px;border: 2px dashed #b7d4cd;border-radius: 10px;margin-bottom: 10px" class="ticket-data">
										<a href="<?php echo base_url('leads/removenote/' . $note['id'] . ''); ?>" style="cursor: pointer;" class="mdi mdi-close pull-right" data-noteid="<?php echo $note['id'] ?>"></a>
										<p>
											<?php echo $note['description'] ?>
										</p>
										<code class="pull-left">Added by <a href="<?php echo base_url('staff/staffmember/' . $note['addedfrom'] . ''); ?>"><?php echo $note['notestaff'] ?></a></code>
										<code class="pull-left">Date Added <span class="text-muted"><?php echo _adate($note['dateadded']) ?></span></code>
										<br>
									</div>
									<?php }?>
									<hr>
									<?php echo form_open_multipart('leads/addnote', array("class" => "form-horizontal col-md-12")); ?>
									<div class="form-group">
										<textarea name="description" class="form-control"><?php $this->input->post('description')?></textarea>
										<input hidden="" type="text" name="leadid" value="<?php echo $lead['id']; ?>">
									</div>
									<div class="form-group pull-right">
										<button type="button" class="btn btn-default btn-space"><i class="icon s7-mail"></i> <?php echo lang('cancel') ?></button>
										<button type="submit" class="btn btn-default btn-space"><i class="icon s7-close"></i> <?php echo lang('add') ?></button>
									</div>
									<?php echo form_close(); ?>
								</div>
								<div id="reminders" class="tab-pane cont" style="margin-top: -20px;">
									<div class="panel panel-default panel-table">
										<div class="panel-body">
											<div class="table-responsive noSwipe">
												<table class="table table-striped table-hover reminder-table">
													<thead>
														<tr>
															<th style="width:17%;">
																<?php echo lang('description') ?>
															</th>
															<th style="width:20%;">
																<?php echo lang('remind') ?>
															</th>
															<th style="width:10%;">
																<?php echo lang('notified') ?>
															</th>
															<th style="width:10%;">
																<?php echo lang('date') ?>
															</th>
															<th style="width:10%;">
																<button type="button" data-toggle="dropdown" class="add-reminder btn btn-default dropdown-toggle ion-android-alarm-clock">
																	<?php echo lang('addreminder') ?>
																</button>
															</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($reminders as $reminder) {?>
														<tr class="reminder-<?php echo $reminder['id']; ?>">
															<td class="cell-detail">
																<span class="cell-detail-description">
																	<?php echo $reminder['description']; ?>
																</span>
															</td>
															<td class="user-avatar cell-detail user-info"><img src="<?php echo base_url('uploads/staffavatars/' . $reminder['staffpicture'] . '') ?>" alt="Avatar">
																<span>
																	<?php echo $reminder['reminderstaff']; ?>
																</span>
															</td>
															<td class="cell-detail"><span>OK</span>
															</td>
															<td class="cell-detail">
																<span>
																	<?php echo _adate($reminder['date']); ?>
																</span>
															</td>
															<td class="text-right"><button data-reminder="<?php echo $reminder['id']; ?>" type="button" class="btn btn-default ion-android-delete delete-reminder"></button>
															</td>
														</tr>
														<?php }?>
													</tbody>
												</table>
												<div class="reminder-form col-md-12" style="display: none">
													<?php echo form_open_multipart('leads/addreminder', array("class" => "form-horizontal col-md-12")); ?>
													<div class="col-md-12 md-p-0">
														<div class="col-md-6 md-pl-0">
															<div class="form-group md-pl-0 md-pr-10">
																<label for="date">
																	<?php echo lang('datetobenotified'); ?>
																</label>
																<div data-start-view="3" data-date-format="yyyy-mm-dd - HH:ii" data-link-field="dtp_input1" class="input-group date datetimepicker"><span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
																	<input name="date" required size="16" type="text" value="<?php $this->input->post('date')?>" class="form-control ci-event-start" placeholder="<?php echo date(" d.m.Y "); ?>">
																</div>
															</div>
														</div>

														<div class="col-md-6 md-pr-0">
															<div class="form-group  md-pr-0">
																<label for="staff">
																	<?php echo lang('setreminderto'); ?>
																</label>
																<select required name="staff" class="select2">
																	<?php
foreach ($all_staff as $staff) {
	$selected = ($staff['id'] == $this->input->post('staff')) ? ' selected="selected"' : null;
	echo '<option value="' . $staff['id'] . '" ' . $selected . '>' . $staff['staffname'] . '</option>';
}
?>
																</select>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="assignedstaff">
															<?php echo lang('description'); ?>
														</label>
														<textarea name="description" class="form-control"><?php $this->input->post('description')?></textarea>
														<input hidden="" type="text" name="relation" value="<?php echo $lead['id']; ?>">
													</div>
													<div class="form-group pull-right">
														<button type="button" class="btn btn-default btn-space reminder-cancel"><i class="icon s7-mail"></i> <?php echo lang('cancel') ?></button>
														<button type="submit" class="btn btn-default btn-space"><i class="icon s7-close"></i> <?php echo lang('add') ?></button>
													</div>
													<?php echo form_close(); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="convert" tabindex="-1" role="dialog" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<?php echo form_open('leads/convertcustomer/' . $lead['id'] . '', array("class" => "form-vertical")); ?>
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="type" value="<?php echo $lead['type'] ?>">
					<div class="text-center">
						<div class="text-success"><span class="modal-main-icon mdi mdi-info"></span>
						</div>
						<h3>
							<?php echo lang('information'); ?>
						</h3>
						<p>
							<?php echo sprintf(lang('convertcustomerdesc'), $lead['leadname']); ?>
						</p>
						<div class="xs-mt-50">
							<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
								<?php echo lang('cancel'); ?>
							</a>
							<button type="submit" class="btn btn-space btn-default">
								<?php echo lang('convert'); ?>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-footer"></div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<div id="remove" tabindex="-1" role="dialog" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
				</div>
				<div class="modal-body">
					<div class="text-center">
						<div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span>
						</div>
						<h3>
							<?php echo lang('attention'); ?>
						</h3>
						<p>
							<?php echo lang('leadattentiondetail'); ?>
						</p>
						<div class="xs-mt-50">
							<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
								<?php echo lang('cancel'); ?>
							</a>
							<a href="<?php echo base_url('leads/remove/' . $lead['id'] . '') ?>" type="button" class="btn btn-space btn-danger">
								<?php echo lang('delete'); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<div id="update-lead" tabindex="-1" role="dialog" class="modal fade">
		<?php echo form_open('leads/update/' . $lead['id'] . '', array("class" => "form-vertical")); ?>
		<div style="width: 70%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="border: 0; padding: 19px; border-bottom: 1px solid #e4e4e4; background: white;">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title">
						<?php echo lang('updatelead'); ?>
					</h3>
					<span>
						<?php echo lang('updateleaddesc'); ?>
					</span>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div id="" class="form-group company">
								<label class="">
									<?php echo lang('name') ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-information"></i></span>
									<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $lead['leadname']); ?>" class="form-control" id="name"/>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="title">
									<?php echo lang('title'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-information"></i></span>
									<input type="text" name="title" value="<?php echo ($this->input->post('title') ? $this->input->post('title') : $lead['title']); ?>" class="form-control" id="title"/>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div id="" class="form-group company">
								<label for="company">
									<?php echo lang('company'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-briefcase"></i></span>
									<input type="text" name="company" value="<?php echo ($this->input->post('company') ? $this->input->post('company') : $lead['company']); ?>" class="form-control" id="company"/>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="description">
									<?php echo lang('description'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-navicon-round"></i></span>
									<input type="text" name="description" value="<?php echo ($this->input->post('description') ? $this->input->post('description') : $lead['description']); ?>" class="form-control" id="description"/>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="source">
									<?php echo lang('assignedstaff'); ?>
								</label>
								<select required name="assigned" class="select2">
									<option value="<?php echo $lead['assigned']; ?>"><?php echo $lead['leadassigned']; ?></option>
									<?php
foreach ($all_staff as $staff) {
	$selected = ($staff['id'] == $this->input->post('assigned')) ? ' selected="selected"' : null;
	echo '<option value="' . $staff['id'] . '" ' . $selected . '>' . $staff['staffname'] . '</option>';
}
?>
								</select>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="phone">
									<?php echo lang('phone'); ?>
								</label>
								<div class="input-group "><span class="input-group-addon"><i class="mdi mdi-phone"></i></span>
									<input type="text" name="phone" value="<?php echo ($this->input->post('phone') ? $this->input->post('phone') : $lead['leadphone']); ?>" class="form-control" id="phone"/>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 col-lg-4 md-p-0">
							<div class="form-group">
								<div class="col-md-6 md-pr-0">
									<label for="source">
										<?php echo lang('source'); ?>
									</label>
									<select required name="source" class="select2">
										<option value="<?php echo $lead['source']; ?>"><?php echo $lead['sourcename']; ?></option>
										<?php
foreach ($leadssources as $source) {
	$selected = ($source['id'] == $this->input->post('source')) ? ' selected="selected"' : null;
	echo '<option value="' . $source['id'] . '" ' . $selected . '>' . $source['name'] . '</option>';
}
?>
									</select>
								</div>
								<div class="col-md-6">
									<label for="status">
										<?php echo lang('status'); ?>
									</label>
									<select required name="status" class="select2">
										<option value="<?php echo $lead['status']; ?>"><?php echo $lead['statusname']; ?></option>
										<?php
foreach ($leadsstatuses as $status) {
	$selected = ($status['id'] == $this->input->post('status')) ? ' selected="selected"' : null;
	echo '<option value="' . $status['id'] . '" ' . $selected . '>' . $status['name'] . '</option>';
}
?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="email">
									<?php echo lang('email'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon">@</span>
									<input required type="text" name="email" value="<?php echo ($this->input->post('email') ? $this->input->post('email') : $lead['leadmail']); ?>" class="form-control" id="email"/>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label for="website">
									<?php echo lang('website'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
									<input type="text" name="website" value="<?php echo ($this->input->post('website') ? $this->input->post('website') : $lead['website']); ?>" class="form-control" id="website"/>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="address">
									<?php echo lang('address'); ?>
								</label>
								<textarea name="address" class="form-control"><?php echo ($this->input->post('address') ? $this->input->post('address') : $lead['address']); ?></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label for="country">
									<?php echo lang('country'); ?>
								</label>
								<select required name="country" class="select2">
								<option value="<?php echo $lead['country']; ?>"><?php echo $lead['leadcountry']; ?></option>
								<?php
foreach ($countries as $country) {
	$selected = ($country['id'] == $this->input->post('country')) ? ' selected="selected"' : null;
	echo '<option value="' . $country['id'] . '" ' . $selected . '>' . $country['shortname'] . '</option>';
}
?>
							</select>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label for="state">
									<?php echo lang('state'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-location"></i></span>
									<input type="text" name="state" value="<?php echo ($this->input->post('state') ? $this->input->post('state') : $lead['state']); ?>" class="form-control" id="state"/>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label for="city">
									<?php echo lang('city'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-location"></i></span>
									<input type="text" name="city" value="<?php echo ($this->input->post('city') ? $this->input->post('city') : $lead['city']); ?>" class="form-control" id="city"/>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label for="zip">
									<?php echo lang('zip'); ?>
								</label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi ion-pound"></i></span>
									<input type="text" name="zip" value="<?php echo ($this->input->post('zip') ? $this->input->post('zip') : $lead['zip']); ?>" class="form-control" id="zip"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="ciuis-body-checkbox has-success pull-left">
						<input name="public" id="spr" type="checkbox" <?=$lead['public'] == 1 ? 'checked value="1"' : 'value="1"'?>>
						<label for="spr">
							<?php echo lang('public'); ?>
						</label>
					</div>
					<div class="ciuis-body-checkbox has-primary pull-left md-ml-20">
						<input name="type" id="type" type="checkbox" <?=$lead['type'] == 1 ? 'checked value="1"' : 'value="1"'?>>
						<label for="type">
							<?php echo lang('individuallead'); ?>
						</label>
					</div>
					<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
						<?php echo lang('cancel'); ?>
					</button>
					<button type="submit" class="btn btn-default modal-close">
						<?php echo lang('update'); ?>
					</button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php';?>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_post.php';?>
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
		$( ".add-note-button" ).click( function () {
			$.ajax( {
				type: "POST",
				url: base_url + "trivia/addnote",
				data: {
					description: $( ".note-description" ).val(),
					relation: $( ".note-customer-id" ).val(),
					relation_type: 'customer'
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('noteadded') ?>',
						position: 'bottom',
						class_name: 'color success',
					} );
					var noteid = data.insert_id;
					$( '.all-notes' ).append( '<div style="padding: 20px;border: 2px dashed #b7d4cd;border-radius: 10px;margin-bottom: 10px" class="ticket-data note-data" data-id="10"><li data-id="' + noteid + '" class="one-note"><a style="cursor: pointer;" class="mdi mdi-close pull-right delete-note"></a> <p>' + $( '.note-description' ).val() + '</p> <code class="pull-left">Added by <a href="http://localhost:8888/ciuis/staff/staffmember/<?php echo $this->session->userdata('logged_in_staff_id'); ?>"><?php echo $this->session->userdata('staffname '); ?></a></code> <code class="pull-left">Date Added <span class="text-muted"><?php echo date('Y.m.d') ?></span></code><br></li></div>' );
					$( '.note-description' ).val( '' );
				}
			} );
			return false;
		} );

		$( ".delete-note" ).click( function () {
			var base_url = '<?php echo base_url(); ?>';
			var noteid = $( this ).parent().data( 'id' );
			var $div = $( this ).closest( 'div.note-data' );
			$.ajax( {
				type: "POST",
				url: base_url + "trivia/removenote",
				data: {
					notes: noteid
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('notedeleted') ?>',
						position: 'bottom',
						class_name: 'color warning',
					} );
					$div.find( 'li' ).fadeOut( 1000, function () {
						$div.remove();
					} );
				}
			} );
			return false;
		} );
		$( ".delete-reminder" ).click( function () {
		var base_url = '<?php echo base_url(); ?>';
		var reminder = $( this ).data( 'reminder' );
		$.ajax( {
			type: "POST",
			url: base_url + "trivia/removereminder",
			data: {
				reminder: reminder
			},
			dataType: "text",
			cache: false,
			success: function ( data ) {
				$.gritter.add( {
					title: '<b><?php echo lang('notification') ?></b>',
					text: '<?php echo lang('reminderdeleted') ?>',
					position: 'bottom',
					class_name: 'color warning',
				} );
				$( '.reminder-'+reminder+'').remove();
			}
		} );
		return false;
	} );
	</script>
	<script type="text/javascript">
		$( ".add-reminder" ).click( function () {
			$( '.reminder-table' ).hide();
			$( '.reminder-form' ).show();
		} );
		$( ".reminder-cancel" ).click( function () {
			$( '.reminder-form' ).hide();
			$( '.reminder-table' ).show();
		} );
		$( '#chooseFile' ).bind( 'change', function () {
			var filename = $( "#chooseFile" ).val();
			if ( /^\s*$/.test( filename ) ) {
				$( ".file-upload" ).removeClass( 'active' );
				$( "#noFile" ).text( "<?php echo lang('notassignedanystaff') ?>" );
			} else {
				$( ".file-upload" ).addClass( 'active' );
				$( "#noFile" ).text( filename.replace( "C:\\fakepath\\", "" ) );
			}
		} );
		$( '.search-table-external' ).on( 'keyup click', function () {
			$( '#table2' ).DataTable().search(
				$( '.search-table-external' ).val()
			).draw();
		} );
	</script>