<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
  
  <div class="ciuis-body-content">
    <div class="main-content col-xs-12 col-md-5 col-lg-5">
      <div class="user-display">
        <div class="user-display-bg">
          <iframe
            width="100%"
            height="150"
            frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC9_4BPTE00nQyumr6QDd-QYn16BYVdo9M
              &q=1+Elsmere+Manor" allowfullscreen>
          </iframe>
        </div>
        <div class="user-display-bottom">
          <div class="user-display-avatar">
            <img src="https://ciuis.dev/uploads/images/pm.jpg" alt="Avatar">
          </div>
          <div class="user-display-info">
            <div class="name">
              <h3 class="ng-binding"><?php
                if ($customers['type']==0) {
                    echo $customers['companyname'];
                } else {
                    echo $customers['namesurname'];
                } ?></h3>
            </div>
            <div class="nick">
              <span class="mdi ion-location"></span> 
              <span class="ng-binding"><?php echo $customers['customercity']; ?></span>
            </div>
          </div>
        </div>
        <md-divider></md-divider>
        <md-content class="bg-white _md">
          <md-list class="md-p-0 sm-p-0 lg-p-0" role="list">
            <md-list-item role="listitem" class="md-no-proxy _md">
              <md-icon class="mdi mdi-case material-icons" role="img" aria-hidden="true"></md-icon>
              <p class="ng-binding"><?php echo $customers['companyname']; ?></p>
            <div class="md-secondary-container"></div></md-list-item>
            
            <md-list-item role="listitem" class="md-no-proxy _md">
              <md-icon class="ion-android-call material-icons" role="img" aria-hidden="true"></md-icon>
              <p class="ng-binding"><?php echo $customers['companyphone']; ?></p>
            <div class="md-secondary-container"></div></md-list-item>
            
            <md-list-item role="listitem" class="md-no-proxy _md">
              <md-icon class="mdi ion-location material-icons" role="img" aria-hidden="true"></md-icon>
              <p class="ng-binding"><?php echo $customers['companyaddress']; ?>, <?php echo $customers['customercity']; ?>, <?php echo $customers['customerstate']; ?>, <?php echo $customers['customercountry']; ?></p>
            <div class="md-secondary-container"></div></md-list-item>
            
            <md-list-item role="listitem" class="md-no-proxy _md">
              <md-icon class="mdi ion-android-mail material-icons" role="img" aria-hidden="true"></md-icon>
              <p class="ng-binding"><?php echo $customers['companyemail']; ?></p>
            <div class="md-secondary-container"></div></md-list-item>
          </md-list>
        </md-content>
      </div>

      <div class="main-content container-fluid bg-white">
        <md-toolbar class="toolbar-white _md _md-toolbar-transitions">
          <div class="md-toolbar-tools" style="padding: 0">
            <i class="material-icons">assignment</i>
            <h2 class="md-truncate flex">Private Notes</h2>
          </div>
          </md-toolbar>
          
        <md-content class="md_padding _md">
          <div class="form-group">
            <textarea name="companyaddress" class="form-control"></textarea>
            
          </div>
        </md-content>

        <md-content class="md-mt-10 md-mb-30 _md">
          <button class="btn btn-primary pull-right" type="button" ng-transclude=""><span class="ng-scope">Add Customer Note</span></button>
        </md-content>
      </div>

      

      
      <div class="row ciuis-customers-detail-header md-mt-10">
          <iframe
            width="100%"
            height="150"
            frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC9_4BPTE00nQyumr6QDd-QYn16BYVdo9M
              &q=1+Elsmere+Manor" allowfullscreen>
          </iframe>
        <div class="col-lg-2">
          
        </div>
        <div class="col-lg-6">
            <h3 style="font-weight:500">
                <?php
                if ($customers['type']==0) {
                    echo $customers['companyname'];
                } else {
                    echo $customers['namesurname'];
                } ?>
            </h3>
            <address style="font-weight:500; color:#b5b5b5;" class="invoice-address text-muted">
                <?php echo $customers['companyaddress']; ?>
            </address>
        </div>
        <div class="col-lg-4">
          <button type="button" data-target="#edit" data-toggle="modal" data-placement="left" title="" class="btn btn-default pull-right" data-original-title="<?php echo lang('edit')?>" style="margin-top: 20px;"><i class="icon mdi mdi-edit"> </i> <?php echo lang('editcustomer') ?></button>
        </div>

        <div class="col-lg-12" style="margin-top: 25px">
          <div class="row">
            <div class="col-lg-6">
              <span class="mdi mdi-email"></span> <a href="mailto:<?php echo $customers['companyemail']; ?>"><?php echo $customers['companyemail']; ?></a>
            </div>
            <div class="col-lg-6">
              <span class="mdi mdi-phone"></span> <?php echo $customers['companyphone']; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <span class="mdi mdi-globe-alt"></span> <a href="<?php echo $customers['companyweb']; ?>"><?php echo $customers['companyweb']; ?></a>
            </div>
            <div class="col-lg-6">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="main-content col-xs-12 col-md-7 col-lg-7">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Special title treatment</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="ciuis-body-content">
    <div class="main-content col-xs-12 col-md-12 col-lg-9">
      <div class="row ciuis-customers-detail-header">
        <div class="col-md-4">
          <h3 style="font-weight:500">
            <?php
            if ($customers['type']==0) {
                echo $customers['companyname'];
            } else {
                echo $customers['namesurname'];
            } ?>
        </h3>
        <address style="font-weight:500; color:#b5b5b5;" class="invoice-address text-muted">
            <?php echo $customers['companyaddress']; ?>
        </address>
      </div>
      <div class="col-md-3">
        <div class="reset-ul">
          <address class="ciuis-customer-detail-am">
            <p class="cmdam-title"><b><span class="mdi mdi-email"></span> <?php echo lang('customeremail');?></b>
            </p>
            <span class="cmdam-detail">
                <?php echo $customers['companyemail']; ?>
            </span>
          </address>
          <address class="ciuis-customer-detail-am">
            <p class="cmdam-title"><b><span class="mdi mdi-globe-alt"></span> <?php echo lang('customerweb');?></b>
            </p>
            <span class="cmdam-detail">
                <?php echo $customers['companyweb']; ?>
            </span>
          </address>
        </div>
      </div>
      <div class="col-md-3">
        <div class="reset-ul">
          <address class="ciuis-customer-detail-am">
            <p class="cmdam-title"><b><span class="mdi mdi-phone"></span> <?php echo lang('customerphone');?></b>
            </p>
            <span class="cmdam-detail">
                <?php echo $customers['companyphone']; ?>
            </span>
          </address>
          <address class="ciuis-customer-detail-am">
            <p class="cmdam-title"><b><span class="mdi mdi-dialpad"></span> <?php echo lang('customerfax');?></b>
            </p>
            <span class="cmdam-detail">
                <?php echo $customers['companyfax']; ?>
            </span>
          </address>
        </div>
      </div>
      <div class="col-md-2 text-right">
        <h2 style="font-weight: 600; color: #6e7479; font-size: 28px; padding: 0; margin-bottom: 0;">
            <?php
            $this->db->select_sum('total');
            $this->db->from('invoices');
            $this->db->where('(statusid = 3 AND customerid = '.$customers['id'].') ');
            $mbb = $this->db->get();
            if ($mbb->row()->total>0) {
                echo
                '<strong style="font-size: 20px;"> <span class="money-area">'.$mbb->row()->total.'</strong><br><span style="font-size:10px">'.lang('currentdebt').'</span>';
            } else {
                echo '<strong style="font-size: 35px;"><i class="text-success ion-android-checkmark-circle"></i>
            </strong><br><span class="text-success" style="font-size:10px">'.lang('nobalance').'</span>' ;
            }
            ?>
        </h2>
      </div>
    </div>
    <div style="border-top: 2px solid #d8dfe3;">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#customersummary" data-toggle="tab" aria-expanded="true"><strong><?php echo lang('summary');?></strong></a></li>
      <li class=""><a href="#invoices" data-toggle="tab" aria-expanded="false"><strong><?php echo lang('customerinvoices');?></strong></a>  </li>
      <li class=""><a href="#proposals" data-toggle="tab" aria-expanded="false"><strong><?php echo lang('proposals');?></strong></a>  </li>
      <li class=""><a href="#payments" data-toggle="tab" aria-expanded="false"><strong><?php echo lang('payments')?></strong></a></li>
      <li class=""><a href="#tickets" data-toggle="tab" aria-expanded="false"><strong><?php echo lang('tickets')?></strong></a></li>
      <li><a href="#notes" data-toggle="tab"><strong><i class="ion-document-text"></i> <?php echo lang('notes') ?></strong></a></li>
      <li><a href="#reminders" data-toggle="tab"><strong><i class="ion-ios-bell"></i> <?php echo lang('reminders') ?></strong></a></li>
    </ul>
    </div>
    <div class="col-md-4" style="padding-right: 0;padding-left: 0;padding-top: 0;border-top: 1px solid #d8dfe3;">
      <div id="ciuis-customer-detail-contacts">
        <div id="ciuis-customer-detail-contacts-list">
          <div id="ciuis-customer-contacts-menu">
            <h4>
                <?php echo lang('customercontacts');?>
            </h4>
          </div>
          <div id="ciuis-customer-contact-detail">
            <?php foreach ($contacts as $tem) {
                            ?>
            <div class="ciuis-customer-contacts">
              <div data-toggle="modal" data-target="#contactmodal<?php echo $tem['id']; ?>">
                <img class="ciuis-contact-avatar" width="40" height="40" avatar="<?php echo $tem['name']; ?> <?php echo $tem['surname']; ?>">
                <div style="padding: 16px;position: initial;">
                  <strong>
                    <?php echo $tem['name']; ?>
                    <?php echo $tem['surname']; ?>
                  </strong>
                  <br>
                  <span>
                    <?php echo $tem['email']; ?>
                  </span>
                </div>
                <div class="status 
                <?php if ($tem['primary']==1) {
                    echo 'available';
                } else {
                    echo 'inactive';
                  } ?>">
                              
                            </div>
              </div>
            </div>
            <div id="contactmodal<?php echo $tem['id']; ?>" tabindex="-1" role="dialog" class="modal fade contact-detail-modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
                  </div>
                  <div class="modal-body">
                    <div class="user-info-list panel panel-default">
                      <div class="panel-heading panel-heading-divider">
                        <b>
                                <?php echo $tem['name']; ?>
                                <?php echo $tem['surname']; ?>
                        </b>
                        <h4 style="margin: 0px;" class="pull-right">
                                <?php if ($tem['primary']==1) {
                                    echo '<span class="text-success"><i class="ion-person"></i> <b>'.lang('primarycontact').'</b></span>';
                                  }
                                ?>
                        </h4>
                        <span class="panel-subtitle"><b><?php echo $tem['email']; ?></b></span>
                      </div>
                      <div class="panel-body">
                        <table class="no-border no-strip skills">
                          <tbody class="no-border-x no-border-y">
                            <tr style="border-bottom: 1px solid rgb(239, 239, 239);">
                              <td class="icon"><span class="mdi mdi-case"></span>
                              </td>
                              <td class="item"><?php echo lang('contactposition')?><span class="icon s7-portfolio"></span>
                              </td>
                              <td>
                                <?php echo $tem['position']; ?>
                              </td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgb(239, 239, 239);">
                              <td class="icon"><span class="mdi mdi-phone"></span>
                              </td>
                              <td class="item"><?php echo lang('contactphone')?><span class="icon s7-phone"></span>
                              </td>
                              <td>
                                <?php echo $tem['phone']; ?> -
                                <?php echo $tem['intercom']; ?>
                              </td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgb(239, 239, 239);">
                              <td class="icon"><span class="mdi ion-iphone"></span>
                              </td>
                              <td class="item"><?php echo lang('contactmobile')?><span class="icon s7-phone"></span>
                              </td>
                              <td>
                                <?php echo $tem['mobile']; ?>
                              </td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgb(239, 239, 239);">
                              <td class="icon"><span class="mdi mdi-pin"></span>
                              </td>
                              <td class="item"><?php echo lang('contactaddress')?><span class="icon s7-map-marker"></span>
                              </td>
                              <td>
                                <?php echo $tem['address']; ?>
                              </td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgb(239, 239, 239);">
                              <td class="icon"><span class="mdi mdi-skype"></span>
                              </td>
                              <td class="item"><?php echo lang('contactskype')?><span class="icon s7-gift"></span>
                              </td>
                              <td>
                                <?php echo $tem['skype']; ?>
                              </td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgb(239, 239, 239);">
                              <td class="icon"><span class="mdi mdi-linkedin"></span>
                              </td>
                              <td class="item"><?php echo lang('contactlinkedin')?><span class="icon s7-gift"></span>
                              </td>
                              <td>
                                <?php echo $tem['linkedin']; ?>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                  <button data-toggle="modal" data-target="#changepassword<?=$tem['id']?>" class="btn btn-danger modal-close pull-left contact-change-button"><i class="ion-refresh"> </i><?php echo lang('changepassword')?></button>
                    <div class="btn-group">
                      <button data-toggle="modal" data-target="#mod-danger<?=$tem['id']?>" class="btn btn-default modal-close contact-delete-button"><?php echo lang('delete')?></button>
                      <button data-toggle="modal" data-target="#updatecontact<?=$tem['id']?>" type="submit" class="btn btn-default modal-close contact-edit-button"><?php echo lang('edit')?></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="changepassword<?php echo $tem['id']?>" tabindex="-1" role="dialog" class="modal fade">
            <?php echo form_open('customers/changecontactpassword/'.$tem['id'].''); ?>
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="pull-left">
                    <?php echo lang('changepassword'); ?>
                  </h3>
                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
                </div>
                <hr>
                <div class="modal-body">
                  <div class="col-md-12 nopadding">
                    <div class="form-group">
                      <label for="ad">
                        <b><?php echo lang('password'); ?></b>
                      </label>
                      <p class="xs-mb-5">Customer Area Login Password<a href="javascript:;" data-toggle="popover" data-trigger="hover" title="<?php echo lang('information')?>" data-content="<?php echo lang('contactprimaryhover')?>" data-placement="top"><b> ?</b></a>
                      </p>
                      <div class="input-group ">
                        <input name="contactnewpassword" type="text" class="form-control " rel="gp" data-size="9" id="nc" data-character-set="a-z,A-Z,0-9,#">
                        <span class="input-group-btn"><button type="button" class="btn btn-default getNewPass"><span class="ion-refresh"></span>
                        </button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-default pull-right">
                        <?php echo lang('changepassword'); ?>
                    </button>
                  </div>
                </div>
                <div class="modal-footer"></div>
              </div>
            </div>
            <?php echo form_close(); ?>
          </div>
            <div id="mod-danger<?=$tem['id']?>" tabindex="-1" role="dialog" class="modal fade">
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
                        <?php echo lang('contactattentiondetail'); ?>
                      </p>
                      <div class="xs-mt-50">
                        <a type="button" data-dismiss="modal" class="btn btn-space btn-default">
                            <?php echo lang('cancel'); ?>
                        </a>
                        <a href="<?php echo site_url('contacts/remove/'.$tem['id']); ?>" type="button" class="btn btn-space btn-danger">
                            <?php echo lang('delete'); ?>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer"></div>
                </div>
              </div>
            </div>
            <div id="updatecontact<?php echo $tem['id']?>" tabindex="-1" role="dialog" class="modal fade">
                <?php echo form_open('customers/updatecontact/'.$tem['id'], array("class"=>"form-vertical")); ?>
              <div style="width: 65%;" class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="pull-left">
                        <?php echo lang('newcontacttitle'); ?>
                    </h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
                  </div>
                  <div class="modal-body">
                    <div class="col-md-12 nopadding">
                      <div style="padding: 0px;" class="form-group col-md-4">
                        <label for="ad"><b><?php echo lang('contactname'); ?></b></label>
                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-person"></i></span>
                          <input type="text" name="name" value="<?php echo($this->input->post('name') ? $this->input->post('name') : $tem['name']); ?>" class="form-control ci-contact-name" id="name" placeholder="<?php echo lang('contactname'); ?>"/>
                          <input type="hidden" name="customerid" value="<?php echo $customers['id']; ?>"/>
                        </div>
                      </div>
                      <div style="padding-right: 0px;" class="form-group col-md-4">
                        <label for="surname">
                          <b><?php echo lang('contactsurname'); ?></b>
                        </label>


                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-person-outline"></i></span>
                          <input type="text" name="surname" value="<?php echo($this->input->post('surname') ? $this->input->post('surname') : $tem['surname']); ?>" class="form-control ci-contact-surname" id="surname" placeholder="<?php echo lang('contactsurname'); ?>"/>
                        </div>
                      </div>
                      <div style="padding-right: 0px;" class="form-group col-md-4">
                        <label for="ad">
                          <b><?php echo lang('contactposition'); ?></b>
                        </label>


                        <input type="text" name="position" value="<?php echo($this->input->post('position') ? $this->input->post('position') : $tem['position']); ?>" class="form-control ci-contact-position" id="position" placeholder="<?php echo lang('contactposition'); ?>"/>
                      </div>
                    </div>
                    <div class="col-md-12 nopadding">
                      <div style="padding: 0px;" class="form-group col-md-4">
                        <label for="ad">
                          <b><?php echo lang('contactphone'); ?></b>
                        </label>
                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-telephone"></i></span>
                          <input type="text" name="phone" value="<?php echo($this->input->post('phone') ? $this->input->post('phone') : $tem['phone']); ?>" class="form-control ci-contact-phone" id="phone" placeholder="<?php echo lang('contactphone'); ?>"/>
                        </div>
                      </div>
                      <div style="padding-right: 0px;" class="form-group col-md-4">
                        <label for="ad">
                          <b><?php echo lang('contactintercom'); ?></b>
                        </label>


                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-grid-view-outline"></i></span>
                          <input type="text" name="intercom" value="<?php echo($this->input->post('intercom') ? $this->input->post('intercom') : $tem['intercom']); ?>" class="form-control ci-contact-intercom" id="intercom" placeholder="<?php echo lang('contactintercom'); ?>"/>
                        </div>
                      </div>
                      <div class="form-group col-md-4 md-pr-0 sm-pr-0 lg-pr-0">
                        <label for="ad">
                          <b><?php echo lang('contactmobile'); ?></b>
                        </label>


                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-iphone"></i></span>
                          <input type="text" name="mobile" value="<?php echo($this->input->post('mobile') ? $this->input->post('mobile') : $tem['mobile']); ?>" class="form-control ci-contact-mobile" id="mobile" placeholder="<?php echo lang('contactmobile'); ?>"/>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 nopadding">
                      <div class="form-group col-md-4 md-p-0 sm-p-0 lg-p-0">
                        <label for="ad">
                          <b><?php echo lang('contactemail'); ?></b>
                        </label>


                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-at"></i></span>
                          <input type="text" name="email" value="<?php echo($this->input->post('email') ? $this->input->post('email') : $tem['email']); ?>" class="form-control ci-contact-email" id="email" placeholder="<?php echo lang('contactemail'); ?>"/>
                        </div>
                      </div>
                      <div class="form-group col-md-4 md-pr-0 sm-pr-0 lg-pr-0">
                        <label for="ad">
                          <b><?php echo lang('contactskype'); ?></b>
                        </label>


                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-social-skype"></i></span>
                          <input type="text" name="skype" value="<?php echo($this->input->post('skype') ? $this->input->post('skype') : $tem['skype']); ?>" class="form-control ci-contact-skype" id="skype" placeholder="Skype"/>
                        </div>
                      </div>
                      <div class="form-group col-md-4 md-pr-0 sm-pr-0 lg-pr-0 ">
                        <label for="ad">
                          <b><?php echo lang('contactlinkedin'); ?></b>
                        </label>


                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-social-linkedin"></i></span>
                          <input type="text" name="linkedin" value="<?php echo($this->input->post('linkedin') ? $this->input->post('linkedin') : $tem['linkedin']); ?>" class="form-control ci-contact-linkedin" id="linkedin" placeholder="Linkedin"/>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 nopadding">
                      <div class="form-group">
                        <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-location"></i></span>
                          <textarea name="address" class="form-control ci-contact-address" id="address" placeholder="<?php echo lang('contactaddress'); ?>"><?php echo($this->input->post('address') ? $this->input->post('address') : $tem['address']); ?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-ciuis pull-right ci-update-contact-button">
                        <?php echo lang('update'); ?>
                      </button>
                    </div>
                  </div>
                  <div class="modal-footer"></div>
                </div>
              </div>
                <?php echo form_close(); ?>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="  col-md-8" style="padding: 0;border-top: 1px solid #d8dfe3;">
      <div class="panel-default panel-tab">
        <div class="tab-container">
        <ul class="nav nav-tabs" style="height: 44px">
            <li style="margin-top: 5px;margin-right: 10px;" class="btn-group btn-space pull-right">
              <button type="button" data-target="#contactadd" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="<?php echo lang('addcontact')?>"><i class="icon mdi mdi-account-add"> </i> <?php echo lang('addcontact') ?></button>
              <button type="button" data-target="#edit" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="<?php echo lang('edit')?>"><i class="icon mdi mdi-edit"> </i> <?php echo lang('editcustomer') ?></button>
              <button type="button" data-target="#remove" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="<?php echo lang('delete')?>"><i class="icon mdi mdi-delete"></i></button>
            </li>
          </ul>
          <div class="tab-content ciuis-customers-wrapper">
            <div id="customersummary" class="tab-pane cont active">
              <div style="border-right: 1px solid rgb(234, 234, 234);" class="col-md-4">
                <div class='customer-42525'>
                  <div class='customer-42525__inner'>
                    <h2>
                        <?php echo lang('riskstatus');?>
                    </h2>
                    <small>
                        <?php echo lang('customerrisksubtext');?>
                    </small>
                    <?php
                      $this->db->select('risk');
                      $this->db->from('customers');
                      $this->db->where('(id = '.$customers['id'].') ');
                      $riskstatus = $this->db->get();

                    if ($riskstatus->row()->risk<1) {
                          echo '<div class="stat"><span style="color:#eaeaea;"><i class="text-success mdi mdi-shield-check"></i> '.lang('norisk').'</span></div>' ;
                    } else {
                        if ($riskstatus->row()->risk>50) {
                              echo '<div class="stat"><span>%'.$riskstatus->row()->risk.'</span></div><div class="progress"><div style="width: '.$riskstatus->row()->risk.'%" class="progress-bar progress-bar-danger"></div></div>' ;
                        } else {
                              echo '<div class="stat"><span>%'.$riskstatus->row()->risk.'</span></div><div class="progress"><div style="width: '.$riskstatus->row()->risk.'%" class="progress-bar progress-bar-primary"></div></div>' ;
                        }
                    }
                    ?>
                    <p>
                        <?php echo lang('customerrisksubtext');?>
                    </p>
                  </div>
                </div>
              </div>
              <div style="border-right: 1px solid rgb(234, 234, 234);" class="col-md-4">
                <div class='customer-42525'>
                  <div class='customer-42525__inner'>
                    <h2>
                        <?php echo lang('netsales');?>
                    </h2>
                    <small>
                        <?php echo lang('netsalesdetail');?>
                    </small>
                    <div class='stat'>
                      <span>
                        <?php
                        $this->db->select_sum('total');
                        $this->db->from('invoices');
                        $this->db->where('(statusid = 2 AND customerid = '.$customers['id'].') ');
                        $mbb = $this->db->get();
                        if ($mbb->row()->total>0) {
                            echo '<span class="money-area">'.$mbb->row()->total.'</span>';
                        } else {
                            echo '<span class="text-success" style="font-size:10px">'.lang('nosalesyet').'</span>' ;
                        }
                        ?>

                      </span>
                    </div>
                    <p>
                        <?php echo lang('netsalesdescription');?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class='customer-42525'>
                  <div class='customer-42525__inner'>
                    <h2>
                        <?php echo lang('totalsales');?>
                    </h2>
                    <small>
                        <?php echo lang('totalsalesdetail');?>
                    </small>
                    <div class='stat'>
                      <span>
                        <?php
                        $this->db->select_sum('total');
                        $this->db->from('invoices');
                        $this->db->where('(statusid != 1 AND customerid = '.$customers['id'].') ');
                        $mbb = $this->db->get();
                        if ($mbb->row()->total>0) {
                            echo '<span class="money-area">'.$mbb->row()->total.'</span>';
                        } else {
                            echo '<span class="text-success" style="font-size:10px">'.lang('nosalesyet').'</span>' ;
                        }
                        ?>
                      </span>
                    </div>
                    <p>
                        <?php echo lang('totalsalesdescription');?>
                    </p>
                  </div>
                </div>
              </div>
              <hr style="margin-bottom: 10px;">
              <div ng-controller="mainChartCtrl" class="my-2">
                <div class="chart-wrapper" style="height:210">
                  <canvas id="customerthisyearsalesgraph" height="210px"></canvas>
                </div>
              </div>
            </div>
            <div id="invoices" class="tab-pane">
              <div class="panel panel-default panel-table">
                <div class="panel-body" style="overflow: scroll;height: 410px;">
                  <table id="table2" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th><?php echo lang('id')?></th>
                        <th><?php echo lang('dateofissuance')?></th>
                        <th class="text-right"><?php echo lang('total')?></th>
                      </tr>
                    </thead>
                    <?php foreach ($invoices as $mf) {
                                                    ?>
                    <tr>
                      <td>
                        <a class="label label-default" href="<?php echo base_url('invoices/invoice/'.$mf['id'].'')?>"><i class="ion-document"> </i><?php echo lang('invoiceprefix'),'-',str_pad($mf['id'], 6, '0', STR_PAD_LEFT); ?></a>
                      </td>
                      <td>
                        <?php echo _adate($mf['datecreated']); ?>
                      </td>
                      <td class="text-right">
                      <span class="money-area"><?php echo $mf['total']?></span>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
              </div>
            </div>
            <div id="proposals" class="tab-pane">
              <div class="panel panel-default panel-table">
                <div class="panel-body" style="overflow: scroll;height: 410px;">
                  <table id="table2" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th><?php echo lang('id')?></th>
                        <th><?php echo lang('subject')?></th>
                        <th><?php echo lang('dateofissuance')?></th>
                        <th><?php echo lang('opentill')?></th>
                        <th class="text-right"><?php echo lang('total')?></th>
                      </tr>
                    </thead>
                    <?php foreach ($proposals as $proposal) {
                                                    ?>
                    <tr>

                      <td>
                        <a class="label label-default" href="<?php echo base_url('proposals/proposal/'.$proposal['id'].'')?>"><i class="ion-document"> </i><?php echo lang('proposalprefix'),'-',str_pad($proposal['id'], 6, '0', STR_PAD_LEFT); ?></a>
                      </td>
                      <td><?php echo $proposal['subject']?></td>
                      <td>
                        <?php echo _adate($proposal['date']); ?>
                      </td>
                      <td>
                        <?php echo _adate($proposal['opentill']); ?>
                      </td>
                      <td class="text-right">
                      <span class="money-area"><?php echo $proposal['total']?></span>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
              </div>
            </div>
            <div id="payments" class="tab-pane cont">
              <div class="panel panel-table">
                <div class="panel-body" style="overflow: scroll;height: 410px;">
                  <table id="table2" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th><?php echo lang('id')?></th>
                        <th><?php echo lang('invoice')?></th>
                        <th><?php echo lang('date')?></th>
                        <th class="text-right"><?php echo lang('amount')?></th>
                      </tr>
                    </thead>
                    <?php foreach ($payments as $payment) {
                                                    ?>
                    <tr>
                      <td>
                        <a class="label label-default" href=""><i class="ion-document"> </i><?php echo $payment['id']; ?></a>
                      </td>
                      <td>
                        <b><a class="label label-default" href="<?php echo base_url('invoices/invoice/'.$payment['invoiceid'].'')?>"><?php echo lang('invoiceprefix'),'-',$payment['invoiceid']; ?></a></b>

                      </td>
                      <td>
                        <?php echo _adate($payment['date']); ?>
                      </td>
                      <td class="text-right">
                        <span class="money-area"><?php echo $payment['amount']?></span>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
              </div>
            </div>
            <div id="tickets" class="tab-pane">
              <div class="panel panel-default panel-table">
                <div class="panel-body" style="overflow: scroll;height: 410px;">
                  <table id="table2" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th><?php echo lang('id')?></th>
                        <th><?php echo lang('subject')?></th>
                        <th><?php echo lang('date')?></th>
                        <th class="text-right"><?php echo lang('status')?></th>
                      </tr>
                    </thead>
                    <?php foreach ($tickets as $tickets) {
                                                    ?>
                    <tr>
                      <td>
                        <a class="label label-default" href="<?php echo base_url('tickets/ticket/'.$tickets['id'].'')?>"><i class="ion-document"> </i><?php echo $tickets['id']; ?></a>
                      </td>
                      <td>
                        <?php echo $tickets['subject'] ?>
                      </td>
                      <td><?php echo _adate($tickets['date']); ?></td>
                      <td class="text-right">
                        <?php echo $tickets['statusid'] ?>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
              </div>
            </div>
            <div id="notes" class="tab-pane">
              <div class="panel panel-default panel-table">
                <div class="panel-body notes-all" style="overflow: scroll;height: 410px;padding: 20px;list-style: none">
                  <div class="all-notes">
                    <?php foreach ($notes as $note) {
                                                    ?>
                  <div style="padding: 20px;border: 2px dashed #b7d4cd;border-radius: 10px;margin-bottom: 10px" class="ticket-data note-data" >
                  <li data-id="<?php echo $note['id']?>" class="one-note">
                  <a style="cursor: pointer;" class="mdi mdi-close pull-right delete-note"></a>
                    <p><?php echo $note['description']?></p>
                    <code class="pull-left">Added by <a href="<?php echo base_url('staff/staffmember/'.$note['addedfrom'].''); ?>"><?php echo $note['notestaff'] ?></a></code>
                    <code class="pull-left">Date Added <span class="text-muted"><?php echo _adate($note['dateadded']) ?></span></code>
                    <br>
                  </li>
                  </div>
                    <?php } ?>
                  </div>
                  <hr>
                  <div class="form-group">
                    <textarea required name="description" class="form-control note-description"><?php $this->input->post('description')?></textarea>
                    <input class="note-customer-id" hidden="" type="text" name="customerid" value="<?php echo $customers['id']; ?>">
                  </div>
                  <div class="form-group pull-right">
                    <button type="button" class="btn btn-default btn-space"><i class="icon s7-mail"></i> <?php echo lang('cancel')?></button>
                    <button class="btn btn-default btn-space add-note-button"><i class="icon s7-close"></i> <?php echo lang('add')?></button>
                  </div>
                </div>
              </div>
            </div>
            <div id="reminders" class="tab-pane cont">
              <div class="panel panel-default panel-table">
                <div class="panel-body" style="overflow: scroll;height: 410px;">
                  <table class="table table-striped table-hover reminder-table">
                    <thead>
                      <tr>
                        <th style="width:30%;">
                            <?php echo lang('description') ?>
                        </th>
                        <th style="width:20%;">
                            <?php echo lang('remind') ?>
                        </th>
                        <th style="width:10%;">
                            <?php echo lang('date') ?>
                        </th>
                        <th style="width:10%; text-align: right;">
                          <button type="button" data-toggle="dropdown" class="add-reminder btn btn-default dropdown-toggle ion-android-alarm-clock">
                            <?php echo lang('addreminder') ?>
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reminders as $reminder) {
                                                    ?>
                      <tr class="reminder-<?php echo $reminder['id']; ?>">
                        <td class="cell-detail">
                          <span class="cell-detail-description">
                            <?php echo $reminder['description']; ?>
                          </span>
                        </td>
                        <td class="user-avatar cell-detail user-info"><img src="<?php echo base_url('uploads/staffavatars/'.$reminder['staffpicture'].'')  ?>" alt="Avatar">
                          <span>
                            <?php echo $reminder['reminderstaff']; ?>
                          </span>
                        </td>
                        <td class="cell-detail">
                          <span>
                            <?php echo _adate($reminder['date']); ?>
                          </span>
                        </td>
                        <td class="text-right"><button data-reminder="<?php echo $reminder['id']; ?>" type="button" class="btn btn-default ion-android-delete delete-reminder"></button>
                        </td>
                      </tr>
                        <?php } ?>
                    </tbody>
                  </table>
                  <div class="reminder-form col-md-12" style="display: none">
                    <?php echo form_open_multipart('customers/addreminder', array("class"=>"form-horizontal col-md-12")); ?>
                    <div class="col-md-12 md-p-0">
                      <div class="col-md-6 md-pl-0">
                        <div class="form-group md-pl-0 md-pr-10">
                          <label for="date">
                            <?php echo lang('datetobenotified'); ?>
                          </label>
                          <div data-start-view="3" data-date-format="yyyy-mm-dd - HH:ii" data-link-field="dtp_input1" class="input-group date datetimepicker"><span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
                            <input type="integer" name="date" required size="16"  value="<?php $this->input->post('date')?>" class="form-control ci-event-start" placeholder="<?php echo date(" d.m.Y "); ?>">
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6 md-pr-0">
                        <div class="form-group  md-pr-0">
                          <label for="staff">
                            <?php echo lang('setreminderto');?>
                          </label>
                          <select required name="staff" class="form-control select2">
                            <?php
                            foreach ($all_staff as $staff) {
                                $selected = ($staff[ 'id' ] == $this->input->post('staff')) ? ' selected="selected"' : null;
                                echo '<option value="' . $staff[ 'id' ] . '" ' . $selected . '>' . $staff[ 'staffname' ] . '</option>';
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="assignedstaff">
                        <?php echo lang('description');?>
                      </label>
                      <textarea name="description" class="form-control"><?php $this->input->post('description')?></textarea>
                      <input hidden="" type="text" name="relation" value="<?php echo $customers['id']; ?>">
                    </div>
                    <div class="form-group pull-right">
                      <button type="button" class="btn btn-default btn-space reminder-cancel"><i class="icon s7-mail"></i> <?php echo lang('cancel')?></button>
                      <button type="submit" class="btn btn-default btn-space"><i class="icon s7-close"></i> <?php echo lang('add')?></button>
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
    <div class="col-md-12 panel borderten" style="padding:20px;">
      <div style="margin: 0 0px 8px;" class="panel-heading panel-heading-divider"><span><b><?php echo lang('customeractivities');?></b></span>
        <span class="panel-subtitle">
            <?php echo lang('customeractivitiessubtext');?>
        </span>
      </div>
      <div style="padding:0px;" class="panel-body">
        <ul class="user-timeline">
            <?php foreach ($companylog as $logcustomer) {
                                                            ?>
          <li>
            <div class="user-timeline-title">
                <?php echo tes_ciuis($logcustomer['date']); ?>
            </div>
            <div class="user-timeline-description">
                <?php echo $logcustomer['detail']; ?>
            </div>
          </li>
            <?php  } ?>
        </ul>
      </div>
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
            <?php echo lang('customerattentiondetail'); ?>
          </p>
          <div class="xs-mt-50">
            <a type="button" data-dismiss="modal" class="btn btn-space btn-default">
                <?php echo lang('cancel'); ?>
            </a>
            <a href="<?php echo base_url('customers/remove/'.$customers['id'].'')?>" type="button" class="btn btn-space btn-danger">
                <?php echo lang('delete'); ?>
            </a>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
<div id="contactadd" tabindex="-1" role="dialog" class="modal fade">
    <?php echo form_open('customers/contactadd', array("class"=>"form-vertical")); ?>
  <div style="width: 65%;" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="pull-left">
            <?php echo lang('newcontacttitle');?>
        </h3>
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
      </div>
      <div class="modal-body">
        <div class="col-md-12 nopadding">
          <div style="padding: 0px;" class="form-group col-md-4">
            <label for="name">
              <b><?php echo lang('contactname');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-person"></i></span>
              <input required type="text" name="name" value="<?php echo $this->input->post('name'); ?>" class="form-control" id="name" placeholder="<?php echo lang('contactname');?>"/>
              <input type="hidden" name="customerid" value="<?php echo $customers['id']; ?>"/>
            </div>
          </div>
          <div style="padding-right: 0px;" class="form-group col-md-4">
            <label for="surname">
              <b><?php echo lang('contactsurname');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-person-outline"></i></span>
              <input required type="text" name="surname" value="<?php echo $this->input->post('surname'); ?>" class="form-control" id="surname" placeholder="<?php echo lang('contactsurname');?>"/>
            </div>
          </div>
          <div style="padding-right: 0px;" class="form-group col-md-4">
            <label for="position">
              <b><?php echo lang('contactposition');?></b>
            </label>

            <input type="text" name="position" value="<?php echo $this->input->post('position'); ?>" class="form-control" id="position" placeholder="<?php echo lang('contactposition');?>"/>
          </div>
        </div>
        <div class="col-md-12 nopadding">
          <div style="padding: 0px;" class="form-group col-md-4">
            <label for="ad">
              <b><?php echo lang('contactphone');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-telephone"></i></span>
              <input type="text" name="phone" value="<?php echo $this->input->post('phone'); ?>" class="form-control" id="phone" placeholder="<?php echo lang('contactphone');?>"/>
            </div>
          </div>
          <div style="padding-right: 0px;" class="form-group col-md-4">
            <label for="intercom">
              <b><?php echo lang('contactintercom');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-grid-view-outline"></i></span>
              <input type="text" name="intercom" value="<?php echo $this->input->post('intercom'); ?>" class="form-control" id="intercom" placeholder="<?php echo lang('contactintercom');?>"/>
            </div>
          </div>
          <div class="form-group col-md-4 md-pr-0 sm-pr-0 lg-pr-0">
            <label for="ad">
              <b><?php echo lang('contactmobile');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-iphone"></i></span>
              <input type="text" name="mobile" value="<?php echo $this->input->post('mobile'); ?>" class="form-control" id="mobile" placeholder="<?php echo lang('contactmobile');?>"/>
            </div>
          </div>
        </div>
        <div class="col-md-12 nopadding">
          <div class="form-group col-md-4 md-p-0 sm-p-0 lg-p-0">
            <label for="email">
              <b><?php echo lang('contactemail');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-at"></i></span>
              <input required type="text" name="email" value="<?php echo $this->input->post('email'); ?>" class="form-control" id="email" placeholder="<?php echo lang('contactemail');?>"/>
            </div>
          </div>
          <div class="form-group col-md-4 md-pr-0 sm-pr-0 lg-pr-0">
            <label for="skype">
              <b><?php echo lang('contactskype');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-social-skype"></i></span>
              <input type="text" name="skype" value="<?php echo $this->input->post('skype'); ?>" class="form-control" id="skype" placeholder="Skype"/>
            </div>
          </div>
          <div class="form-group col-md-4 md-pr-0 sm-pr-0 lg-pr-0 ">
            <label for="linkedin">
              <b><?php echo lang('contactlinkedin');?></b>
            </label>

            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-social-linkedin"></i></span>
              <input type="text" name="linkedin" value="<?php echo $this->input->post('linkedin'); ?>" class="form-control" id="linkedin" placeholder="Linkedin"/>
            </div>
          </div>
          <div style="display: none" class="form-group col-md-4 password-input md-pl-0 sm-pl-0 lg-pl-0 md-pr-0 sm-pr-0 lg-pr-0">
            <label for="password">
              <b><?php echo lang('password');?></b>
            </label>
            <p class="xs-mb-5">Customer Area Login Password<a href="javascript:;" data-toggle="popover" data-trigger="hover" title="<?php echo lang('information')?>" data-content="<?php echo lang('contactprimaryhover')?>" data-placement="top"><b> ?</b></a>
            </p>
            <div class="input-group ">
              <input name="password" type="text" class="form-control " rel="gp" data-size="9" id="nc" data-character-set="a-z,A-Z,0-9,#">
              <span class="input-group-btn"><button type="button" class="btn btn-default getNewPass"><span class="ion-refresh"></span>
              </button>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-12 nopadding">
          <div class="form-group">
            <div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-location"></i></span>
              <textarea name="address" class="form-control" id="address" placeholder="<?php echo lang('contactaddress');?>"><?php echo $this->input->post('address'); ?></textarea>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="ciuis-body-checkbox has-success col-md-5">
            <input name="primary" class="primary-check" id="primary" type="checkbox" value="1" onchange="valueChanged()">
            <label for="primary"><?php echo lang('primarycontact') ?></label>
          </div>
          <button type="submit" class="btn btn-ciuis pull-right">
            <?php echo lang('contactaddbuton');?>
          </button>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
    <?php echo form_close(); ?>
</div>
<div id="edit" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-warning">
    <?php echo form_open('customers/customer/'.$customers['id'], array("class"=>"form-vertical")); ?>
  <div style="width: 70%;" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
        <h3 class="modal-title">
            <?php echo lang('updatecustomertitle');?>
        </h3>
        <span>
            <?php echo lang('updatecustomerdescription');?>
        </span>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
                <?php if ($customers['type']==0) {
                    echo'<label for="companyname">'.lang('updatecustomercompanyname').'</label><div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-case"></i></span><input type="text" name="companyname" value="'.($this->input->post('companyname') ? $this->input->post('companyname') : $customers['companyname']).'" class="form-control" id="companyname"/></div>';
                        } else {
                    echo'<label for="namesurname">'.lang('updatecustomerindividualname').'</label><div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-case"></i></span><input type="text" name="namesurname" value="'.($this->input->post('namesurname') ? $this->input->post('namesurname') : $customers['namesurname']).'" class="form-control" id="namesurname"/></div>';
                  }
                ?>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="taxoffice">
                <?php echo lang('taxofficeedit');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-local-atm"></i></span>
                <input type="text" name="taxoffice" value="<?php echo($this->input->post('taxoffice') ? $this->input->post('taxoffice') : $customers['taxoffice']); ?>" class="form-control" id="taxoffice"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
                <?php if ($customers['type']==0) {
                        echo'<label for="taxnumber">'.lang('taxnumberedit').'</label><div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-case"></i></span><input type="number" name="taxnumber" value="'.($this->input->post('taxnumber') ? $this->input->post('taxnumber') : $customers['taxnumber']).'" class="form-control required" id="taxnumber" required/></div>';
                      } else {
                        echo'<label for="socialsecuritynumber">'.lang('socialsecuritynumberedit').'</label><div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-case"></i></span><input type="text" name="socialsecuritynumber" value="'.($this->input->post('socialsecuritynumber') ? $this->input->post('socialsecuritynumber') : $customers['socialsecuritynumber']).'" class="form-control" id="socialsecuritynumber"/></div>';
                      }
                ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="companyexecutive">
                <?php echo lang('companyexecutiveupdate');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-account"></i></span>
                <input type="text" name="companyexecutive" value="<?php echo($this->input->post('companyexecutive') ? $this->input->post('companyexecutive') : $customers['companyexecutive']); ?>" class="form-control" id="companyexecutive"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="zipcode">
                <?php echo lang('zipcode');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon"><i class="ion-paper-airplane"></i></span>
                <input type="text" name="zipcode" value="<?php echo($this->input->post('zipcode') ? $this->input->post('zipcode') : $customers['zipcode']); ?>" class="form-control" id="zipcode"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="companyphone">
                <?php echo lang('customerphoneupdate');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-phone"></i></span>
                <input type="text" name="companyphone" value="<?php echo($this->input->post('companyphone') ? $this->input->post('companyphone') : $customers['companyphone']); ?>" class="form-control" id="companyphone"/>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="companyfax">
                <?php echo lang('customerfaxupdate');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-scanner"></i></span>
                <input type="text" name="companyfax" value="<?php echo($this->input->post('companyfax') ? $this->input->post('companyfax') : $customers['companyfax']); ?>" class="form-control" id="companyfax"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="companyemail">
                <?php echo lang('emailedit');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon">@</span>
                <input type="text" name="companyemail" value="<?php echo($this->input->post('companyemail') ? $this->input->post('companyemail') : $customers['companyemail']); ?>" class="form-control" id="companyemail"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="form-group">
              <label for="companyweb">
                <?php echo lang('customerwebupdate');?>
              </label>
              <div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
                <input type="text" name="companyweb" value="<?php echo($this->input->post('companyweb') ? $this->input->post('companyweb') : $customers['companyweb']); ?>" class="form-control" id="companyweb"/>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="companyemail">
                <?php echo lang('customeraddressupdate');?>
              </label>
              <textarea name="companyaddress" class="form-control"><?php echo($this->input->post('companyaddress') ? $this->input->post('companyaddress') : $customers['companyaddress']); ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="form-group">
              <label for="companyemail">
                <?php echo lang('customercountryupdate');?>
              </label>
              <select required name="countryid" class="form-control select2 required">
                <option value="<?php echo $customers['countryid'];?>"><?php echo $customers['country'];?></option>
                <?php
                foreach ($countries as $country) {
                    $selected = ($country[ 'id' ] == $this->input->post('countryid')) ? ' selected="selected"' : null;
                    echo '<option value="' . $country[ 'id' ] . '" ' . $selected . '>' . $country[ 'shortname' ] . '</option>';
                }
                ?>
              </select>

            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="form-group">
              <label for="customerstate">
                <?php echo lang('customerstateupdate');?>
              </label>
              <div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
                <input type="text" name="customerstate" value="<?php echo($this->input->post('customerstate') ? $this->input->post('customerstate') : $customers['customerstate']); ?>" class="form-control" id="customerstate"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="form-group">
              <label for="customercity">
                <?php echo lang('customercityupdate');?>
              </label>
              <div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
                <input type="text" name="customercity" value="<?php echo($this->input->post('customercity') ? $this->input->post('customercity') : $customers['customercity']); ?>" class="form-control" id="customercity"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="form-group">
              <label for="customertown">
                <?php echo lang('customertownupdate');?>
              </label>
              <div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
                <input type="text" name="customertown" value="<?php echo($this->input->post('customertown') ? $this->input->post('customertown') : $customers['customertown']); ?>" class="form-control" id="customertown"/>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="form-group text-left">
          <div class="col-sm-3">
            <label for="risk">
                <?php echo lang('riskstatus');?>
            </label><br>
            <input data="value:'<?php echo $customers['risk'];?>'" name="risk" type="text" data-slider-max="100" value="<?php echo($this->input->post('risk') ? $this->input->post('risk') : $customers['risk']); ?>" class="bslider form-control">
          </div>
        </div>
        <input hidden="" type="text" name="type" value="<?php echo $customers['type'];?>">
        <button type="button" data-dismiss="modal" class="btn btn-default modal-close">
            <?php echo lang('cancel');?>
        </button>
        <button type="submit" class="btn btn-default modal-close">
            <?php echo lang('update');?>
        </button>
      </div>
    </div>
  </div>
    <?php echo form_close(); ?>
</div>
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_post.php' ;?>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('form').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
            // global options for plugin
        });
    });

}); // Generate a password string
  function randString( id ) {
    var dataSet = $( id ).attr( 'data-character-set' ).split( ',' );
    var possible = '';
    if ( $.inArray( 'a-z', dataSet ) >= 0 ) {
      possible += 'abcdefghijklmnopqrstuvwxyz';
    }
    if ( $.inArray( 'A-Z', dataSet ) >= 0 ) {
      possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if ( $.inArray( '0-9', dataSet ) >= 0 ) {
      possible += '0123456789';
    }
    if ( $.inArray( '#', dataSet ) >= 0 ) {
      possible += '![]{}()%&*$#^<>~@|';
    }
    var text = '';
    for ( var i = 0; i < $( id ).attr( 'data-size' ); i++ ) {
      text += possible.charAt( Math.floor( Math.random() * possible.length ) );
    }
    return text;
  }

  // Create a new password on page load
  $( 'input[rel="gp"]' ).each( function () {
    $( this ).val( randString( $( this ) ) );
  } );

  // Create a new password
  $( ".getNewPass" ).click( function () {
    var field = $( this ).closest( 'div' ).find( 'input[rel="gp"]' );
    field.val( randString( field ) );
  } );

  // Auto Select Pass On Focus
  $( 'input[rel="gp"]' ).on( "click", function () {
    $( this ).select();
  } );
</script>
<script type="text/javascript">
  function valueChanged() {
    if ( $( '.primary-check' ).is( ":checked" ) )
      $( ".password-input" ).show();
    else
      $( ".password-input" ).hide();
  }
</script>
<script src="<?php echo base_url(); ?>assets/crm/lib/chartjs/dist/Chart.bundle.js" type="text/javascript"></script>
<script>
  $( '.bslider' ).bootstrapSlider( {
    value: <?php echo $customers['risk']; ?>,
  } );
  $( ".contact-edit-button" ).click( function () {
    $('.contact-detail-modal').modal('hide');
  });
  $( ".contact-delete-button" ).click( function () {
    $('.contact-detail-modal').modal('hide');
  });
  $('.contact-change-button').click( function(){
    $('.contact-detail-modal').modal('hide');
  });
</script>
<script>
  $( function () {
    var data = {
      "labels": [ "<?php echo lang('january');?>", "<?php echo lang('february');?>", "<?php echo lang('march');?>", "<?php echo lang('april');?>", "<?php echo lang('may');?>", "<?php echo lang('june');?>", "<?php echo lang('july');?>", "<?php echo lang('august');?>", "<?php echo lang('september');?>", "<?php echo lang('october');?>", "<?php echo lang('november');?>", "<?php echo lang('december');?>" ],
      "datasets": [ {
        "type": "line",
        backgroundColor: 'rgba(57, 57, 57, 0.69)',
        "hoverBorderColor": "#f5f5f5",

        borderColor: '#ffbc00',
        borderWidth: 1,
        "data": <?php echo $customer_annual_sales_chart ?>,
      } ]
    };
    var options = {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [ {
          categoryPercentage: .2,
          barPercentage: 1,
          position: 'top',
          gridLines: {
            color: '#C7CBD5',
            zeroLineColor: '#C7CBD5',
            drawTicks: true,
            borderDash: [ 5, 5 ],
            offsetGridLines: false,
            tickMarkLength: 10,
            callback: function ( value ) {
              console.log( value )
                // return value.charAt(0) + value.charAt(1) + value.charAt(2);
            }
          },
          ticks: {
            callback: function ( value ) {
              return value.charAt( 0 ) + value.charAt( 1 ) + value.charAt( 2 );
            }
          }
        } ],
        yAxes: [ {
          display: false,
          gridLines: {
            drawBorder: true,
            drawOnChartArea: true,
            borderDash: [ 8, 5 ],
            offsetGridLines: true
          },
          ticks: {
            beginAtZero: true,
            max: "<?php echo $ycr; ?>",
            maxTicksLimit: 12,
          }
        } ]
      },
      legend: {
        display: false
      }
    };
    var ctx = $( '#customerthisyearsalesgraph' );
    var mainChart = new Chart( ctx, {
      type: 'bar',
      data: data,
      options: options
    } );
  } );
