<div id="tab_domain_vs_domain" class="tab-pane fade"> 

	<div class="panel">
		<div class="panel-content bg-green">
			<h2>Compare you Domain with Competitor</h2>
			<form action="javascript:void(0);" method="post" onsubmit="comparedomain();" accept-charset="utf-8">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<input type="text" class="form-control" name="domain1" value="<?php echo $domain_data['domainHost']; ?>">
						</div>
					</div>

					<div class="col-md-5">
						<div class="form-group">
							<input class="form-control" type="text" placeholder="Competitor's Domain" name="domain2" id="domain2">
						</div>
					</div>

					

					<div class="col-md-2">
						<div class="form-group">
							<input class="btn btn-success" style="width: 100%;" type="submit" name="submit" value="Compare">
						</div>
					</div>
				</div>
			</form>
		</div>

		<div id="domain_compare_competition">
			
		</div>

		
	</div>
	
</div>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/venn/venn.min.js"></script>

<script>
function comparedomain()
{
	$.post('/serp/keywordresearch/domain_compare',{domain2:$('#domain2').val()}, function(data){
		$('#domain_compare_competition').html(data)
	},'html')
}


</script>