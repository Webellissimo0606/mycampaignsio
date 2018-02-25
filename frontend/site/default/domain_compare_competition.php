<?php if(isset($id) && $id): ?>
<div class="panel-content bg-light">
	<div class="row">
		<div class="col-md-6">
			<div id="venn"></div>
		</div>
		<div class="col-md-6">
			<div class="panel">
				<div class="panel-header">
					<h3>The Number of domain's keywords</h3>
				</div>
				<div class="panel-content panel-content padding-zero">
					<table class="table table-bordered ds-list-table">
		                <thead>
		                  <tr>
							<th>Total Number</th>
		                    <th>Unique</th>
							<th>Domains</th>
		                  </tr>
		                </thead>
		                <tbody>
						    <tr>
						    	<td><?php echo $domain1_total; ?></td>
						        <td><?php echo $domain1_unique; ?></td>
						        <td><?php echo $domain1; ?></td>
						    </tr>
						    <tr>
						    	<td><?php echo $domain2_total; ?></td>
						        <td><?php echo $domain2_unique; ?></td>
						        <td><?php echo $domain2; ?></td>
						    </tr>

						    <tr>
						    	<td><?php echo $total_common_keyword; ?></td>
						        <td></td>
						        <td><?php echo $domain1.' ,'. $domain2; ?> (Common keywords)</td>
						    </tr>
						</tbody>
		            </table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel-content pagination2 table-responsive padding-zero">
    <table class="table table-bordered ds-list-table">
        <thead>
          <tr>
			<th>#</th>
            <th>KEYWORDS</th>
			<th>Pos. <?php echo $domain1; ?></th>
			<th>Pos. <?php echo $domain2; ?></th>
			<th>Google Volume</th>
			<th>CPC ($)</th>
			<th>Competition in PPC</th>
          </tr>
        </thead>
        <tbody id="keyword_compare_table">
        <?php 
        $keywords = json_decode($result, true);
         ?>
         <?php foreach($keywords as $key=>$keyword): ?>	
            <tr>
                <td><?php echo $key+1; ?></td>
                <td><?php echo $keyword['keyword']; ?></td>
	            <td><?php echo $keyword['position1']; ?></td>
	            <td><?php echo $keyword['position2']; ?></td>
	            <td><?php echo $keyword['concurrency']; ?></td>
	            <td><?php echo $keyword['cost']; ?></td>
	            <td><?php echo $keyword['region_queries_count']; ?></td>
            </tr>
          <?php endforeach; ?>  
        </tbody>
    </table>
</div>

<div class="panel-footer">
</div>

<script type="text/javascript">
  	var chart = venn.VennDiagram()
                 .width(500)
                 .height(300);

var div = d3.select("#venn")
var sets = [
         {"sets": [0], "label": "<?php echo $domain1; ?>", "size": parseInt(<?php echo $domain1_total; ?>)},
         {"sets": [1], "label": "<?php echo $domain2; ?>", "size": parseInt(<?php echo $domain2_total; ?>)},
         {"sets": [0, 1], "size": parseInt(<?php echo $total_common_keyword; ?>)},
         ];

div.datum(sets).call(chart);

var tooltip = d3.select("body").append("div")
    .attr("class", "venntooltip");

div.selectAll("path")
    .style("stroke-opacity", 0)
    .style("stroke", "#fff")
    .style("stroke-width", 3)

div.selectAll("g")
    .on("mouseover", function(d, i) {
        // sort all the areas relative to the current item
        venn.sortAreas(div, d);

        // Display a tooltip with the current size
        tooltip.transition().duration(400).style("opacity", .9);
        tooltip.text(d.size + " keywords");

        // highlight the current path
        var selection = d3.select(this).transition("tooltip").duration(400);
        selection.select("path")
            .style("fill-opacity", d.sets.length == 1 ? .4 : .1)
            .style("stroke-opacity", 1);
    })

    .on("mousemove", function() {
        tooltip.style("left", (d3.event.pageX) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
    })

    .on("mouseout", function(d, i) {
        tooltip.transition().duration(400).style("opacity", 0);
        var selection = d3.select(this).transition("tooltip").duration(400);
        selection.select("path")
            .style("fill-opacity", d.sets.length == 1 ? .25 : .0)
            .style("stroke-opacity", 0);
    });
  </script>  	
    <?php else: ?>
    	<div>
    		Sorry no data is available
    	</div>
<?php endif; ?>

