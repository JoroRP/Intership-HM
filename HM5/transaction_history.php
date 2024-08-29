<?php

$transactions =
    [
        ['Deposit', '12.000000 BTC', '12.000000', '2022-02-28', 'Approved'],
        ['Exchange', '1.000000 BTC', '1.000000 ADA', '022-02-28', 'Approved'],
        ['Deposit', '1.000000 BTC', '1.000000', '2022-02-28', 'Waiting'],
        ['Deposit', '12.000000 BTC', '12.000000', '2023-02-28', 'Approved'],
    ];

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Transaction History</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
		  integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="CSS/custom.css">
</head>

<body class="d-flex flex-column h-100">

<?php include 'navbar.html'; ?>

<div class="d-flex h-100">
    <?php include 'sidebar.html'; ?>
	
	<div class="p-4 w-100">
		
		<div class="d-flex justify-content-between align-items-center mb-4 containter-fluid p-0">
			<h2>Transaction History</h2>
			<div>
				<button class="btn me-2 yellow-button">Deposit</button>
				<button class="btn btn-secondary me-2">Withdraw</button>
				<button class="btn btn-secondary">Transfer</button>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<label for="selectCoin" class="form-label">Select Coin</label>
						<select id="selectCoin" class="form-select">
							<option selected>Bitcoin</option>
							<option>Ethereum</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="paymentType" class="form-label">Payment Type</label>
						<select id="paymentType" class="form-select">
							<option selected>All Type</option>
							<option>Deposit</option>
							<option>Exchange</option>
						</select>
					</div>
					<div class="col-md-2">
						<label for="creationDateFrom" class="form-label">Creation Date</label>
						<input type="date" class="form-control" id="creationDateFrom">
					</div>
					<div class="col-md-2">
						<label for="creationDateTo" class="form-label">Creation Date</label>
						<input type="date" class="form-control" id="creationDateTo">
					</div>
					<div class="col-md-2">
						<label for="status" class="form-label">Status</label>
						<select id="status" class="form-select">
							<option selected>All</option>
							<option>Approved</option>
							<option>Waiting</option>
						</select>
					</div>
					<div class="col-md-2 d-flex align-items-end">
						<button class="btn w-100 yellow-button">Search</button>
					</div>
				</div>
			</div>
		</div>

		<h4>History results</h4>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead class="table-light">
				<tr>
					<th>Type</th>
					<th>From Coin</th>
					<th>To Coin</th>
					<th>Date</th>
					<th>Status</th>
				</tr>
				</thead>
				<tbody>
                <?php foreach ($transactions as $transaction): ?>
					
					<tr>
						<td><?php echo $transaction[0] ?></td>
						<td><?php echo $transaction[1] ?></td>
						<td><?php echo $transaction[2] ?></td>
						<td><?php echo $transaction[3] ?></td>
						<td><?php echo $transaction[4] ?></td>
					</tr>

                <?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include 'footer.html'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
