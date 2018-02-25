 <div class="cf">
     <div class="fl w-100 w-20-l">
         <div class="content-column-inner">
             <span class="f7">
                 DOMAIN
             </span>
             <p>
                 <a href="javascript:void(0);" title="View Overview for <?php echo $domain_data['domainHost'] ?>" target="_blank" class="no-underline underline-hover white f5 fw5">
                    <?php echo $domain_data['domainHost']; ?>
                 </a>
             </p>
         </div>
         <hr class="dn-l" style="margin-top:0; margin-bottom:0;"/>
     </div>
     <div class="fl w-100 w-25-ns w-20-l">
         <div class="content-column-inner">
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-green">
                     <?php echo $keywordstats['top1_latest']; ?>
                 </span>
                 <span class="stat-label">
                     FIRST PLACE
                 </span>
             </div>
             <hr/>
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-green">
                    <?php echo $keywordstats['top3_latest']; ?>
                 </span>
                 <span class="stat-label">
                     IN TOP 3
                 </span>
             </div>
         </div>
         <hr class="dn-ns" style="margin-top:0; margin-bottom:0;"/>
     </div>
     <div class="fl w-100 w-25-ns w-20-l">
         <div class="content-column-inner">
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-yellow">
                    <?php echo $keywordstats['top5_latest']; ?>
                 </span>
                 <span class="stat-label">
                     IN TOP 5
                 </span>
             </div>
             <hr/>
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-yellow">
                   <?php echo $keywordstats['top10_latest']; ?>
                 </span>
                 <span class="stat-label">
                     IN TOP 10
                 </span>
             </div>
         </div>
         <hr class="dn-ns" style="margin-top:0; margin-bottom:0;"/>
     </div>
     <div class="fl w-100 w-25-ns w-20-l">
         <div class="content-column-inner">
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-action-color">
                     <?php echo $keywordstats['top20_latest']; ?>
                 </span>
                 <span class="stat-label">
                     IN TOP 20
                 </span>
             </div>
             <hr/>
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-action-color">
                     <?php echo $keywordstats['top30_latest']; ?>
                 </span>
                 <span class="stat-label">
                     IN TOP 30
                 </span>
             </div>
         </div>
         <hr class="dn-ns" style="margin-top:0; margin-bottom:0;"/>
     </div>
     <div class="fl w-100 w-25-ns w-20-l">
         <div class="content-column-inner">
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-orange">
                     <?php echo $keywordstats['ranked_latest']; ?>
                 </span>
                 <span class="stat-label">
                     RANKED
                 </span>
             </div>
             <hr/>
             <div class="bold-stat-num">
                 <span class="stat-num campaignsio-admin-orange">
                     <?php echo $keywordstats['notranked_latest']; ?>
                 </span>
                 <span class="stat-label">
                     NOT RANKED
                 </span>
             </div>
         </div>
     </div>
 </div>
 <hr/>
 <div class="list-table-wrap">
			         <table class="list-table collapse tc mv3" id="serp_report" >
                         <thead>
                           <tr>
                             <th>KEYWORDS <?php echo $show_date; ?></th>
     						<th>Position</th>
     						<th>GWT</th>
                           </tr>
                         </thead>
                         <tbody>
     						<?php 
     							if (isset($result)):
     						    	foreach ($result as $key1 => $val):
     						?>
     					    <tr>
     					        <td><?php echo $val['keyword']; ?></td>
     					        <td><?php echo $val['position']; ?></td>
     					        <td><?php if($val['type'] == 'GWT')echo 'YES';else echo 'NO'; ?></td>
     					    </tr>
     						<?php 
     							endforeach;
     							endif;
     						?>
     					</tbody>
     	            </table>
 </div>
