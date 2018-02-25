<?php
global $active_content_nav_items;
$active_content_nav_items = 'analytics';
?>
<?php require 'parts/top.php'; ?>
<?php

$domain_data = $this->session->get_userdata();
$domain_user_id = $domain_data['user_id'];
$domain_id = $domain_data['domainId'];

$new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );

$visits = array( "payload" => array() );

// $visits_data = array(
//   "status" => "success",
//   "payload" => array(
//     "totalUniqueVisitors" => 342,
//     "totalVisitors" => 482,
//     "totalPagePerVisit" => "1433"
//   )
// );

$visits_data = $new_analytics_handler->get_visits( $domain_user_id, $domain_id );

if( 'success' === $visits_data['status'] ){
  $visits = $visits_data['payload'];
}

$summ_total = array(
  'unique_visitors' => isset( $visits_data["payload"]["totalUniqueVisitors"] ) && $visits_data["payload"]["totalUniqueVisitors"] ? $visits_data["payload"]["totalUniqueVisitors"] : 'n/a',
  'visitors' => isset( $visits_data["payload"]["totalVisitors"] ) && $visits_data["payload"]["totalVisitors"] ? $visits_data["payload"]["totalVisitors"] : 'n/a',
  'page_views' => isset( $visits_data["payload"]["totalPagePerVisit"] ) && $visits_data["payload"]["totalPagePerVisit"] ? $visits_data["payload"]["totalPagePerVisit"] : 'n/a',
);

$total_visits = array();
$total_visits_data = $new_analytics_handler->get_total_visits( $domain_user_id, $domain_id );
/*$total_visits_data = array(
  'status' => 'success',
  'payload' => array(
    'totalVisitsGraph' => array(
      '2017-09-04' => 23,
      '2017-09-05' => 28,
      '2017-09-06' => 26,
      '2017-09-07' => 8,
      '2017-09-08' => 16,
      '2017-09-09' => 1,
      '2017-09-10' => 31,
      '2017-09-11' => 26,
      '2017-09-12' => 15,
      '2017-09-13' => 18,
      '2017-09-14' => 12,
      '2017-09-15' => 10,
      '2017-09-16' => 13,
      '2017-09-17' => 11,
      '2017-09-18' => 32,
      '2017-09-19' => 11,
      '2017-09-20' => 3,
      '2017-09-21' => 0,
      '2017-09-22' => 5,
      '2017-09-23' => 0,
      '2017-09-24' => 1,
      '2017-09-25' => 43,
      '2017-09-26' => 19,
      '2017-09-27' => 20,
      '2017-09-28' => 4,
      '2017-09-29' => 9,
      '2017-09-30' => 18,
      '2017-10-01' => 8,
      '2017-10-02' => 29,
      '2017-10-03' => 21,
      '2017-10-04' => 18 
    ) 
  ) 
);*/
if( 'success' === $total_visits_data['status'] ){
  $total_visits = $total_visits_data['payload']['totalVisitsGraph'];
}

$unique_visits = array();
$unique_visits_data = $new_analytics_handler->get_unique_visits( $domain_user_id, $domain_id );
/*$unique_visits_data = array(
  'status' => 'success',
  'payload' => array(
    'uniqueVisitorsGraph' => array(
      '2017-09-04' => 23,
      '2017-09-05' => 28,
      '2017-09-06' => 26,
      '2017-09-07' => 8,
      '2017-09-08' => 16,
      '2017-09-09' => 1,
      '2017-09-10' => 31,
      '2017-09-11' => 26,
      '2017-09-12' => 15,
      '2017-09-13' => 18,
      '2017-09-14' => 12,
      '2017-09-15' => 10,
      '2017-09-16' => 13,
      '2017-09-17' => 11,
      '2017-09-18' => 32,
      '2017-09-19' => 11,
      '2017-09-20' => 3,
      '2017-09-21' => 0,
      '2017-09-22' => 5,
      '2017-09-23' => 0,
      '2017-09-24' => 1,
      '2017-09-25' => 43,
      '2017-09-26' => 19,
      '2017-09-27' => 20,
      '2017-09-28' => 4,
      '2017-09-29' => 9,
      '2017-09-30' => 18,
      '2017-10-01' => 8,
      '2017-10-02' => 29,
      '2017-10-03' => 21 ,
      '2017-10-04' => 18 
    )
  )
);*/
if( 'success' === $unique_visits_data['status'] ){
  $unique_visits = $unique_visits_data['payload']['uniqueVisitorsGraph'];
}

