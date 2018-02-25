<?php
	// var_dump( $heatmaps['current']['id'] );
?>

<div class="content-row">

	<div class="content-column w-100">
		
		<div class="content-column-main">

			<div class="title">
		        <div class="left-pos"><h3>HEATMAPS</h3></div>
		        <div class="right-pos">
		        	<a href="<?php echo $heatmaps['list_url']; ?>" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 mr2 br1"><i class="material-icons white">&#xE241;</i><small class="white">LIST HEATMAPS</small></a>
		        	<a href="<?php echo base_url( 'domains/' . $domain['id'] . '/heatmaps/add' ); ?>" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD HEATMAP</small></a>
		        </div>
		    </div>

			<div class="content-column-inner">
				<?php if( ! empty( $heatmaps['available'] ) ){ ?>
					<label class="dib fw5 mr2 mb2">Choose Heatmap:</label>
	                <select class="domain-heatmap-select dib pa2">
	                	<?php foreach( $heatmaps['available'] as $k => $v ) { ?>
	                    <option value="<?php echo base_url('domains/' . $domain['id'] . '/heatmaps/' . $v['id']); ?>"<?php echo (int) $heatmaps['current']['id'] === (int) $v['id'] ? ' selected="selected"' : ''; ?>><?php echo $v['page_name']; ?></option>
	                    <?php } ?>
	                </select>
	                <hr/>
                <?php } ?>
				<div class="aspect-ratio aspect-ratio--4x3">
		        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f3 f2-ns">
		        		<?php if( ! empty( $heatmaps['available'] ) ){ ?>
		        		<iframe src="<?php echo $heatmaps['current']['data']['embed_url']; ?>" class="w-100 h-100" style="border:0"></iframe>
		        		<?php } 
		        		else{ ?> <span class="dtc v-mid pa3">None available heatmap</span> <?php } ?>
		        	</div>
				</div>
			</div>

		</div>

	</div>

</div>