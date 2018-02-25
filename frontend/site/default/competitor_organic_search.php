<div class="panel-content padding-zero">
							<table class="table table-bordered ds-list-table">
			                    <thead>
			                      <tr>
			                        <th>#</th>
									<th>Domain</th>
									<th>All Keywords</th>
									<th>Traffic dynamic</th>
									<th>Visible dynamic</th>
									<th>Keyword dynamic</th>
			                      </tr>
			                    </thead>
			                    <tbody>
			                    	<?php 
			                    	if(isset($result)):
			                    	$res = json_decode($result, true);
			                    	$res = array_slice($res, 0, 20);
			                    	foreach($res as $key=>$keyword): ?>
								    <tr>
								    	<td><?php echo $key+1; ?></td>
								        <td><a href="#" title=""><?php echo $keyword['domain']; ?></a></td>
								        <td><?php echo $keyword['keywords']; ?></td>
								        <td><?php echo $keyword['traffic_dynamic']; ?></td>
								        <td><?php echo $keyword['visible_dynamic']; ?></td>
								        <td><?php echo $keyword['keywords_dynamic']; ?></td>
								    </tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td>Sorry no data found</td>
								</tr>
							<?php endif; ?>
									</tbody>
				            </table>
						</div>
						<div class="panel-footer">
							<a href="#" class="btn btn-md btn-rounded btn-blue" title="" >VIEW ALL</a>
						</div>