</script>
<script>
  $( document ).ready( function () {
    //Update Contact
    var contactid = $('#cx-ci-form').attr('data-contactid');
    $( "#ajaxpost-contact-update" ).click( function () {
      $.ajax( {
        type: "POST",
        url:  "<?php echo base_url(); ?>customers/updatecontact/" + contactid,
        data: {
          name: $( ".ci-contact-name" ).val(),
          surname: $( ".ci-contact-surname" ).val(),
          phone: $( ".ci-contact-phone" ).val(),
          intercom: $( ".ci-contact-intercom" ).val(),
          mobile: $( ".ci-contact-mobile" ).val(),
          email: $( ".ci-contact-email" ).val(),
          address: $( ".ci-contact-address" ).val(),
          skype: $( ".ci-contact-skype" ).val(),
          linkedin: $( ".ci-contact-linkedin" ).val(),
          position: $( ".ci-contact-position" ).val(),
          primary: $( ".ci-contact-primary" ).val(),
          password: $( ".ci-contact-password" ).val()
        },
        dataType: "text",
        cache: false,
        success: function ( data ) {
          $.gritter.add( {
            title: '<b><?php echo lang('notification')?></b>',
            text: '<?php echo lang('contactupdated')?>',
            class_name: 'color success',
          } );
        }
      } );
      return false;
    } );
} );
</script>
<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
    $( ".add-note-button" ).click( function () {
        $.ajax( {
        type: "POST",
        url:  base_url + "trivia/addnote",
        data: {
          description: $( ".note-description" ).val(),
          relation: $( ".note-customer-id" ).val(),
          relation_type: 'customer'
        },
        dataType: "text",
        cache: false,
        success: function ( data ) {
          $.gritter.add( {
            title: '<b><?php echo lang('notification')?></b>',
            text: '<?php echo lang('noteadded')?>',
            position: 'bottom',
            class_name: 'color success',
          } );
          var noteid = data.insert_id;
          $( '.all-notes' ).append( '<div style="padding: 20px;border: 2px dashed #b7d4cd;border-radius: 10px;margin-bottom: 10px" class="ticket-data note-data" data-id="10"><li data-id="'+noteid+'" class="one-note"><a style="cursor: pointer;" class="mdi mdi-close pull-right delete-note"></a> <p>'+ $( '.note-description' ).val() +'</p> <code class="pull-left">Added by <a href="http://localhost:8888/ciuis/staff/staffmember/<?php echo $this->session->userdata('logged_in_staff_id'); ?>"><?php echo $this->session->userdata('staffname'); ?></a></code> <code class="pull-left">Date Added <span class="text-muted"><?php echo date('Y.m.d')  ?></span></code><br></li></div>');
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
            title: '<b><?php echo lang('notification')?></b>',
            text: '<?php echo lang('notedeleted')?>',
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
          title: '<b><?php echo lang('notification')?></b>',
          text: '<?php echo lang('reminderdeleted')?>',
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
        $( "#noFile" ).text( "<?php echo lang('notassignedanystaff')?>" );
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