$page_per_visit = array();
$page_per_visit_data = $new_analytics_handler->get_page_per_visit( $domain_user_id, $domain_id );
/*$page_per_visit_data = array(
  'status' => 'success',
  'payload' => array(
    'totalPagePerVisitGraph' => array(
      '2017-09-04' => 23,
      '2017-09-05' => 28,
      '2017-09-06' => 26,
      '2017-09-07' => 8,
      '2017-09-08' => 16,
      '2017-09-09' => 1,
      '2017-09-10' => 31,
      '2017-09-11' => 26,
      '2017-09-12' => 15,
      '2017-09-13' => 18,
      '2017-09-14' => 12,
      '2017-09-15' => 10,
      '2017-09-16' => 13,
      '2017-09-17' => 11,
      '2017-09-18' => 32,
      '2017-09-19' => 11,
      '2017-09-20' => 3,
      '2017-09-21' => 0,
      '2017-09-22' => 5,
      '2017-09-23' => 0,
      '2017-09-24' => 1,
      '2017-09-25' => 43,
      '2017-09-26' => 19,
      '2017-09-27' => 20,
      '2017-09-28' => 4,
      '2017-09-29' => 9,
      '2017-09-30' => 18,
      '2017-10-01' => 8,
      '2017-10-02' => 29,
      '2017-10-03' => 21,
      '2017-10-04' => 20
    )
  )
);*/
if( 'success' === $page_per_visit_data['status'] ){
  $page_per_visit = $page_per_visit_data['payload']['totalPagePerVisitGraph'];
}

$referrer_visits = array();
$referrer_visits_graph_array = array();
$referrer_visits_data = $new_analytics_handler->get_referrer_visits( $domain_user_id, $domain_id );
/*$referrer_visits_data = array(
  'status' => 'success',
  'payload' => array(
    'referrervisit' => array(
      array ( 'label' => 'direct', 'nb_visits' => 146 ),
      array ( 'label' => 'referral', 'nb_visits' => 62 ),
      array ( 'label' => 'organic', 'nb_visits' => 273 )
    ),
    'referrervisitgraph' => array(
      '2016-10-01' => array (
        array( 'label' => 'direct', 'nb_visits' => 47 ),
        array( 'label' => 'organic', 'nb_visits' => 190 ),
        array( 'label' => 'referral', 'nb_visits' => 72 )
      ),
      '2016-11-01' => array(
        array( 'label' => 'direct', 'nb_visits' => 106 ),
        array( 'label' => 'organic', 'nb_visits' => 234 ),
        array( 'label' => 'referral', 'nb_visits' => 148 )
      ),
      '2016-12-01' => array(
        array( 'label' => 'direct', 'nb_visits' => 334 ),
        array( 'label' => 'organic', 'nb_visits' => 171 ),
        array( 'label' => 'referral', 'nb_visits' => 144 ) 
      )
    )
  ) 
);*/
if( 'success' === $referrer_visits_data['status'] ){
  $referrer_visits = $referrer_visits_data['payload']['referrervisit'];
  $referrer_visits_graph_array = $referrer_visits_data['payload']['referrervisitgraph'];
}

$referrer_visits_summary = 0;
foreach ($referrer_visits as $key => $val) {
  $referrer_visits_summary += $val['nb_visits'];
}
$referrer_visits_array = array();
foreach ($referrer_visits as $key => $val) {
  $referrer_visits_array[] = array( 'x' => $val['label'], 'y' => round( ( $val['nb_visits'] * 100 ) / $referrer_visits_summary , 2 ) ) ;
}

$top_countries = array();
$top_countries_data = $new_analytics_handler->get_top_countries( $domain_user_id, $domain_id );
/*$top_countries_data = array(
  'status' => 'success',
  'payload' => array(
    'topcountries' => array(
      array ( 'label' => 'United Kingdom', 'nb_visits' => 300 ),
      array ( 'label' => 'United States', 'nb_visits' => 77 ),
      array ( 'label' => 'India', 'nb_visits' => 40 ),
      array ( 'label' => 'Ireland', 'nb_visits' => 15 ),
      array ( 'label' => 'Sweden', 'nb_visits' => 13 ),
      array ( 'label' => 'Germany', 'nb_visits' => 3 ),
      array ( 'label' => 'France', 'nb_visits' => 2 ),
      array ( 'label' => 'Hong Kong', 'nb_visits' => 2 ),
      array ( 'label' => 'Mexico', 'nb_visits' => 2 ),
      array ( 'label' => 'Nepal', 'nb_visits' => 2 ),
      array ( 'label' => 'Russia', 'nb_visits' => 2 ),
      array ( 'label' => 'Tunisia', 'nb_visits' => 2 ),
      array ( 'label' => 'Ukraine', 'nb_visits' => 2 ),
      array ( 'label' => 'Algeria', 'nb_visits' => 1 ),
      array ( 'label' => 'Australia', 'nb_visits' => 1 )
      ) 
    ) 
  );*/
