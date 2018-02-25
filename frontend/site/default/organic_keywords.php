
						<div class="panel-content table-responsive padding-zero">
			                <table class="table table-bordered ds-list-table">
			                    <thead>
				                      <tr>
				                        <th>Keywords </th>
										<th>Position</th>
										<th>Google Volume</th>
										<th>CPC $</th>
				                      </tr>
			                    </thead>
			                    <tbody>
									<?php 
									if(isset($result)):
									$res = json_decode($result, true);
									$res = array_slice($res, 0, 20);
									foreach($res as $key=>$result): ?>
									    <tr>
									        <td><?php echo $result['keyword']; ?></td>
									        <td><?php echo $result['position']; ?></td>
									        <td><?php echo $result['concurrency']; ?></td>
									        <td><?php echo $result['cost']; ?></td>
									    </tr>
								    <?php endforeach;
								     ?>
								 <?php else: ?>
								 	<tr>
								 		<td colspan="4"> No data available</td>
								 	</tr>
								 <?php endif; ?>
								</tbody>
				            </table>
	                	</div>
	                	<div class="panel-footer">
	                		<a href="#" class="btn btn-md btn-rounded btn-blue" title="" >VIEW ALL</a>
	                	</div>