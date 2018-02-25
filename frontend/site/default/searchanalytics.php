<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

<style>
    .date-boxx {
        background-color: #363941;
        padding: 8px 10px 9px 10px;

    }
    .date-text {
        color: #fff;
        font-size: larger;
    }
    .graph-box{
        text-align: center;
        height: 300px;
        padding:30px;
        background: #33373a none repeat scroll 0 0;
    }
</style>
<div class="page-container">

    <div class="page-content">
        <div class="page-content-inner">

        </div>
        <div class="page-header">
            <div class="page-title profile-page-title" style="color: #363941">

                <h2>Search Analytics</h2>


            </div>
            <div class="row">
                <form id="options" method="post">
                    <div class="col-md-12 query-box">



                        <div class="analytics_type col-md-6">
                            <label><input class="analytics_type_checkbox analytics_type_checkbox_click" type="checkbox"  value="clicks" checked />Clicks</label>
                            <label><input class="analytics_type_checkbox analytics_type_checkbox_impression" type="checkbox" value="impressions">Impressions</label>
                            <label><input class="analytics_type_checkbox analytics_type_checkbox_ctr" type="checkbox" value="ctr">CTR</label>
                            <label><input class="analytics_type_checkbox analytics_type_checkbox_position" type="checkbox" value="position">Position</label>
                        </div>
                        <div class="col-md-3 date-boxx">
                            <div class="date-text">From Date </div>
                            <input type="text" class="form-control " id="startDate" />
                        </div>
                        <div class="col-md-3 date-boxx">
                            <div class="date-text">To Date </div>
                            <input type="text" class="form-control " id="endDate" />
                        </div>
                    </div>

                    <div class="dimensions_type col-md-12">
                        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="query" checked>Queries</label>
                        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="page">Pages</label>
                        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="country">Countries</label>
                        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="device">Devices</label>
                        <label><input class="dimensions_type_radio"  name="dimensions_type" type="radio" value="search_type">Search Type</label>
                        <span>  <select name="search-type" class="search-type">
                                <option value="web">Web</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select></span>
                    </div>
                </form>
                <div class="total-box">
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

                        <div class="clicks-total">
                            Total Clicks<div><?php echo $clicks ?></div>
                        </div>
                        <div class="impressions-total">
                            Total Impressions<div><?php echo $impressions ?></div>
                        </div>
                        <div class="ctr-total">
                            Average CTR<div><?php echo $avg_ctr ?></div>
                        </div>
                        <div class="position-total">
                            Average Position<div><?php echo $avg_position ?></div>
                        </div>
                    </div>
                <?php } else { ?>
                    <h2>No Data Available.</h2>
                <?php }
                ?>
                <div class="graph-box">

                </div>
                <div class="table-box">
                    <div class="table-responsive">
                        <?php if (!empty($query)) { ?>
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="queries" align="left">Queries </th>
                                        <th class="clicks">Clicks</th>
                                        <th class="impressions">Impressions</th>
                                        <th class="ctr">CTR</th>
                                        <th class="position">Position</th>
                                    </tr>
                                </thead>
    <!--                                <tbody>
                                <?php foreach ($query->rows as $key => $value) { ?>
                                                                                <tr>
                                                                                    <td class="queries"><?php echo $value->keys[0] ?></td>
                                                                                    <td class="clicks"><?php echo $value->clicks ?></td>
                                                                                    <td class="impressions"><?php echo $value->impressions ?></td>
                                                                                    <td class="ctr"><?php
                                    $ctr = $value->ctr * 100;
                                    echo (float) number_format($ctr, 2, ".", "") . '%';
                                    ?></td>
                                                                                    <td class="position"><?php echo (float) number_format($value->position, 1, ".", "") ?></td>
                                                                                </tr>
                                <?php }
                                ?>
                                </tbody>-->
                            </table>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php 
$this->load->view(get_template_directory() . 'footer_new');
 ?>
<script type="text/javascript">
    $(document).ready(function () {
        sessionLineChartByDate();
        var startDate = '<?php echo $startDate ?>';
        var endDate = '<?php echo $endDate ?>';
        $('#startDate').datetimepicker({defaultDate: startDate,
            format: "DD/MM/YYYY"});
        $('#endDate').datetimepicker({defaultDate: endDate,
            format: "DD/MM/YYYY"});


        $('.impressions').css('display', 'none');
        $('.ctr').css('display', 'none');
        $('.position').css('display', 'none');

        $('.analytics_type').click(function () {
            if ($('.analytics_type_checkbox_click').is(":checked"))
            {
                $('.clicks').css('display', '');
            } else {
                $('.clicks').css('display', 'none');
            }
            if ($('.analytics_type_checkbox_impression').is(":checked"))
            {
                $('.impressions').css('display', '');
            } else {
                $('.impressions').css('display', 'none');
            }
            if ($('.analytics_type_checkbox_ctr').is(":checked"))
            {
                $('.ctr').css('display', '');
            } else {
                $('.ctr').css('display', 'none');
            }
            if ($('.analytics_type_checkbox_position').is(":checked"))
            {
                $('.position').css('display', '');
            } else {
                $('.position').css('display', 'none');
            }

            sessionLineChartByDate();
        });
        ///change
        function clickHandler() {
            $('.analytics_type').click();
        }

        var oTable = $('#datatable').DataTable({
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
        $('.dimensions_type').click(function () {
            
            $('th.queries').text($('input[type="radio"]:checked', '#options').val());
            if ($('input[type="radio"]:checked', '#options').val() == 'search_type') {
                $('.table-box').css('display', 'none')
            } else {
                var dimension = $('input[type="radio"]:checked', '#options').val();
                var searchType = $('.search-type').val();
                console.log(dimension);
                searchAnalyticsByDimensionType(dimension, searchType, oTable);
                //$('.table-responsive').html('');
                $('.table-box').css('display', '');

            }
        });

        $('#endDate').on("dp.change", function (e) {
            var dimension = $('input[type="radio"]:checked', '#options').val();
            var searchType = $('.search-type').val();
            searchAnalyticsByDimensionType(dimension, searchType, oTable);

        });

        $("#startDate").on("dp.change", function (e) {
            var dimension = $('input[type="radio"]:checked', '#options').val();
            var searchType = $('.search-type').val();
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


    });

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

                    $('.clicks-total').html("Total Clicks<div>" + clicks + "</div>");
                    $('.impressions-total').html("Total Impressions<div>" + impressions + "</div>");
                    $('.ctr-total').html("Average CTR<div>" + avg_ctr + "</div>");
                    $('.position-total').html("Average Position<div>" + avg_postition + "</div>");

                    sessionLineChartByDateOnChange(date);
                } else {
                    $('.table-box').html("<h2>No Data Available</h2>");
                    $('.total-box').html("");
                    $('.graph-box').html("");
                }



            }
        });
    }

    function searchAnalyticsAPI() {

//        var url = '<?php // echo urlencode($url);                        ?>';
//        var accessToken = '<?php // echo $access_token                        ?>';
//        var dimensions = ["query", "page", "country"];
//        var params = {
//            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
//            "searchType": "web",
//            "startDate": "2016-02-06",
//            "endDate": "2016-02-08"
//        };




//        $.ajax({
//            type: "POST",
//            url: "https://www.googleapis.com/webmasters/v3/sites/" + url + "/searchAnalytics/query?fields=rows&access_token=" + accessToken,
//                    dataType: 'json',
//            contentType: 'application/json',
//            data: {params},
//            success: function (data) {
//                console.log(data);
//            }
//        });


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
                    ctx.canvas.height = 250;
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
                    ctx.canvas.height = 250;
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
                        scaleFontColor: "#F9F9F9",
                        bezierCurve: false,
                        tooltipTemplate: "<% if (datasetLabel) { %> <%= datasetLabel %> - <% } %><%= value %>",
                                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"});

                                        }

</script>