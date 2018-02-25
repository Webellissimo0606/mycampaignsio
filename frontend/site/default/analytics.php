<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');

?>
<style type="text/css">
    span.data-value {
        font-size: 25px;
        float: right;
    }
    span.data-heading {
        font-size: 20px;
        color: #fff;
        font-weight: bold;
    }
    .data-summary {
        padding: 20px 15px;
        background: #63A3DA;
        border-left: 1px solid #FFF;
        border-right: 1px solid #FFF;
        border-bottom: 1px solid #FFF;
    }
    .session-country-header {
        font-size: 20px;
        padding-top: 10px;
        text-align: left;
        font-weight: bold;
        color: #363941;
    }
    .session-country-sub-header {
        font-size: 15px;
        text-align: center;
        color: #63A3DA;
    }
    .domain-select-options select {
        padding: 8px 50px;
        color: #E63026;
        font-size: 15px;
        font-weight: 400;
    }
    .domain-select-options {
        padding: 20px;
        background: #4B5E6F;
        width   : 100%;
    }
    .column-heading {
        margin-top: 10px;
        font-size: 20px;
        border-bottom: 1px solid #63A3DA;
        font-weight: bold;
    }
    .column-sub-heading {
        padding: 10px 5px;
    }
    .column-sub-heading ul li {
        padding: 5px;
        font-size: 15px;
        color: #363941;
        cursor: pointer;
    }
    .selected-action-data {
        margin-top: 10px;
    }
    .selected-action-data table th {
        font-size: 20px;
        border-bottom: 1px solid #63A3DA;
        font-weight: bold;
        text-align: center;
    }
    .selected-action-data table td {
        padding: 5px;
        font-size: 15px;
        color: #363941;
        cursor: pointer;
        text-align: center;
    }
    .auth-button a {
        color: #fff;
        font-weight: bold;
        padding: 20px;
        background: #63A3DA;
        border-radius: 10px;
        border: 1px solid#363941;
        font-size: 30px;
    }
    .auth-button {
        width: 100%;
        text-align: center;
        padding: 20px;
        color: #fff !important;
    }
    :host {
        display: inline-block;
    }
    .control {
        @apply(--google-analytics-date-selector-control);
    }
    label {
        @apply(--google-analytics-date-selector-label);
    }
    input {
        color: inherit;
        font: inherit;
        margin: 0;
        @apply(--google-analytics-date-selector-input);
    }
    input:focus {
        @apply(--google-analytics-date-selector-input-focus);
    }
	.changemycanvas {
		max-height:  200px !important;
	}
	.sometopmargin {
		margin-top: 20px;
	}
	.sometopmargin .data-summary {
		padding: 2px 15px !important;
	}
	.sometopmargin .data-heading {
		font-size: 16px;
	}
	.sometopmargin .data-value {
		font-size: 18px;
	}
	.changesomefonts .column-heading {
		font-size: 16px !important;
	}
	.changesomefonts .column-sub-heading ul li {
		font-size: 14px !important;
		padding: 0 !important;
	}
	.changesomefonts .selected-action-data table th {
		font-size: 16px !important;
		text-align: left !important;
	}
	.changesomefonts .selected-action-data table td {
		font-size: 14px !important;
		padding: 5px 0 !important;
		text-align: left !important;
	}
</style>
<div class="page-container"> 
    <!-- Content -->
    <div class="page-content">
        <div class="page-content-inner"> 
            <!-- Page header -->
            <div class="page-header">
                <div class="page-title profile-page-title">
                    <h2>Google Analytics</h2>

                </div>
                <div class="row">
<!--Custom-grid-->
<div class="cg-site-content">
            <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="cg-panel">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-globe" aria-hidden="true"></i> Total Visits</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12 ">
                    <div class="cg-panel">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Grraph</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="cg-panel cg-panel-style-1">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Page Per Visit</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="cg-panel cg-panel-style-1">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Unique Visitor</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>
                </div>
                </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="cg-panel cg-panel-style-1">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Site Visitor Stat</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="cg-panel cg-panel-style-1">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Visits</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>

                    <div class="cg-panel cg-panel-style-1">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Top Countries (Visits)</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>

                </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="cg-panel cg-panel-style-1">
                        <div class="cg-panel-head">
                        <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Top Sources/Medium(Visits)</h6>
                        </div>
                        <div class="cg-panel-content">
                            
                        </div>
                    </div>

                </div>
            </div>  

<!--Custom-grid-->

                
    </script>
    <?php $this->load->view(get_template_directory() . 'footer'); ?>