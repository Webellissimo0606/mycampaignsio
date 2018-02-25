<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.steps.js"></script>

<!-- Page Content -->
<div class="page-container">
    <div id="page-content-wrapper">

        <div class="panel_top">
            <div class="container">

                <div class="row">

                    <?php
                    $attributes = array('id' => 'UserSettings');
                    echo form_open("", $attributes);
                    ?>
                    <div id="addproject" class="steps-style">

                        <h3>Add Domain</h3>

                        <section>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="painted_form_block">
                                    <div class="item form-group col-md-12 col-sm-6 col-xs-12">
                                        <div class="row">

                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input id="domain_name" class="form-control"  name="domain_name" data-validate-words="2" placeholder="Domain Name" required type="text">
                                            </div>
                                        </div>
                                        <div class="item form-group col-md-12 col-sm-12 col-xs-12">
                                            <div class="row featured_btn_sm">
                                                <label class="optioned control-label label_pane" for="fname">
                                                    <span class="monitorQuestion">Would you like to Monitor website uptime?</span></label>
                                                <div class="input_pane">
                                                    <span class="monitorReply"><input id="radio_01" type="radio" name="uptime" value="1" ><label class="yes_radio" for="radio_01">
                                                            Yes</label></span><span class="monitorReply"><input id="radio_02"  type="radio" name="uptime" value="0" checked><label class="no_radio" for="radio_02">
                                                            No </label></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="item form-group col-md-12 col-sm-12 col-xs-12">
                                            <div class="row featured_btn_sm">
                                                <input type="hidden" name="connectToGoogleValue" id="connectToGoogleValue" value="<?php echo $google_connect ?>">
                                                <label class="optioned control-label label_pane" for="fname">
                                                    <span class="connectGoogleQuestion">Connect to Google</span></label>
                                                <div class="input_pane">
                                                    <span class="connectGoogleReply"><input id="radio_04" type="radio" name="google_connect" value="1" ><label class="yes_radio" for="radio_04">
                                                            Yes</label></span><span class="connectGoogleReply"><input id="radio_05"  type="radio" name="google_connect" value="0" checked><label class="no_radio" for="radio_05">
                                                            No </label></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="item form-group col-md-12 col-sm-12 col-xs-12">
                                            <div class="row featured_btn_sm">
                                                <label class="optioned control-label label_pane" for="fname">
                                                    <span class="connectGoogleQuestion">Would you like to get Crawl Errors from Google Webmaster Tools?</span></label>
                                                <div class="input_pane">
                                                    <span class="connectGoogleReply"><input id="radio_11" type="radio" name="google_webmaster" value="1" ><label class="yes_radio" for="radio_11">
                                                            Yes</label></span><span class="connectGoogleReply"><input id="radio_12"  type="radio" name="google_webmaster" value="0" checked><label class="no_radio" for="radio_12">
                                                            No </label></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="item form-group col-md-12 col-sm-12 col-xs-12">
                                            <div class="row featured_btn_sm">
                                                <label class="optioned control-label label_pane" for="fname">
                                                    <span class="connectGoogleQuestion">Would you like to track your Search Queries from Google Webmaster Tools?</span></label>
                                                <div class="input_pane">
                                                    <span class="connectGoogleReply"><input id="radio_13" type="radio" name="google_search_analytics" value="1" ><label class="yes_radio" for="radio_13">
                                                            Yes</label></span><span class="connectGoogleReply"><input id="radio_14"  type="radio" name="google_search_analytics" value="0" checked><label class="no_radio" for="radio_14">
                                                            No </label></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="item form-group col-md-12 col-sm-12 col-xs-12">
                                            <div class="row featured_btn_sm">
                                                <label class="optioned control-label label_pane" for="fname">
                                                    <span class="monitorMalwareQuestion">Monitor Malware</span></label>
                                                <div class="input_pane">
                                                    <span class="monitorMalwareReply"><input id="radio_06" type="radio" name="malware" value="1" ><label class="yes_radio" for="radio_06">
                                                            Yes</label></span><span class="monitorMalwareReply"><input id="radio_07"  type="radio" name="malware" value="0" checked><label class="no_radio" for="radio_07">
                                                            No </label></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>    </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="painted_form_block">
                                    <div id="uptime_option" class="hide">
                                        <div class="item form-group col-md-12 col-sm-6 col-xs-12">
                                            <div class="row">
                                                <label class="control-label col-md-12 col-sm-12 col-xs-12" for="fname">Page Header <span class="required">*</span></label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input id="page_header" class="form-control"  name="page_header" data-validate-words="2" placeholder="Page Header To search" required type="text" value="">
                                                </div></div></div>
                                        <div class="item form-group col-md-12 col-sm-6 col-xs-12">
                                            <div class="row">
                                                <label class="control-label col-md-12 col-sm-12 col-xs-12" for="fname">Page Body <span class="required">*</span></label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input id="page_body" class="form-control"  name="page_body" data-validate-words="2" placeholder="Page Body To Search" required type="text" value="">
                                                </div></div></div>
                                        <div class="item form-group col-md-12 col-sm-6 col-xs-12">
                                            <div class="row">
                                                <label class="control-label col-md-12 col-sm-12 col-xs-12" for="fname">Page Footer <span class="required">*</span></label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input id="page_footer" class="form-control"  name="page_footer" data-validate-words="2" placeholder="Page Footer To Search" required type="text" value="">
                                                </div></div></div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div id="ga_account"  class="hide">
                                        <label>Select Google Analytics Account</label>
                                        <div class="select_gaAccount">
                                            <select name="gaAccounts" id="gaAccounts">
                                                <option value="0">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div>

                                  <!--<input type="submit" name="submit" value="NEXT" />-->

                                </div>
                            </div>
                        </section>
                        <h3>Connect WordPress</h3>

                        <section>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row painted_form_block">
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h4>Admin URL</h4>
                                        <input id="adminURL" class="form-control"  name="adminURL" data-validate-words="2" placeholder="http://" required type="url">
                                    </div>

                                    <div class="clearfix"></div>
                                    <br/>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h4>Admin username</h4>
                                        <input id="adminUsername" class="form-control"  name="adminUsername" data-validate-words="2" placeholder="Username" required type="text">
                                    </div>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                        </section>


                        <h3>Monitor Keyword SERP</h3>
                        <section>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="block_inside top-bar-sec">

                                    <div class="input-group" id="adv-search">
                                        <input type="text" class="form-control domain-select" value="" placeholder=" Choose your search engines" />
                                        <div class="input-group-btn">
                                            <div class="btn-group" role="group">
                                                <div class="dropdown dropdown-lg">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                        <form class="form-horizontal" role="form">
                                                            <div class="form-group">
                                                                <h2><i class="fa fa-search"></i>
                                                                    Choose your search engines</h2>
                                                                <ul>
                                                                    <li class="checks_inside"><input  name="engines" id="check1" type="checkbox" value="en-uk"><label for="check1">   <a href="#">https://www.google.co.uk/</a></label></li>
                                                                    
                                                                    
                                                                </ul>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="painted_form_block">
                                    <div class="keywords_block">
                                        <br>
                                        <textarea placeholder="Enter your keywords, one per line, that you would like to monitor" rows="4" id="keywords"></textarea>
                                        <!--                                        <div class="btn-grp">
                                        
                                                                                    <button class="btn_sm green" type="Save">Save</button>
                                                                                    <button class="btn_sm red" type="submit">Cancel</button>
                                                                                    <div class="clearfix"></div>
                                                                                </div>-->
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="painted_form_block">
                                    <div class="checks_inside"><input id="mobile_check1" name="mobile_search" type="checkbox" value="0"><label for="mobile_check1">Include Mobile Search</label> </div>
                                    <div class="checks_inside"><input  id="localsearch_check2" name="local_search" type="checkbox" value="0"><label for="localsearch_check2">Include Local Search</label> </div>
                                    <ul class="embed_input">


                                        <li><input type="text" value="" placeholder="Enter Town" class="local_town" /><button class="btn1">
                                            </button></li><br><br>
                                    </ul>
                                </div>
                            </div>
                        </section>
                        <h3> Monitor OnPage Issues</h3>
                        <section>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="painted_form_block">
                                        <span class="monitorQuestion control-label col-md-12 col-sm-12 col-xs-12">Would you like to monitor on page issues?</span>
                                        <span class="col-md-12 col-sm-12 col-xs-12">
                                            <span class="monitorReply"><input type="radio" name="monitor" value="0" <?php
                                                if (!empty($user_settings)) {
                                                    if (isset($user_settings->monitorOnPageIssues)) {
                                                        if ($user_settings->monitorOnPageIssues == 1) {
                                                            echo 'checked';
                                                        }
                                                    }
                                                } else {
                                                    echo 'checked';
                                                }
                                                ?>>No </span>

                                            <span class="monitorReply"><input type="radio" name="monitor" value="1" <?php
                                                                              if (isset($user_settings->monitorOnPageIssues)) {
                                                                                  if ($user_settings->monitorOnPageIssues == 1) {
                                                                                      echo 'checked';
                                                                                  }
                                                                              }
                                                                              ?>>Yes</span>
                                        </span>
                                    </div>  </div></div>
                        </section>

                    </div>
                    <?php echo form_close(); ?>
                </div>

            </div>


        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->

