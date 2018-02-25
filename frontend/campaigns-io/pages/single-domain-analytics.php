<div class="relative db w-100 ph2 pv1 cf">

	<?php if( isset( $page_data_disabled_ga ) && $page_data_disabled_ga ){ ?>
	<span class="mv3-s ml0-s fl w-100 w-auto-ns fr-ns"><small class="dib mv3 mv0-ns mr2" style="color:#c9c9c9;">Google account is disabled</small> 
		<?php if( isset( $page_data_edit_settings_link ) && $page_data_edit_settings_link ){ ?>
		<a href="<?php echo $page_data_edit_settings_link; ?>" title="" class="dib f7 btn-color no-underline pv2 ph3 br1"><span class="white">EDIT SETTINGS</span></a></span>
		<?php } ?>
	<?php } ?>


	<?php if( isset( $page_data_last_update ) && $page_data_last_update ){ ?>
	<span class="mv3-s ml0-s fl w-100 w-auto-ns fr-ns"><small class="dib mv3 mv0-ns mr2" style="color:#c9c9c9;">Last analytics check: <?php echo $page_data_last_update; ?></small> 
		<?php if( isset( $page_data_update_now_link ) && $page_data_update_now_link ){ ?>
		<a href="<?php echo $page_data_update_now_link; ?>" title="" class="dib f7 btn-color no-underline pv2 ph3 br1"><span class="white">CHECK NOW</span></a></span>
		<?php } ?>
	<?php } ?>
</div>

<div class="content-row">

	<div class="content-column w-100 w-third-l">

		<div class="content-column-main content-col">

			<div class="title">
				<div class="left-pos">
					<h3>TOTAL VISITS</h3>
				</div>
				<div class="right-pos">
					<span class="dib f5 fw5 ph2 br1 campaignsio-admin-green"><?php echo $domain['summ_total']['visitors']; ?></span>
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
					<span class="dib f5 fw5 ph2 br1 campaignsio-admin-green"><?php echo $domain['summ_total']['unique_visitors']; ?></span>
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
					<span class="dib f5 fw5 br1 campaignsio-admin-green"><?php echo $domain['summ_total']['page_views']; ?></span>
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
						<tbody><?php
							if( ! empty( $domain['top_countries'] ) ){
								foreach ( $domain['top_countries'] as $key => $val ) {
									echo '<tr><td class="tl">' . $val['label'] . '</td><td>' . $val['nb_visits'] . '</td></tr>';
								}
							} ?>
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
						<tbody> <?php
						if( ! empty( $domain['top_sources'] ) ){
							foreach ($domain['top_sources'] as $key => $val) {
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

<?php echo form_hidden( 'id_total_visits_data', json_encode( $domain['total_visits'] ) ); ?>
<?php echo form_hidden( 'id_unique_visits_data', json_encode( $domain['unique_visits'] ) ); ?>
<?php echo form_hidden( 'id_page_per_visit_data', json_encode( $domain['page_per_visit'] ) ); ?>
<?php echo form_hidden( 'id_referrer_visits_data', json_encode( $domain['referrer_visits_array'] ) ); ?>
<?php echo form_hidden( 'id_referrer_visits_graph_data', json_encode( $domain['referrer_visits_graph_array'] ) ); ?>