if( 'success' === $top_countries_data['status'] ){
  $top_countries = $top_countries_data['payload']['topcountries'];
}


$top_sources = array();
$top_sources_data = $new_analytics_handler->get_visit_sources( $domain_user_id, $domain_id );
/*$top_sources_data = Array ( 
  'status' => 'success',
  'payload' => array( 
    'sites' => array (
      array ( 'label' => 'google', 'nb_visits' => 265 ),
      array ( 'label' => 'direct', 'nb_visits' => 146 ),
      array ( 'label' => 'm.facebook.com', 'nb_visits' => 20 ),
      array ( 'label' => 'ahrefs.com', 'nb_visits' => 10 ),
      array ( 'label' => 'pinterest.co.uk', 'nb_visits' => 7 ),
      array ( 'label' => 'bing', 'nb_visits' => 5 ),
      array ( 'label' => 'lm.facebook.com', 'nb_visits' => 5 ),
      array ( 'label' => 'l.facebook.com', 'nb_visits' => 4 ),
      array ( 'label' => 'yahoo', 'nb_visits' => 3 ),
      array ( 'label' => 'facebook.com', 'nb_visits' => 2 ),
      array ( 'label' => 'irish-wedding-photography10494.ezblogz.com', 'nb_visits' => 2 ),
      array ( 'label' => '1stopweddingshop.net', 'nb_visits' => 1 ),
      array ( 'label' => 'bridalprepvideosamples24195.diowebhost.com', 'nb_visits' => 1 ),
      array ( 'label' => 'dream-weddingphotos.co.uk', 'nb_visits' => 1 ),
      array ( 'label' => 'l.messenger.com', 'nb_visits' => 1 )
    ) 
  ) 
);*/

if( 'success' === $top_sources_data['status'] ){
  $top_sources = $top_sources_data['payload']['sites'];
}

if( 1 === (int) $domain_user_id ){

       // $visits = $new_analytics_handler->get_visits( $domain_user_id, $domain_id );
       // var_dump( $visits );

//     $visits = $new_analytics_handler->get_visits( $domain_user_id, $domain_id );
    // $total_visits = $new_analytics_handler->get_total_visits( $domain_user_id, $domain_id );
    // $unique_visits = $new_analytics_handler->get_unique_visits( $domain_user_id, $domain_id );
    // $page_per_visit = $new_analytics_handler->get_page_per_visit( $domain_user_id, $domain_id );
    // $referrer_visits = $new_analytics_handler->get_referrer_visits( $domain_user_id, $domain_id );
//     $top_countries = $new_analytics_handler->get_top_countries( $domain_user_id, $domain_id );
//     $top_sources = $new_analytics_handler->get_visit_sources( $domain_user_id, $domain_id );

//     echo '$total_visits';
//     echo '<br/><br/>';
    // print_r( $total_visits );
    // echo '<br/><br/>';
    // echo '$unique_visits';
    // echo '<br/><br/>';
    // print_r( $unique_visits );
    // echo '<br/><br/>';
    // echo '$page_per_visit';
    // echo '<br/><br/>';
    // print_r( $page_per_visit );
    // echo '<br/><br/>';
    // echo '$referrer_visits';
    // echo '<br/><br/>';
    // print_r( $referrer_visits );
    // echo '<br/><br/>';
//     echo '$top_countries';
//     echo '<br/><br/>';
//     print_r( $top_countries );
//     echo '<br/><br/>';
//     echo '$top_sources';
//     echo '<br/><br/>';
//     print_r( $top_sources );
//     echo '<br/><br/>';
}
?>
<div class="content-row">

  <div class="content-column w-100 w-third-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>TOTAL VISITS</h3>
            </div>
            <div class="right-pos">
                  <span class="dib f5 fw5 ph2 br1 campaignsio-admin-green"><?php echo $summ_total['visitors']; ?></span>
              </div>
          </div>

          <div class="content-column-inner">
              <div class="aspect-ratio aspect-ratio--16x9">
              <div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f5 f4-ns">
                <span class="dtc v-mid pa3">
                  <canvas id="totalVisitsChart"></canvas>
                </span>
              </div>
        </div>
          </div>
      </div>
  </div>

  <div class="content-column w-100 w-third-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>UNIQUE VISITORS</h3>
            </div>
            <div class="right-pos">
                  <span class="dib f5 fw5 ph2 br1 campaignsio-admin-green"><?php echo $summ_total['unique_visitors']; ?></span>
              </div>
          </div>

          <div class="content-column-inner">
              <div class="aspect-ratio aspect-ratio--16x9">
                  <div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f5 f4-ns">
                    <span class="dtc v-mid pa3">
                      <canvas id="uniqueVisitsChart"></canvas>
                    </span>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="content-column w-100 w-third-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>PAGE VIEWS</h3>
            </div>
            <div class="right-pos">
                  <span class="dib f5 fw5 br1 campaignsio-admin-green"><?php echo $summ_total['page_views']; ?></span>
              </div>
          </div>

          <div class="content-column-inner">
              <div class="aspect-ratio aspect-ratio--16x9">
              <div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f5 f4-ns">
                <span class="dtc v-mid pa3">
                  <canvas id="pagePerVisitChart"></canvas>
                </span>
              </div>
        </div>
          </div>
      </div>
  </div>