<!-- /#wrapper -->
<?php $this->load->view(get_template_directory() . 'footer'); ?>


<script type="text/javascript">

     var connectToGoogle = 0;
        var googleAccount = 0;
        var uptimeFields = 0;
        var pageHeader = "";
        var pageBody = "";
        var pageFooter = "";
    jQuery(document).ready(function ($) {
       
        $('#tabs').tab();
        $(".btn1").click(function () {
            $(".embed_input").append("<li><input type='text' value='' placeholder='Enter Town' class='local_town' /><button class='btn1'></button></li><br><br>");
        });
        $('input[name=uptime]').click(function () {
            var uptime = $('input[name=uptime]:checked').val();

            if ($('#uptime_option').hasClass('hide')) {
                $('#uptime_option').removeClass('hide');

            }
            if (!$('#ga_account').hasClass('hide')) {
                $('#ga_account').addClass('hide');
            }

            if (uptime == '1')
            {
                $('#uptime_option').css('display', 'block');
                $('#page_header').val(pageHeader);
                $('#page_body').val(pageBody);
                $('#page_footer').val(pageFooter);



            } else {
                pageHeader = "";
                pageBody = "";
                pageFooter = "";
                $('#uptime_option').css('display', 'none');
                $('#page_header').val(pageHeader);
                $('#page_body').val(pageBody);
                $('#page_footer').val(pageFooter);
            }
        });
        $('input[name=google_connect]').click(function () {
            pageHeader = $('#page_header').val();
            pageBody = $('#page_body').val();
            pageFooter = $('#page_footer').val();
            var google_connect = $('input[name=google_connect]:checked').val();
            if ($('#ga_account').hasClass('hide')) {
                $('#ga_account').removeClass('hide');
            }
            if (!$('#uptime_option').hasClass('hide')) {
                $('#uptime_option').addClass('hide');
            }
            if (google_connect == 1) {

                connectToGoogle = $('#connectToGoogleValue').val();
                if (connectToGoogle != 1) {
                    login();
                } else {
                    if (googleAccount == 0) {
                        $('#ga_account').css('display', 'none');
                        $.ajax({
                            type: "POST",
                            url: siteUrl + "analytics/analytics/getUserGAAccounts",
                            dataType: 'json',
                            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                            success: function (data) {
                                $('#gaAccounts').html("");
                                $.each(data, function (key, value) {
                                    $('#gaAccounts')
                                            .append($("<option></option>")
                                                    .attr("value", value.id)
                                                    .text(value.name));
                                    googleAccount = 1;
                                });

                            },
                            complete: function () {
                                $('#ga_account').css('display', 'block');
                            }
                        });
                    } else {
                        $('#ga_account').css('display', 'block');
                    }
                }

            } else {
                $('#gaAccounts')
                        .html($("<option></option>")
                                .attr("value", '0')
                                .text('select'));
                googleAccount = 0;
                $('#ga_account').css('display', 'none');
            }
        });
        
        $('input[name=google_webmaster]').click(function () {
            var webmaster = $(this).val();
            var webmaster = $(this).val();
            pageHeader = $('#page_header').val();
            pageBody = $('#page_body').val();
            pageFooter = $('#page_footer').val();
            if (!$('#uptime_option').hasClass('hide')) {
                $('#uptime_option').addClass('hide');
            }
            if (!$('#ga_account').hasClass('hide')) {
                $('#ga_account').addClass('hide');
            }
            if (webmaster == 1) {
                connectToGoogle = $('#connectToGoogleValue').val();
                if (connectToGoogle != 1) {
                    login_webmaster();
                }
            }
        });
        
        $('input[name=google_search_analytics]').click(function () {
            var analytics = $(this).val();
            pageHeader = $('#page_header').val();
            pageBody = $('#page_body').val();
            pageFooter = $('#page_footer').val();
            if (!$('#uptime_option').hasClass('hide')) {
                $('#uptime_option').addClass('hide');
            }
            if (!$('#ga_account').hasClass('hide')) {
                $('#ga_account').addClass('hide');
            }
            if (analytics == 1) {
                connectToGoogle = $('#connectToGoogleValue').val();
                if (connectToGoogle != 1) {
                    login_webmaster();
                }
            }
        });

        $('input[name=malware]').click(function () {
            pageHeader = $('#page_header').val();
            pageBody = $('#page_body').val();
            pageFooter = $('#page_footer').val();
            if (!$('#uptime_option').hasClass('hide')) {
                $('#uptime_option').addClass('hide');
            }
            if (!$('#ga_account').hasClass('hide')) {
                $('#ga_account').addClass('hide');
            }
        });

    });



