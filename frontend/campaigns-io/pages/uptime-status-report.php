<div class="content-row mb2">
    <div class="content-column w-100 ph2 pb2">
        <div class="w-100 dib pb3" style="border-bottom:1px solid rgba(255, 255, 255, 0.1);">

            <form action="./statusreport" method="get" enctype="multipart/form-data" id="frm_status_report" name="frm_status_report">
                <div class="w-100 w-50-ns w-third-l" style="max-width:300px;">
                    <select name="d" class="w-auto fl dib uptime-stats-days">
                        <?php foreach ($days_options as $key => $val) { ?>
                            <option value="<?php echo $val['days']; ?>"<?php if( $val['active'] ){ echo 'selected="selected"';} ?>><?php echo $val['label']; ?></option><?php
                        } ?>
                    </select>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="content-row mb2">
                    
	<div class="content-column w-100">

		<div class="content-column-main">
		    
		    <div class="title">
                <div class="left-pos"><h3>UPTIME RESULTS</h3></div>
            </div>
            
		    <div class="content-column-inner">

		    	<div class="list-table-wrap">
                    
                	<table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th data-sortable="false" style="width:1px;"></th>
                                <th class="tl">DOMAIN</th>
                                <th>STATUS</th>
                                <?php /* ?>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <?php */ ?>
                                <th>UPTIME</th>
                                <th>LOADTIME</th>
                            </tr>
                        </thead>
                		<tbody>
                            <?php
                            if( ! empty( $uptime_data['uptime'] ) ){
                                $counter = 0;
                                foreach( $uptime_data['uptime'] as $key => $up ) {
                                    $counter++; ?>
                        			<tr>
                                        <td><?php echo $counter; ?></td>
                                        <td class="tl"><a href="<?php echo $up['domain_name']; ?>" target="_blank" class="white link"><?php echo $up['domain_name']; ?><a/></td>
                                        <td><div class="dib" style="background-color:#1ac65b;width:16px;height:16px;border-radius:20px;">&nbsp;</div></td>
                        				<?php /* ?>
                                        <td>200</td>
                                        <td><?php echo $up['module']; ?></td>
                                        <?php */ ?>
                                        <?php
                                        foreach( $uptime_data['totaltime'] as $x => $y ){
                                            if( $up['domain_id'] === $y['domain_id'] ){ ?>
                                                <td><?php echo round( ( $up['total_stats'] * 100 ) / $y['total_stats'], 2 ); ?>%</td> <?php
                                                break;
                                            }
                                        } ?>
                                        <td><?php echo round( $up['avg_load_time'] / 1000 , 3); ?> <small>seconds</small></td>
                        			</tr> <?php
                                }
                            }
                            else{ ?>
                                <tr><td colspan="7">No results found</td></tr>
                                <?php
                            } ?>
                		</tbody>
                	</table>

            	</div>
                
            </div>

		</div>

	</div>

</div>

<div class="content-row">
                    
    <div class="content-column w-100">

        <div class="content-column-main">
            
            <div class="title">
                <div class="left-pos"><h3>DOWNTIME RESULTS</h3></div>
            </div>
            
            <div class="content-column-inner">

                <div class="list-table-wrap">
                    
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th data-sortable="false" style="width:1px;"></th>
                                <th class="tl">DOMAIN</th>
                                <th>STATUS</th>
                                <?php /* ?>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <?php */ ?>
                                <th>UPTIME</th>
                                <th>LOADTIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if( ! empty( $uptime_data['downtime'] ) ){
                                $counter = 0;
                                foreach( $uptime_data['downtime'] as $down ) {
                                    $counter++; ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td class="tl"><a href="<?php echo $down['domain_name']; ?>" target="_blank" class="white link"><?php echo $down['domain_name']; ?><a/></td>
                                        <td><div class="dib" style="background-color:#ff6000;width:16px;height:16px;border-radius:20px;">&nbsp;</div></td>
                                        <?php /* ?>
                                        <td>200</td>
                                        <td><?php echo $down['module']; ?></td>
                                        <?php */ ?>
                                        <?php
                                        foreach( $uptime_data['totaltime'] as $x => $y ){
                                            if( $down['domain_id'] === $y['domain_id'] ){ ?>
                                                <td><?php echo round( ( $down['total_stats'] * 100 ) / $y['total_stats'], 2 );?>%</td> <?php
                                                break;
                                            }
                                        } ?>
                                        <td><?php echo round( $down['avg_load_time'] / 1000 , 3); ?> <small>seconds</small></td>
                                    </tr> <?php
                                }
                            }
                            else{ ?>
                                <tr><td colspan="7">No results found</td></tr>
                                <?php
                            } ?>
                        </tbody>
                    </table>

                </div>
                
            </div>

        </div>

    </div>

</div>