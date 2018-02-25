
						<div class="panel-content table-responsive padding-zero">
			                <table class="table table-bordered ds-list-table">
			                    <thead>
				                      <tr>
				                        <th>keyword </th>
										<th>title</th>
										<th>Position</th>
										<th>Concurrency</th>
										<th>Found results</th>
				                      </tr>
			                    </thead>
			                    <tbody>
									<?php 

									if(isset($data)):
									foreach($data as $result): ?>
									    <tr>
									        <td><?php echo $result['keyword']; ?></td>
									        <td><?php echo $result['title']; ?></td>
									        <td><?php echo $result['position']; ?></td>
									        <td><?php echo $result['concurrency']; ?></td>
									        <td><?php echo $result['found_results']; ?></td>
									    </tr>
								    <?php endforeach;
								     ?>
								 <?php else: ?>
								 	<tr>
								 		<td colspan="5"> No data available</td>
								 	</tr>
								 <?php endif; ?>
								</tbody>
				            </table>
	                	</div>
	                	<div class="panel-footer">
	                		<a href="#" class="btn btn-md btn-rounded btn-blue" title="" >VIEW ALL</a>
	                	</div>