//https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id=880327336203-7clktmd0r08q6424ebhtgqksnvv2ug3c.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost%2Fcampaigns%2Fanalytics%2Fanalytics&state&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics.readonly%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics.edit%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics.manage.users%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics.manage.users.readonly&approval_prompt=force
    var domainId = '';

    $("#addproject").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex)
        {
            return true;
        },
        onFinishing: function (event, currentIndex)
        {
            //code added by Mannan
            var keywords = $('#keywords').val();
            var mobile_search = 0;
            var local_search = 0;
            if($("input[name='mobile_search']").is(':checked')){
                mobile_search = 1;   
            }
            if($("input[name='local_search']").is(':checked')){
                local_search = 1;   
            }
            var engines = '';
            var towns = '';
            var engines_arr = [];
            var domain_Id = '';
            var towns_arr = [];
            $("input[name='engines']:checked").each(function(){
                engines_arr.push($(this).val());
            })
            $('.local_town').each(function(){
                if($(this).val() != ''){
                    towns_arr.push($(this).val());
                }
            })

            engines = engines_arr.join(", ");
            towns = towns_arr.join(", ");
            //end
            $.ajax({
                type: "POST",
                url: siteUrl + "auth/auth/addDomain",
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', 
                    monitor: $('input[name=monitor]:checked').val(), 
                    connectToGoogle: connectToGoogle, 
                    domain: $('#domain_name').val(), 
                    ga_account: $('#gaAccounts').val(), 
                    monitorMalware: $('input[name=malware]:checked').val(), 
                    adminURL: $('input[name=adminURL]').val(),
                    adminUsername: $('input[name=adminUsername]').val(),
                    engines: engines, 
                    keywords: keywords, 
                    mobile_search: mobile_search, 
                    local_search: local_search, 
                    town:towns,
                    webmaster: $('input[name=google_webmaster]').val(),
                    page_header: pageHeader,
                    page_body:pageBody,
                    page_footer:pageFooter, 
                    search_analytics: $('input[name=google_search_analytics]').val(),
                },
                success: function (data) {
                    $('.domain-selected').val(data.domainURL);
                    $('.domain-select').val(data.domainHost);
                    domain_Id = data.domainId;

                }
            }).done(function (data) {
                if (typeof domain_Id !== 'undefined') {
                    window.location.href = siteUrl + "auth/dashboard/" + domain_Id;
                } else {
                    window.location.href = siteUrl + "auth/dashboard/";
                }

            });

            return true;
        },
        onFinished: function (event, currentIndex)
        {

        }
    });




    var SCOPE = 'https://www.googleapis.com/auth/analytics.readonly https://www.googleapis.com/auth/analytics https://www.googleapis.com/auth/analytics.edit https://www.googleapis.com/auth/analytics.manage.users https://www.googleapis.com/auth/analytics.manage.users.readonly https://www.googleapis.com/auth/analytics.manage.users.readonly https://www.googleapis.com/auth/webmasters';
    var CLIENTID = "<?php echo $client_id ?>";
    var REDIRECT = '<?php echo $redirect_uri ?>';
    var TYPE = 'code';
    var Access_Type = 'offline';
    var ApprovalPrompt = 'force';
    //var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&access_type' + Access_Type + '&approval_prompt' + ApprovalPrompt;
    var _url = "https://accounts.google.com/o/oauth2/auth?response_type=" + TYPE + "&access_type=" + Access_Type + "&client_id=" + CLIENTID + "&redirect_uri=" + REDIRECT + "&scope=" + SCOPE + "&approval_prompt=" + ApprovalPrompt;
    var acToken;
    var tokenType;
    var expiresIn;
    var refToken;
    var user;

    function login() {
        var win = window.open(_url, "windowname1", 'width=800, height=600');

        var pollTimer = window.setInterval(function () {
            if (typeof win.document !== "undefined") {


                if (win.document.URL.indexOf(REDIRECT) !== -1) {
                    window.clearInterval(pollTimer);
                    connectToGoogle = 1;
                    win.close();

                    $.ajax({
                        type: "POST",
                        url: siteUrl + "analytics/analytics/getUserGAAccounts",
                        dataType: 'json',
                        data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                        success: function (data) {
                            $('#gaAccounts').html("");
                            $.each(data, function (key, value) {
                                $('#gaAccounts')
                                        .append($("<option></option>")
                                                .attr("value", value.id)
                                                .text(value.name));
                            });

                        },
                        complete: function () {
                            $('#ga_account').css('display', 'block');
                        }
                    });
               //validateToken(acToken);
                }
            }
        }, 100);

        return true;
    }
    
    function login_webmaster() {
        var win = window.open(_url, "windowname1", 'width=800, height=600');

        var pollTimer = window.setInterval(function () {
            if (typeof win.document !== "undefined") {


                if (win.document.URL.indexOf(REDIRECT) !== -1) {
                    window.clearInterval(pollTimer);
                    connectToGoogle = 1;
                    win.close();

                   $.ajax({
                        type: "POST",
                        url: siteUrl + "analytics/analytics/getUserGAAccounts",
                        dataType: 'json',
                        data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                        success: function (data) {
                            

                        },
                        complete: function () {
                           
                        }
                    });
                }
            }
        }, 100);

        return true;
    }




    function addsite() {
        $.post("http://app.campaigns.io/auth/add_project", {name: "John", time: "2pm"})
                .done(function (data) {
                    alert("Data Loaded: " + data);
                });
    }


</script>
