<div class="content-row">
                    
	<div class="content-column w-100 w-two-thirds-l">

		<div class="content-column-main">
		    
		    <div class="title">
		        <div class="left-pos"><h3>LIST HEAPMAPS</h3></div>
                <div class="right-pos">
                    <a href="<?php echo $heatmaps['url']; ?>" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 mr2 br1"><i class="material-icons white">&#xE241;</i><small class="white">VIEW HEATMAPS</small></a>
                    <a href="<?php echo base_url( 'domains/' . $domain['id'] . '/heatmaps/add' ); ?>" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD HEATMAP</small></a>
                </div>
		    </div>

		    <div class="content-column-inner">

		    	<div class="list-table-wrap">

                	<table data-table-id="domain-heatmaps-list" class="filter-table list-table mv3 collapse tc">
                		<thead>
                			<tr>
                				<th class="tl">PAGE</th>
                                <th>URL</th>
                				<th>ADDED</th>
                				<th>ACTION</th>
                			</tr>
                		</thead>
                		<tbody>
                			<?php
                			if( empty( $heatmaps['list_data'] ) ){ ?>
	                			<tr>
	                                <td colspan="4">No pages added</td>
	                           	</tr><?php
                			}
                			else{
                				foreach( $heatmaps['list_data'] as $k => $v ){ ?>
                					<tr>
		                				<td class="tl"><?php echo $v['page_name']; ?></td>
		                                <td style="max-width:25rem;"><a href="<?php echo $v['embed_url']; ?>" title="" target="_blank" class="white link mw-100 dib" style="text-overflow:ellipsis; white-space: nowrap; overflow: hidden;"><?php echo $v['embed_url']; ?></a></td>
		                                <td><?php echo date('d M Y H:i',strtotime($v['created'])); ?></td>
		                                <td class="domain-actions">
		                					<a href="<?php echo base_url('domains/' . $domain['id'] . '/heatmaps/' . $v['id'] . '/edit'); ?>" title="Edit Heatmap"><i class="material-icons">&#xE3C9;</i></a>
		                					<a href="<?php echo base_url('domains/' . $domain['id'] . '/heatmaps/' . $v['id'] . '/delete'); ?>" class="delete-domain-heatmap" title="Delete Heatmap"><i class="material-icons">&#xE92B;</i></a>
		                				</td>
		                			</tr><?php
                				}
                			}
                			?>
                		</tbody>
                	</table>

            	</div>

		    </div>

		</div>

	</div>

</div>