</div>

<div class="content-row">

  <div class="content-column w-100 w-60-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>LAST 12 MONTHS TRAFFIC</h3>
            </div>
          </div>

          <div class="content-column-inner">
              <div class="aspect-ratio aspect-ratio--8x5">
              <div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f4 f3-ns">
                <span class="dtc v-mid pa3">
                  <canvas id="twelveMonthsChart"></canvas>
                </span>
              </div>
        </div>
          </div>
      </div>
  </div>

  <div class="content-column w-100 w-40-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>VISITS</h3>
            </div>
          </div>

          <div class="content-column-inner">
              <div class="aspect-ratio aspect-ratio--1x1">
              <div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f4 f3-ns">
                <span class="dtc v-mid pa3">
                  <canvas id="visitsChart" style="max-height:96% !important;"></canvas>
                </span>
              </div>
        </div>
          </div>
      </div>
  </div>

</div>

<div class="content-row">

  <div class="content-column w-100 w-50-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>TOP COUNTRIES (VISITS)</h3>
            </div>
          </div>

          <div class="content-column-inner">

            <div class="list-table-wrap" style="padding-bottom:0;">
              
                <table class="list-table collapse tc">
                <thead>
                  <tr>
                      <th class="tl">COUNTRY</th>
                      <th>VISITS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if( ! empty( $top_countries ) ){
                    foreach ($top_countries as $key => $val) {
                      echo '<tr><td class="tl">' . $val['label'] . '</td><td>' . $val['nb_visits'] . '</td></tr>';
                    }
                  } ?>
                  <!-- <tr>
                      <td class="tl">United Kingdom</td>
                      <td>876</td>
                  </tr>
                  <tr>
                      <td class="tl">United States</td>
                      <td>126</td>
                  </tr>
                  <tr>
                      <td class="tl">Ireland</td>
                      <td>43</td>
                  </tr>
                  <tr>
                      <td class="tl">India</td>
                      <td>8</td>
                  </tr>
                  <tr>
                      <td class="tl">(not set)</td>
                      <td>6</td>
                  </tr>
                  <tr>
                      <td class="tl">Australia</td>
                      <td>5</td>
                  </tr> -->
                </tbody>
              </table>

            </div>

          </div>
      </div>
  </div>

  <div class="content-column w-100 w-50-l">
  
      <div class="content-column-main content-col">
          
          <div class="title">
            <div class="left-pos">
              <h3>TOP SOURCES / MEDIUM (VISITS)</h3>
            </div>
          </div>

          <div class="content-column-inner">
              
              <div class="list-table-wrap" style="padding-bottom:0;">

                <table class="list-table collapse tc">
                <thead>
                  <tr>
                      <th class="tl">SOURCE</th>
                      <th>VISITS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if( ! empty( $top_sources ) ){
                    foreach ($top_sources as $key => $val) {
                      echo '<tr><td class="tl">' . $val['label'] . '</td><td>' . $val['nb_visits'] . '</td></tr>';
                    }
                  } ?>
                </tbody>
              </table>

            </div>

          </div>
      </div>
  </div>

</div>

<input type="hidden" name="is_domain_analytics_page" value="1" />

<?php echo form_hidden( 'id_total_visits_data', json_encode( $total_visits ) ); ?>
<?php echo form_hidden( 'id_unique_visits_data', json_encode( $unique_visits ) ); ?>
<?php echo form_hidden( 'id_page_per_visit_data', json_encode( $page_per_visit ) ); ?>
<?php echo form_hidden( 'id_referrer_visits_data', json_encode( $referrer_visits_array ) ); ?>
<?php echo form_hidden( 'id_referrer_visits_graph_data', json_encode( $referrer_visits_graph_array ) ); ?>

<?php require 'parts/bottom.php'; ?>