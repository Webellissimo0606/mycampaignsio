<?php
if( ! isset( $current_page ) ){
    global $current_page;
    $current_page = 'business-overview';
}

$domain_data = $this->session->get_userdata();

$prev_12_months_vs_now_data = array(
    array( 'period' => 'Sun', 'iphone' => 10,  'ipad' => 80,  'itouch' => 20  ),
    array( 'period' => 'Mon', 'iphone' => 130, 'ipad' => 100, 'itouch' => 80  ),
    array( 'period' => 'Tue', 'iphone' => 80,  'ipad' => 30,  'itouch' => 70  ),
    array( 'period' => 'Wed', 'iphone' => 70,  'ipad' => 200, 'itouch' => 140 ),
    array( 'period' => 'Thi', 'iphone' => 180, 'ipad' => 50,  'itouch' => 140 ),
    array( 'period' => 'Fri', 'iphone' => 105, 'ipad' => 170, 'itouch' => 80  ),
    array( 'period' => 'Sat', 'iphone' => 250, 'ipad' => 150, 'itouch' => 200 ),
);

$near_upcoming_invoices_data = array(
    array(
        "label" => "My First dataset",
        "data" => array( 
            "January" => 10,
            "February" => 30,
            "March" => 80,
            "April" => 61,
            "May" => 26,
            "June" => 75,
            "July" => 40
        )
    ),
    array("label" => "My Second dataset",
        "data" => array(
            "January" => 28,
            "February" => 48,
            "March" => 40,
            "April" => 19,
            "May" => 86,
            "June" => 27,
            "July" => 90
        )
    ),
    array(
        "label" => "My Third dataset",
        "data" => array(
            "January" => 8,
            "February" => 28,
            "March" => 50,
            "April" => 29,
            "May" => 76,
            "June" => 77,
            "July" => 40
        )
    ),
);
?>

<?php require FCPATH . '/frontend/site/default/new-ui/parts/top.php';?>

<div class="content-row business-overview-top-row">
    
    <div class="content-column w-100 w-50-m w-25-l">

        <div class="content-column-main">

            <div class="content-column-inner">
                
                <div class="relative dt w-100">
                    <div class="dtc w-50 v-mid">
                        <h3>Bank Balance</h3>
                        <p>R12344</p>
                    </div>
                    <div class="dtc w-50 v-mid tr">
                        <i class="material-icons">account_balance</i>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="content-column w-100 w-50-m w-25-l">

        <div class="content-column-main">

            <div class="content-column-inner">
                
                <div class="relative dt w-100">
                    <div class="dtc w-50 v-mid">
                        <h3>Debtors Due</h3>
                        <p>R12344</p>
                    </div>
                    <div class="dtc w-50 v-mid tr">
                        <i class="material-icons">attach_money</i>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="content-column w-100 w-50-m w-25-l">

        <div class="content-column-main">

            <div class="content-column-inner">
                
                <div class="relative dt w-100">
                    <div class="dtc w-50 v-mid">
                        <h3>Creditors Due</h3>
                        <p>R12344</p>
                    </div>
                    <div class="dtc w-50 v-mid tr">
                        <i class="material-icons">attach_money</i>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="content-column w-100 w-50-m w-25-l">

        <div class="content-column-main">

            <div class="content-column-inner">
                
                <div class="relative dt w-100">
                    <div class="dtc w-50 v-mid">
                        <h3>Sales This Month</h3>
                        <p>R12344</p>
                    </div>
                    <div class="dtc w-50 v-mid tr">
                        <i class="material-icons">insert_chart</i>
                    </div>
                </div>

            </div>

        </div>
     
    </div>
</div>

<div class="content-row business-overview-second-row relative">
    
    <div class="content-column w-100 w-50-l">
        <div class="content-column-main">

            <div class="title">
                <div class="left-pos">
                    <h3>PREVIOUS 12 MONTHS INCOME VS CURRENT YEAR</h3>
                </div>
            </div>

            <div class="content-column-inner">
                <div id="prev_12_months_vs_now_chart" class="tc" style="overflow:hidden; min-width:100%; width:100%; max-width:100%;"> </div>
            </div>

        </div>
    </div>

    <div class="content-column w-100 w-50-m w-25-l profit-loss-calendar-col">
        <div class="content-column-main mb1">
            <div class="title">
                <div class="left-pos">
                    <h3>PROFIT AND LOSS</h3>
                </div>
            </div>
            <div class="content-column-inner">

            </div>
        </div>
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>CALENDAR</h3>
                </div>
            </div>
            <div class="content-column-inner tc">
                <div class="jquery-calendar dib"></div>
                <br/>
                <input class="date-picker dib mt3 ph3 pv2" type="text" value="<?php echo date('Y-m-d'); ?>"> 
            </div>
        </div>
    </div>

    <div class="content-column w-100 w-50-m w-25-l">
        <div class="content-column-main">

            <div class="title">
                <div class="left-pos">
                    <h3>GOOGLE SEARCH CONSOLE</h3>
                </div>
            </div>

            <div class="content-column-inner rmv-left-padd rmv-right-padd">
                
                <br/>

                <div class="circle-stat">
                    <div class="aspect-ratio aspect-ratio--1x1">
                        <div class="dt aspect-ratio--object">
                            <div class="dtc tc v-mid"><span>n/a<small>%</small></span>AVERAGE <br/>CLICK THROUGH</div>
                        </div>
                    </div>
                </div>

                <div class="inline-stat-num">
                    <span class="stat-num campaignsio-admin-green">n/a</span>
                    <span class="stat-label">Total Clicks</span>
                </div>

                <div class="inline-stat-num">   
                    <span class="stat-num campaignsio-admin-yellow">n/a</span>
                    <span class="stat-label">Total Impressions</span>
                </div>

            </div>

        </div>
    </div>

</div>

<div class="content-row">

    <div class="content-column w-100 w-70-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos"><h3>UPCOMING INVOICES</h3></div>
            </div>
            <div class="content-column-inner">
                <table class="filter-table list-table mv3 collapse tc">
                    <thead>
                        <tr>
                            <th>CLIENT</th>
                            <th>DUE</th>
                            <th>INVOICED ON</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>2017-10-10</td>
                            <td>2017-10-10</td>
                            <td>111</td>
                            <td>Paid</td>
                        </tr>
                        <tr>
                            <td>Henry Den</td>
                            <td>2017-10-10</td>
                            <td>2017-10-10</td>
                            <td>111</td>
                            <td>Paid</td>
                        </tr>
                        <tr>
                            <td>Henry Snow</td>
                            <td>2017-10-10</td>
                            <td>2017-10-10</td>
                            <td>111</td>
                            <td>Paid</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="content-column w-100 w-30-l">
        <div class="content-column-main no-bg">
            <div class="content-column-inner3" style="padding:1rem 0.5rem 0;">
                <canvas id="near_upcoming_invoices_chart" height="360"></canvas>
            </div>
        </div>
    </div>

</div>

<input type="hidden" name="is_business_overview_page" value="1" />
<input type="hidden" name="prev_12_months_vs_now_data" value="<?php echo html_escape( json_encode( $prev_12_months_vs_now_data ) ) ; ?>" />
<input type="hidden" name="near_upcoming_invoices_data" value="<?php echo html_escape( json_encode( $near_upcoming_invoices_data ) ) ; ?>" />

<?php require FCPATH . '/frontend/site/default/new-ui/parts/bottom.php'; ?>
