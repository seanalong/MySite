<style>
	.container-table-runs {
		width: 50%;
		height: 300px;
		border-collapse: collapse;
		overflow-x: auto;
	}
	
	.table-runs {
		width: 100%;
	}
	
	.table-runs td, th {
		border: 1px solid #ddd;
		padding: 8px;
	}
	
	.table-runs tr:nth-child(even) {
		background-color: #f2f2f2;
	}
	
	.table-runs tr:hover {
		background-color: #ddd;
	}
	
	.table-runs th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #2b2d2f;
		color: white;
	}
	
	.message {
		width: 100%;
		height: 30px;
		line-height: 30px;
	}
	
	.message-success {
		border: solid 1px green;
		background-color: #90ee90;
		color: green;
	}
	
	.message-error {
		border: solid 1px red;
		background-color: #ffcccb;
		color: red;
	}
</style>
<?php if (!empty($message)) { ?>
	<div class="message <?php echo $messageClass; ?>">
		<?php echo $message; ?>		
	</div>
<?php } ?>
<?php if (!empty($runs)) { ?>
	<h2>Currently Stored Train Runs</h2>
	<div class="container-table-runs">
		<table class="table-runs">
			<thead>
				<tr>
					<th id="trainLineHeader" data-index="trainLine">Train Line</th>
					<th id="trainRouteHeader" data-index="route">Train Route</th>
					<th id="trainIdHeader" data-index="id">Run Number</th>
					<th id="trainOperatorHeader" data-index="operator">Operator</th>
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
<h3>Create/Update A Run</h3>
<div class="runs-form-container">
	<form action="<?php echo $runPostEndpoint; ?>" method="post">
		<div>
			<label for="runId">Run Number</label>
			<input type="text" id="runId" name="runId">
		</div>
		<div>
			<label for="route">Train Route</label>
			<input type="text" id="route"  name="route">
		</div>
		<div>
			<label for="trainLine">Train Line</label>
			<input type="text" id="trainLine" name="trainLine">
		</div>
		<div>
			<label for="operator">Operator</label>
			<input type="text" id="operator" name="operator">
		</div>
		<input id="run-form-submit" type="submit" value="Create New Run">
	</form>
</div>
<?php if (!empty($runs)) { ?>
	<h3>Delete Runs</h3>
	<div class="runs-form-delete">
		<form action="<?php echo $runDeleteEndpoint; ?>" method="post">
			<div>
				<label for="deleteId">Select Run Id: </label>
				<select id="deleteId" name="id">
					<?php foreach($runs as $id => $run) { ?>
						<option value="<?php echo $id; ?>"><?php echo $id; ?></option>
					<?php } ?>
				</select>
				<input id="run-delete-submit" type="submit" value="Delete Selected Run">
			</div>
		</form>
	</div>
<?php } ?>