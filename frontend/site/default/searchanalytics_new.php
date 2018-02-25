<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header_new');
?>

        <div class="row">
			<div class="col-md-12">
				<div class="panel">
					<div class="panel-content">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  <label class="form-label">Starting Date</label>
								  <div class="prepend-icon">
								    <!-- <input type="text" name="timepicker" class="b-datepicker form-control" placeholder="Select a date..." data-orientation="top"> -->
								    <input name="timepicker" class="form-control" type="text" placeholder="Select a date..." data-orientation="top"  id="startDate" >
								    <i class="icon-calendar"></i>
								  </div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
								  <label class="form-label">Ending Date</label>
								  <div class="prepend-icon">
								    <!-- <input type="text" name="timepicker" class="b-datepicker form-control" placeholder="Select a date..." data-orientation="top"> -->
								    <input name="timepicker" class="form-control" type="text" placeholder="Select a date..." id="endDate" data-orientation="top">
								    <i class="icon-calendar"></i>
								  </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

			
		<div class="row">
			<div class="col-md-6 col-lg-6 col-xs-12">
				<div class="panel">  
					<div class="panel-header">
						<h3><strong>Webmaster Tool Analysis</strong></h3>
					</div>
					<div class="panel-content padding-top-zero">
						<form id="options" method="post">
						    <div class="query-box">
								<div class="row" style="margin-bottom: 10px;">
									<div class="analytics_type col-md-12 col-lg-12 col-xs-12">
							            <label><input class="analytics_type_checkbox analytics_type_checkbox_click" type="checkbox"  value="clicks" checked />Clicks</label>
							            <label><input class="analytics_type_checkbox analytics_type_checkbox_impression" type="checkbox" value="impressions" checked>Impressions</label>
							            <label><input class="analytics_type_checkbox analytics_type_checkbox_ctr" type="checkbox" value="ctr" checked>CTR</label>
							            <label><input class="analytics_type_checkbox analytics_type_checkbox_position" type="checkbox" value="position" checked>Position</label>
							        </div>
								</div>
						        
						        <div class="row">
						        	<div class="dimensions_type col-md-10 col-lg-10 col-xs-12">
								        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="query" checked>Queries</label>
								        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="page">Pages</label>
								        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="country">Countries</label>
								        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="device">Devices</label>
								        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="search_type">Search Type</label>
								    </div>

								    <div class="col-md-2 col-lg-2 col-xs-2">
								    	<div class="form-group">
								    	  <select class="form-control form-white" data-placeholder="Select a country..." id="search-type">
								    	    <option value="web" selected="selected">Web</option>
								    	    <option value="image">Image</option>
								    	    <option value="video">Video</option>
								    	  </select>
								    	</div>
								    </div>
						        </div>

							    
						    </div>
						</form>

						<div class="graph-box">
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-6 col-xs-12">
				<?php
				$clicks = $impressions = $ctr = $position = $count = $avg_ctr = $avg_position = 0;
				if (!empty($date)) {
			    foreach ($date->rows as $key => $value) {
			        $count = $key + 1;
			        $clicks += $value->clicks;
			        $impressions += $value->impressions;
			        $ctr += $value->ctr;
			        $position += $value->position;
			    }
			    $avg_ctr = (float) number_format(($ctr / $count) * 100, 2, ".", "") . '%';
			    $avg_position = (float) number_format(($position / $count), 1, ".", "");
			    ?>

			    <div class="row">
			    	<div class="col-md-6">
			    		<div class="panel key_avg_pos">  
			    			<div class="panel-content bg-blue">
			    			  <div class="row">
			    			    <div class="col-md-7"><h4>Total Clicks</h4></div>
			    			    <div class="col-md-5"><span><?php echo $clicks; ?></span></div>
			    			  </div>
			    			</div>
			    		</div>		
			    	</div>
			    	
			    	<div class="col-md-6">
			    		<div class="panel key_avg_pos">  
			    			<div class="panel-content bg-yellow">
			    			  <div class="row">
			    			    <div class="col-md-7"><h4>Total Impressions</h4></div>
			    			    <div class="col-md-5"><span><?php echo $impressions; ?></span></div>
			    			  </div>
			    			</div>
			    		</div>
			    	</div>
			    </div>

			    <div class="row">
			    	<div class="col-md-6">
			    		<div class="panel bg-green">  
			    			<div class="panel-header">
			    				<h3><strong>Average CTR</strong></h3>
			    			</div>
			    			
			    			<div class="panel-content padding-top-zero">
			    				<?php 
			    					$string = $avg_ctr;
			    					$avg_ctr_num = rtrim($string, "%");
			    					$avg_ctr_value = $avg_ctr_num/100;
			    				 ?>
			    				<div id="circle">
			    					<strong></strong>
			    				</div>
			    			</div>
			    			
			    		</div>		
			    	</div>
			    	
			    	<div class="col-md-6">
			    		<div class="panel padding-zero" style="background: #e3f0ff;">  
			    			
			    			<div class="panel-content padding-zero">
			    				<div class="click-box">
				                    <i class="icon-trophy" style="margin-bottom: 18px;"></i>
				                    <span><?php echo $avg_position; ?></span>
				                    <h3>Average Position</h3>
			                    </div>
			    			</div>
			    			
			    		</div>		
			    	</div>
			    </div>

			    <?php } else { ?>
			    	<div class="row">
			    		<div class="col-md-12">
			    			<h2>No Data Available.</h2>
			    		</div>
			        </div>
			    <?php } 
			    ?>
			</div>
		</div>
			
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel">  
						<div class="panel-header">
							<h3><strong>Google Search Analytics</strong></h3>
						</div>
						
						
						<div class="panel-content pagination2 table-responsive">
				            <div class="table-box">
				                <?php if (!empty($query)) { ?>
    		                        <table id="datatable" class="table table-bordered ds-list-table dataTable no-footer" role="grid">
    		                            <thead>
    		                                <tr>
    		                                    <th class="col-md-4 col-sm-4 queries">Term </th>
    		                                    <th class="col-md-2 col-sm-2 clicks">Click</th>
    		                                    <th class="col-md-2 col-sm-2 impressions">Views</th>
    		                                    <th class="col-md-2 col-sm-2 ctr">CTR</th>
    		                                    <th class="col-md-2 col-sm-2 position">POS</th>
    		                                </tr>
    		                            </thead>
    									
    		                        </table>
    		                    <?php } ?>
				            </div>

						</div>
					</div>
			</div>
			
			
		<div class="row">
			  
		  
				<div class="col-md-12">
					
				</div>
        </div>

		
        <!-- END PAGE CONTENT -->
      </div>
      <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      $this->load->view(get_template_directory() . 'footer_new');
      ?>
      <script type="text/javascript">
          // $(document).ready(function () {
              sessionLineChartByDate();
              var startDate = '<?php echo $startDate ?>';
              var endDate = '<?php echo $endDate ?>';
              $('#startDate').datetimepicker({defaultDate: startDate,
                  format: "DD/MM/YYYY"});
              $('#endDate').datetimepicker({defaultDate: endDate,
                  format: "DD/MM/YYYY"});


              // $('.impressions').css('visibility', 'hidden');
              // $('.ctr').css('visibility', 'hidden');
              // $('.position').css('visibility', 'hidden');

              $('.analytics_type_checkbox').on('ifChanged', function (event) {
                  // if ($('.analytics_type_checkbox_click').is(":checked"))
                  // {

                  //     $('.clicks').css('visibility', 'visible');
                  // } else {
                  //     $('.clicks').css('visibility', 'hidden');
                  // }
                  // if ($('.analytics_type_checkbox_impression').is(":checked"))
                  // {
                  //     $('.impressions').css('visibility', 'visible');
                  // } else {
                  //     $('.impressions').css('visibility', 'hidden');
                  // }
                  // if ($('.analytics_type_checkbox_ctr').is(":checked"))
                  // {
                  //     $('.ctr').css('visibility', 'visible');
                  // } else {
                  //     $('.ctr').css('visibility', 'hidden');
                  // }
                  // if ($('.analytics_type_checkbox_position').is(":checked"))
                  // {
                  //     $('.position').css('visibility', 'visible');
                  // } else {
                  //     $('.position').css('visibility', 'hidden');
                  // }

                  sessionLineChartByDate();
              });
              ///change
              function clickHandler() {
                  $('.analytics_type').click();
              }


              function searchAnalyticsByDimensionType(dimension, searchType, oTable) {
                        var start_date = $('#startDate').val().split('/');
                        var startDate = start_date[2] + '-' + start_date[1] + '-' + start_date[0];
                        var end_date = $('#endDate').val().split('/');
                        var endDate = end_date[2] + '-' + end_date[1] + '-' + end_date[0];
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>analytics/analytics/searchAnalyticsByDimensionType",
                            dataType: 'json',
                            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', dimension: dimension, searchType: searchType, endDate: endDate, startDate: startDate},
                            success: function (data) {
                           
                                var query = data.query;
                                var date = data.date;
                                if (query.length > 0) {
                                    oTable.fnClearTable();
                                    var keys = '';
                                    for (var i = 0; i < query.length; i++) {
                                        if (dimension == 'country') {
                                            keys = query[i][0];

                                            keys = getCountryName(keys.toUpperCase());
                                            if (typeof keys === 'undefined') {
                                                keys = "UNKNOWN REGION";
                                            }
                                        } else {
                                            keys = query[i][0];
                                        }
                                        oTable.fnAddData([keys, query[i][1], query[i][2], query[i][3], query[i][4]]);
                                    }
                                    $('.analytics_type').click();
                                } else {
                                    $('.table-box').html("<h2>No Data Available</h2>");
                                }

                                if (date.length > 0) {
                                    var clicks = 0;
                                    var impressions = 0;
                                    var ctr = 0;
                                    var position = 0;
                                    var count = 0;
                                    var avg_ctr = 0;
                                    var avg_postition = 0;
                                    $.each(date, function (key, value) {
                                        count += 1;
                                        clicks += value.clicks;
                                        impressions += value.impressions;
                                        ctr += parseFloat(value.ctr);
                                        position += parseFloat(value.position);

                                    });
                                    avg_ctr = parseFloat((ctr / count) * 100);
                                    avg_ctr = avg_ctr.toFixed(2);

                                    avg_postition = parseFloat(position / count);
                                    avg_postition = avg_postition.toFixed(2);

                                    if(isNaN(clicks)){
                                    	clicks = 0;
                                    }
                                    if(isNaN(impressions)){
                                    	impressions = 0;
                                    }
                                    if(isNaN(avg_ctr)){
                                    	avg_ctr = 0;
                                    }
                                    if(isNaN(avg_postition)){
                                    	avg_postition = 0;
                                    }


                                    $('.clicks-total').html(clicks);
                                    $('.impressions-total').html(impressions);
                                    $('.ctr-total').html(avg_ctr);
                                    $('.position-total').html(avg_postition);

                                    sessionLineChartByDateOnChange(date);
                                } else {
                                    $('.table-box').html("<h2>No Data Available</h2>");
                                    $('.total-box').html("");
                                    $('.graph-box').html("");
                                }



                            }
                        });
                    }

              var oTable = $('#datatable').dataTable({
                  stateSave: true,
                  bPaginate: true,
                  sPaginationType: "full_numbers",
                  fnDrawCallback: function () {
                      $('.dataTables_paginate a')
                              .off("click", clickHandler)
                              .on("click", clickHandler)
                  },
                  aoColumns: [
                      {"sClass": "queries", "sDisplay": "none"},
                      {"sClass": "clicks", "sDisplay": "none"},
                      {"sClass": "impressions", "sDisplay": "none"},
                      {"sClass": "ctr", "sDisplay": "none"},
                      {"sClass": "position", "sDisplay": "none"}
                  ]
              });
              oTable.fnSort([[2, 'desc']]);
                      
              
              $('.dimensions_type_radio').on('ifChanged', function (event) {
                  $('th.queries').text($('input[type="radio"]:checked', '#options').val());
                  if ($('input[type="radio"]:checked', '#options').val() == 'search_type') {
                      $('.table-box').css('display', 'none')
                  } else {
                      var dimension = $('input[type="radio"]:checked', '#options').val();
                      var searchType = $('#search-type').val();
                      console.log(searchType);
                      searchAnalyticsByDimensionType(dimension, searchType, oTable);
                      //$('.table-responsive').html('');
                      $('.table-box').css('display', '');

                  }
              });

              $('#endDate').on("dp.change", function (e) {
                  var dimension = $('input[type="radio"]:checked', '#options').val();
                  var searchType = $('#search-type').val();
                  searchAnalyticsByDimensionType(dimension, searchType, oTable);

              });

              $("#startDate").on("dp.change", function (e) {
                  var dimension = $('input[type="radio"]:checked', '#options').val();
                  var searchType = $('#search-type').val();
                  searchAnalyticsByDimensionType(dimension, searchType, oTable);

              });


      //        oTable.fnSort([[2, 'desc']]);

              $.ajax({
                  url:  "<?php echo base_url(); ?>analytics/analytics/getQueriesByDefault",
                  data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                  dataType: 'json',
                  success: function (s) {
                      oTable.fnClearTable();
                      for (var i = 0; i < s.length; i++) {
                          oTable.fnAddData([s[i][0], s[i][1], s[i][2], s[i][3], s[i][4]]);
                      } // End For 
                      $('.analytics_type').click();
                  }, error: function (e) {

                  }});


          // });

          function getCountryName(code) {
              var country_codes = {
                  AFG: 'AFGHANISTAN',
                  ALB: 'ALBANIA',
                  DZA: 'ALGERIA',
                  ASM: 'AMERICAN SAMOA',
                  AND: 'ANDORRA',
                  AGO: 'ANGOLA',
                  AIA: 'ANGUILLA',
                  ATA: 'ANTARCTICA',
                  ATG: 'ANTIGUA AND BARBUDA',
                  ARG: 'ARGENTINA',
                  ARM: 'ARMENIA',
                  ABW: 'ARUBA',
                  AUS: 'AUSTRALIA',
                  AUT: 'AUSTRIA',
                  AZE: 'AZERBAIJAN',
                  BHS: 'BAHAMAS',
                  BHR: 'BAHRAIN',
                  BGD: 'BANGLADESH',
                  BRB: 'BARBADOS',
                  BLR: 'BELARUS',
                  BEL: 'BELGIUM',
                  BLZ: 'BELIZE',
                  BEN: 'BENIN',
                  BMU: 'BERMUDA',
                  BTN: 'BHUTAN',
                  BOL: 'BOLIVIA',
                  BIH: 'BOSNIA AND HERZEGOWINA',
                  BWA: 'BOTSWANA',
                  BVT: 'BOUVET ISLAND',
                  BRA: 'BRAZIL',
                  IOT: 'BRITISH INDIAN OCEAN TERRITORY',
                  BRN: 'BRUNEI DARUSSALAM',
                  BGR: 'BULGARIA',
                  BFA: 'BURKINA FASO',
                  BDI: 'BURUNDI',
                  KHM: 'CAMBODIA',
                  CMR: 'CAMEROON',
                  CAN: 'CANADA',
                  CPV: 'CAPE VERDE',
                  CYM: 'CAYMAN ISLANDS',
                  CAF: 'CENTRAL AFRICAN REPUBLIC',
                  TCD: 'CHAD',
                  CHL: 'CHILE',
                  CHN: 'CHINA',
                  CXR: 'CHRISTMAS ISLAND',
                  CCK: 'COCOS ISLANDS',
                  COL: 'COLOMBIA',
                  COM: 'COMOROS',
                  COG: 'CONGO',
                  COD: 'CONGO, THE DRC',
                  COK: 'COOK ISLANDS',
                  CRI: 'COSTA RICA',
                  CIV: 'COTE D IVOIRE',
                  HRV: 'CROATIA',
                  CUB: 'CUBA',
                  CYP: 'CYPRUS',
                  CZE: 'CZECH REPUBLIC',
                  DNK: 'DENMARK',
                  DJI: 'DJIBOUTI',
                  DMA: 'DOMINICA',
                  DOM: 'DOMINICAN REPUBLIC',
                  TMP: 'EAST TIMOR',
                  ECU: 'ECUADOR',
                  EGY: 'EGYPT',
                  SLV: 'EL SALVADOR',
                  GNQ: 'EQUATORIAL GUINEA',
                  ERI: 'ERITREA',
                  EST: 'ESTONIA',
                  ETH: 'ETHIOPIA',
                  FLK: 'FALKLAND ISLANDS',
                  FRO: 'FAROE ISLANDS',
                  FJI: 'FIJI',
                  FIN: 'FINLAND',
                  FRA: 'FRANCE',
                  FXX: 'FRANCE, METROPOLITAN',
                  GUF: 'FRENCH GUIANA',
                  PYF: 'FRENCH POLYNESIA',
                  ATF: 'FRENCH SOUTHERN TERRITORIES',
                  GAB: 'GABON',
                  GMB: 'GAMBIA',
                  GEO: 'GEORGIA',
                  DEU: 'GERMANY',
                  GHA: 'GHANA',
                  GIB: 'GIBRALTAR',
                  GRC: 'GREECE',
                  GRL: 'GREENLAND',
                  GRD: 'GRENADA',
                  GLP: 'GUADELOUPE',
                  GUM: 'GUAM',
                  GTM: 'GUATEMALA',
                  GIN: 'GUINEA',
                  GNB: 'GUINEA-BISSAU',
                  GUY: 'GUYANA',
                  HTI: 'HAITI',
                  HMD: 'HEARD AND MC DONALD ISLANDS',
                  VAT: 'HOLY SEE (VATICAN CITY STATE)',
                  HND: 'HONDURAS',
                  HKG: 'HONG KONG',
                  HUN: 'HUNGARY',
                  ISL: 'ICELAND',
                  IND: 'INDIA',
                  IDN: 'INDONESIA',
                  IRN: 'IRAN',
                  IRQ: 'IRAQ',
                  IRL: 'IRELAND',
                  ISR: 'ISRAEL',
                  ITA: 'ITALY',
                  JAM: 'JAMAICA',
                  JPN: 'JAPAN',
                  JOR: 'JORDAN',
                  KAZ: 'KAZAKHSTAN',
                  KEN: 'KENYA',
                  KIR: 'KIRIBATI',
                  PRK: 'D.P.R.O. KOREA',
                  KOR: 'REPUBLIC OF KOREA',
                  KWT: 'KUWAIT',
                  KGZ: 'KYRGYZSTAN',
                  LAO: 'LAOS',
                  LVA: 'LATVIA',
                  LBN: 'LEBANON',
                  LSO: 'LESOTHO',
                  LBR: 'LIBERIA',
                  LBY: 'LIBYAN ARAB JAMAHIRIYA',
                  LIE: 'LIECHTENSTEIN',
                  LTU: 'LITHUANIA',
                  LUX: 'LUXEMBOURG',
                  MAC: 'MACAU',
                  MKD: 'MACEDONIA',
                  MDG: 'MADAGASCAR',
                  MWI: 'MALAWI',
                  MYS: 'MALAYSIA',
                  MDV: 'MALDIVES',
                  MLI: 'MALI',
                  MLT: 'MALTA',
                  MHL: 'MARSHALL ISLANDS',
                  MTQ: 'MARTINIQUE',
                  MRT: 'MAURITANIA',
                  MUS: 'MAURITIUS',
                  MYT: 'MAYOTTE',
                  MEX: 'MEXICO',
                  FSM: 'FEDERATED STATES OF MICRONESIA',
                  MDA: 'REPUBLIC OF MOLDOVA',
                  MCO: 'MONACO',
                  MNG: 'MONGOLIA',
                  MSR: 'MONTSERRAT',
                  MAR: 'MOROCCO',
                  MOZ: 'MOZAMBIQUE',
                  MMR: 'MYANMAR',
                  NAM: 'NAMIBIA',
                  NRU: 'NAURU',
                  NPL: 'NEPAL',
                  NLD: 'NETHERLANDS',
                  ANT: 'NETHERLANDS ANTILLES',
                  NCL: 'NEW CALEDONIA',
                  NZL: 'NEW ZEALAND',
                  NIC: 'NICARAGUA',
                  NER: 'NIGER',
                  NGA: 'NIGERIA',
                  NIU: 'NIUE',
                  NFK: 'NORFOLK ISLAND',
                  MNP: 'NORTHERN MARIANA ISLANDS',
                  NOR: 'NORWAY',
                  OMN: 'OMAN',
                  PAK: 'PAKISTAN',
                  PLW: 'PALAU',
                  PAN: 'PANAMA',
                  PNG: 'PAPUA NEW GUINEA',
                  PRY: 'PARAGUAY',
                  PER: 'PERU',
                  PHL: 'PHILIPPINES',
                  PCN: 'PITCAIRN',
                  POL: 'POLAND',
                  PRT: 'PORTUGAL',
                  PRI: 'PUERTO RICO',
                  QAT: 'QATAR',
                  REU: 'REUNION',
                  ROM: 'ROMANIA',
                  RUS: 'RUSSIAN FEDERATION',
                  RWA: 'RWANDA',
                  KNA: 'SAINT KITTS AND NEVIS',
                  LCA: 'SAINT LUCIA',
                  VCT: 'SAINT VINCENT AND THE GRENADINES',
                  WSM: 'SAMOA',
                  SMR: 'SAN MARINO',
                  STP: 'SAO TOME AND PRINCIPE',
                  SAU: 'SAUDI ARABIA',
                  SEN: 'SENEGAL',
                  SYC: 'SEYCHELLES',
                  SLE: 'SIERRA LEONE',
                  SGP: 'SINGAPORE',
                  SVK: 'SLOVAKIA',
                  SVN: 'SLOVENIA',
                  SLB: 'SOLOMON ISLANDS',
                  SOM: 'SOMALIA',
                  ZAF: 'SOUTH AFRICA',
                  SGS: 'SOUTH GEORGIA AND SOUTH S.S.',
                  ESP: 'SPAIN',
                  LKA: 'SRI LANKA',
                  SHN: 'ST. HELENA',
                  SPM: 'ST. PIERRE AND MIQUELON',
                  SDN: 'SUDAN',
                  SUR: 'SURINAME',
                  SJM: 'SVALBARD AND JAN MAYEN ISLANDS',
                  SWZ: 'SWAZILAND',
                  SWE: 'SWEDEN',
                  CHE: 'SWITZERLAND',
                  SYR: 'SYRIAN ARAB REPUBLIC',
                  TWN: 'TAIWAN, PROVINCE OF CHINA',
                  TJK: 'TAJIKISTAN',
                  TZA: 'UNITED REPUBLIC OF TANZANIA',
                  THA: 'THAILAND',
                  TGO: 'TOGO',
                  TKL: 'TOKELAU',
                  TON: 'TONGA',
                  TTO: 'TRINIDAD AND TOBAGO',
                  TUN: 'TUNISIA',
                  TUR: 'TURKEY',
                  TKM: 'TURKMENISTAN',
                  TCA: 'TURKS AND CAICOS ISLANDS',
                  TUV: 'TUVALU',
                  UGA: 'UGANDA',
                  UKR: 'UKRAINE',
                  ARE: 'UNITED ARAB EMIRATES',
                  GBR: 'UNITED KINGDOM',
                  USA: 'UNITED STATES',
                  UMI: 'U.S. MINOR ISLANDS',
                  URY: 'URUGUAY',
                  UZB: 'UZBEKISTAN',
                  VUT: 'VANUATU',
                  VEN: 'VENEZUELA',
                  VNM: 'VIET NAM',
                  VGB: 'VIRGIN ISLANDS (BRITISH)',
                  VIR: 'VIRGIN ISLANDS (U.S.)',
                  WLF: 'WALLIS AND FUTUNA ISLANDS',
                  ESH: 'WESTERN SAHARA',
                  YEM: 'YEMEN',
                  YUG: 'Yugoslavia',
                  ZMB: 'ZAMBIA',
                  ZWE: 'ZIMBABWE'};
              var country_name = country_codes[code];
              return country_name;
          }

          

          function sessionLineChartByDateOnChange(data) {
              $('.graph-box').html('<canvas id="chart"></canvas>');
              var labels = [];
              var clicks = [];
              var impressions = [];
              var ctr = [];
              var position = [];
              var val1 = 0;
              var val2 = 0;
              $.each(data, function (key, value) {
                  labels.push(value.keys[0].slice(-2));
                  clicks.push(value.clicks);
                  impressions.push(value.impressions);
                  val1 = parseFloat(value.ctr * 100);
                  ctr.push(val1.toFixed(2));
                  val2 = parseFloat(value.position);
                  position.push(val2.toFixed(1));

              });
              var dataset = [];




              if ($('.analytics_type_checkbox_click').is(":checked"))
              {
                  dataset.push({label: "Clicks",
                      fillColor: "rgba(151,187,205,0.2)",
                      strokeColor: "#4D90FE",
                      pointColor: "rgba(151,187,205,1)",
                      pointStrokeColor: "#fff",
                      pointHighlightFill: "#fff",
                      pointHighlightStroke: "rgba(151,187,205,1)",
                      data: clicks});
              }
              if ($('.analytics_type_checkbox_impression').is(":checked"))
              {
                  dataset.push({label: "Impressions",
                      fillColor: "rgba(151,187,205,0.2)",
                      strokeColor: "#DD4B39",
                      pointColor: "#DD4B39",
                      pointStrokeColor: "#fff",
                      pointHighlightFill: "#fff",
                      pointHighlightStroke: "rgba(151,187,205,1)",
                      data: impressions});
              }
              if ($('.analytics_type_checkbox_ctr').is(":checked"))
              {
                  dataset.push({label: "CTR",
                      fillColor: "rgba(151,187,205,0.2)",
                      strokeColor: "#FF9900",
                      pointColor: "#FF9900",
                      pointStrokeColor: "#fff",
                      pointHighlightFill: "#fff",
                      pointHighlightStroke: "rgba(151,187,205,1)",
                      data: ctr});
              }
              if ($('.analytics_type_checkbox_position').is(":checked"))
              {
                  dataset.push({label: "Position",
                      fillColor: "rgba(151,151,205,0.2)",
                      strokeColor: "#109618",
                      pointColor: "#109618",
                      pointStrokeColor: "#fff",
                      pointHighlightFill: "#fff",
                      pointHighlightStroke: "rgba(151,151,205,1)",
                      data: position});
              }
              var chartData = {
                  labels: labels,
                  datasets: dataset
              };
      //            $('#line-chart-date-holder').html('<canvas id="line-chart-' + metric + '" height="300" width="500"/>');
              var ctx = $("#chart").get(0).getContext("2d");
              var a = $('.graph-box');
              ctx.canvas.width = parseFloat($('.graph-box').width() - 40);
                          ctx.canvas.height = 150;
              var myLineChart = new Chart(ctx).Line(chartData, {animationSteps: 50,
                  tooltipYPadding: 16,
                  tooltipXPadding: 30,
                  tooltipCornerRadius: 0,
                  tooltipTitleFontStyle: 'normal',
                  tooltipFillColor: '#3D4049',
                  animationEasing: 'easeOutBounce',
                  scaleLineColor: '#fff',
                  scaleFontSize: 10,
                  scaleShowVerticalLines: false,
                  scaleShowHorizontalLines: false,
                  pointDot: false,
                  bezierCurve: false,
                  scaleFontColor: "#F9F9F9",
                  multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"});

                      }
                      function sessionLineChartByDate() {


                          $('.graph-box').html('<canvas id="chart"></canvas>');
                          var data = '<?php echo json_encode($date->rows); ?>';
                          var dataObject = JSON.parse(data);
                          var labels = [];
                          var clicks = [];
                          var impressions = [];
                          var ctr = [];
                          var position = [];
                          var val1 = 0;
                          var val2 = 0;
                          $.each(dataObject, function (key, value) {
                              labels.push(value.keys[0].slice(-2));
                              clicks.push(value.clicks);
                              impressions.push(value.impressions);
                              val1 = parseFloat(value.ctr * 100);
                              ctr.push(val1.toFixed(2));
                              val2 = parseFloat(value.position);
                              position.push(val2.toFixed(1));

                          });

                          var dataset = [];




                          if ($('.analytics_type_checkbox_click').is(":checked"))
                          {
                              dataset.push({label: "Clicks",
                                  fillColor: "rgba(151,187,205,0.2)",
                                  strokeColor: "#4D90FE",
                                  pointColor: "rgba(151,187,205,1)",
                                  pointStrokeColor: "#fff",
                                  pointHighlightFill: "#fff",
                                  pointHighlightStroke: "rgba(151,187,205,1)",
                                  data: clicks});
                          }
                          if ($('.analytics_type_checkbox_impression').is(":checked"))
                          {
                              dataset.push({label: "Impressions",
                                  fillColor: "rgba(151,187,205,0.2)",
                                  strokeColor: "#DD4B39",
                                  pointColor: "#DD4B39",
                                  pointStrokeColor: "#fff",
                                  pointHighlightFill: "#fff",
                                  pointHighlightStroke: "rgba(151,187,205,1)",
                                  data: impressions});
                          }
                          if ($('.analytics_type_checkbox_ctr').is(":checked"))
                          {
                              dataset.push({label: "CTR",
                                  fillColor: "rgba(151,187,205,0.2)",
                                  strokeColor: "#FF9900",
                                  pointColor: "#FF9900",
                                  pointStrokeColor: "#fff",
                                  pointHighlightFill: "#fff",
                                  pointHighlightStroke: "rgba(151,187,205,1)",
                                  data: ctr});
                          }
                          if ($('.analytics_type_checkbox_position').is(":checked"))
                          {
                              dataset.push({label: "Position",
                                  fillColor: "rgba(151,151,205,0.2)",
                                  strokeColor: "#109618",
                                  pointColor: "#109618",
                                  pointStrokeColor: "#fff",
                                  pointHighlightFill: "#fff",
                                  pointHighlightStroke: "rgba(151,151,205,1)",
                                  data: position});
                          }
                          var chartData = {
                              labels: labels,
                              datasets: dataset
                          };
      //            $('#line-chart-date-holder').html('<canvas id="line-chart-' + metric + '" height="300" width="500"/>');
                          var ctx = $("#chart").get(0).getContext("2d");
                          var a = $('.graph-box');
                          ctx.canvas.width = parseFloat($('.graph-box').width() - 40);
                          ctx.canvas.height = 150;
                          var myLineChart = new Chart(ctx).Line(chartData, {animationSteps: 2,
                              tooltipYPadding: 16,
                              tooltipXPadding: 30,
                              tooltipCornerRadius: 0,
                              tooltipTitleFontStyle: 'normal',
                              tooltipFillColor: '#3D4049',
                              animationEasing: 'easeOutBounce',
                              scaleLineColor: '#fff',
                              scaleFontSize: 10,
                              scaleShowVerticalLines: false,
                              scaleShowHorizontalLines: false,
                              pointDot: false,
                              scaleFontColor: "#F9F9F9",
                              bezierCurve: false,
                              tooltipTemplate: "<% if (datasetLabel) { %> <%= datasetLabel %> - <% } %><%= value %>",
                                          multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"});

                                              }





      </script>      
		
      <script>
        $('#circle').circleProgress({
          value: <?php echo $avg_ctr_value; ?>,
          size: 130,
          fill: {
            gradient: ["#3ecfff", "#37a2ff"]
          }
        }).on('circle-animation-progress', function(event, progress) {
		    $(this).find('strong').html(<?php echo $avg_ctr_num; ?> + '%');
		  });
      </script>