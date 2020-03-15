<?php if (!empty($runs)) { ?>
	<h2>Currently Stored Train Runs</h2>
	<div>
		<table>
			<thead>
				<tr>
					<th>Train Line</th>
					<th>Train Route</th>
					<th>Run Number</th>
					<th>Operator</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($runs as  $r) { ?>
					<tr>
						<td><?php echo $r->getTrainLine(); ?></td>
						<td><?php echo $r->getRoute(); ?></td>
						<td><?php echo $r->getId(); ?></td>
						<td><?php echo $r->getOperator(); ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>