
 
<div class="content-row">

  <div class="content-column w-100 w-two-thirds-l">
  
      <div class="content-column-main content-col">
          
          <div class="title website-traffic-title">
            <div class="left-pos">
              <h3>WEBSITE TRAFFIC</h3>
            </div>
            <div class="right-pos">
              <a href="<?php echo base_url(); ?>analytics/analytics" title="" class="btn-color f7 no-underline pv1 ph3 br1"><span class="white">VIEW MORE</span></a>
            </div>
          </div>

          <div class="content-column-inner">
            <canvas id="webTrafficChart"></canvas>
          </div>
      </div>
  </div>

  <div class="content-column w-100 w-50-ns w-third-half-l">

    <div class="content-column-main">

      <div class="title">
        <div class="left-pos"><h3>SEO DATA</h3></div>
      </div>

      <div class="content-column-inner rmv-left-padd rmv-right-padd">
        
        <div class="circle-stat">
          <div class="aspect-ratio aspect-ratio--1x1">
            <div class="dt aspect-ratio--object">
              <div class="dtc tc v-mid"><span>n/a</span>TRAFFIC THIS<br>WEEK</div>
            </div>
          </div>
        </div>

        <div class="inline-stat-num">
          <span class="stat-num campaignsio-admin-green month-clicks-val">0</span>
          <span class="stat-label">Clicks this month</span>
        </div>

        <div class="inline-stat-num"> 
          <span class="stat-num campaignsio-admin-yellow moved-up-clicks-val">0</span>
          <span class="stat-label">Moved up</span>
        </div>

        <div class="inline-stat-num">
          <span class="stat-num campaignsio-admin-orange moved-down-clicks-val">0</span>
          <span class="stat-label">Moved down</span>
        </div>

        <div class="inline-stat-num">
          <span class="stat-num campaignsio-admin-action-color no-changes-val">36</span>
          <span class="stat-label">No changes</span>
        </div>
      </div>
    </div>
  </div>

  <div class="content-column w-100 w-50-ns w-third-half-l">

    <div class="content-column-main">
      <div class="title">
        <div class="left-pos"><h3>KEYWORD POSITION</h3></div>
      </div>
      <div class="content-column-inner">
        <div class="bold-stat-num">
          <span class="stat-num campaignsio-admin-green keyword-pos-top-10-val">n/a</span>
          <span class="stat-label">Top 10</span>
        </div>
        <hr>
        <div class="bold-stat-num">
          <span class="stat-num campaignsio-admin-yellow keyword-pos-top-20-val">n/a</span>
          <span class="stat-label">Top 20</span>
        </div>
        <hr>
        <div class="bold-stat-num">
          <span class="stat-num campaignsio-admin-orange keyword-pos-top-50-val">n/a</span>
          <span class="stat-label">Top 50</span>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="content-row">
  
  <div class="content-column w-100 w-two-thirds-l">
      <div class="content-column-main content-col">

          <div class="title">
            <div class="left-pos">
              <h3>GT METRIX</h3>
            </div>
            <div class="right-pos">
                  <a href="#" title="" target="_blank" class="gtm-view-more-link btn-color f7 no-underline pv1 ph3 br1" style="display:none;"><span class="white">VIEW MORE</span></a>
              </div>
          </div>

          <div class="fl w-100 w-40-ns w-30-l">
              <div class="content-column-inner">
                  <img class="br1 gtm-screenshot" />
              </div>
          </div>

          <div class="fl w-100 w-60-ns w-30-l">
              <div class="content-column-inner">
              
                  <h4 class="ma0 f5 fw5">Latest Performance Report for</h4>

                  <a  class="gtmetrix-site-link" href="<?php echo $domain_data['domainUrl']; ?>" target="_blank"><?php echo strtr($domain_data['domainUrl'], array('www.'=>'','https://'=>'','http://'=>'','/'=>'')); ?></a>

                  <div class="report-box mt2">
                      
                      <div class="gtmetrix-report-row">
                          <span><strong>Report Generated on</strong></span>
                          <span class="report_date">n/a</span>
                      </div>
                      
                      <br/>

                      <div class="gtmetrix-report-row">
                          <span><strong>Test Server Region</strong></span>
                          <span>Athens, Greece</span>
                      </div>

                      <br/>

                      <div class="gtmetrix-report-row">
                          <span><strong>Using</strong></span>
                          <div class="gtmetrix-browser-row"><img src="" class="browser-thumb"/>(<i class="browser-desktop-mobile">n/a</i>) <i class="browser-title"></i></div>
                      </div>
                  </div>

              </div>
          </div>

          <div class="fl w-100 w-100-ns w-40-l">

              <div class="fl w-100 w-50-ns">
                  <div class="content-column-inner">

                      <hr class="dn-l" style="margin-top: -1.5rem;"/>

                      <div class="bold-stat-num">
                          <span class="stat-num gtm-load-time"><small><span class="time-val"></span><small class="time-unit">n/a</small></small></span>
                          <span class="stat-label">Load time</span>
                      </div>

                      <hr/>

                      <div class="bold-stat-num">
                          <span class="stat-num gtm-page-size"><small><span class="size-val"></span><small class="size-unit">n/a</small></small></span>
                          <span class="stat-label">Page size</span>
                      </div>

                      <hr/>

                      <div class="bold-stat-num">
                          <span class="stat-num gtm-requests"><small class="requests-val">n/a</small></span>
                          <span class="stat-label">Requests</span>
                      </div>

                  </div>
              </div>

              <div class="fl w-100 w-50-ns">
                  <div class="content-column-inner">

                      <hr class="dn-l" style="margin-top: -1.5rem;"/>

                      <div class="bold-stat-num">
                          <span class="stat-num gtm-page-speed">
                            <small><span class="speed-grade"></span> <small>(<span class="speed-val">n/a</span>)</small></small>
                          </span>
                          <span class="stat-label">Page speed</span>
                      </div>

                      <hr/>

                      <div class="bold-stat-num">
                          <span class="stat-num gtm-y-slow-score"><small><span class="score-grade"></span> <small>(<span class="score-val">n/a</span>)</small></small></span>
                          <span class="stat-label">Y Slow score</span>
                      </div>

                      <hr/>
                  </div>
              </div>

          </div>
      </div>
  </div>

  <div class="content-column w-100 w-third-l">
      <div class="content-column-main content-col">

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

  <div class="content-column w-100 w-50-l">
      <div class="content-column-main content-col">
          <div class="title">
            <div class="left-pos"><h3>DOMAIN UPTIME &amp; PERFORMANCE</h3></div>
            <div class="right-pos"><span class="domain-status white">Status: <span class="br1 campaignsio-admin-bg-green"><small>UP</small></span></span></div>
          </div>
          <div class="content-column-inner">
            <canvas id="responseAndUpTimeChart"></canvas>
          </div>
      </div>
  </div>

  <div class="content-column w-100 w-50-l">
      <div class="content-column-main content-col">
          <div class="title">
            <div class="left-pos"><h3>UPTIME</h3></div>
          </div>
          <div class="content-column-inner">
            <canvas id="uptimeChart"></canvas>
          </div>
      </div>
  </div>

</div>