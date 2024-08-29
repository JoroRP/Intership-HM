<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Internal Transfer</title>
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
		
		<div class="d-flex justify-content-between align-items-center mb-4 containter-fluid p-0 ">
			<h2>Internal Transfer</h2>
			<div>
				<button class="btn me-2 yellow-button">Deposit</button>
				<button class="btn btn-secondary me-2">Withdraw</button>
				<button class="btn btn-secondary">Transfer</button>
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-md-5">
				<h5 class="card-title mb-2">Deposit to trading account</h5>
				<div class="card">
					<div class="card-body">
						<p>Available USDT: 208849.740000 USDT</p>
						<div class="mb-3">
							<input type="text" class="form-control mb-2" placeholder="Amount">
							<select class="form-select mb-2">
								<option selected>Tether</option>
							</select>
							<button class="btn w-100 yellow-button">Deposit</button>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-5">
				<h5 class="card-title mb-2">Withdrawal from trading account</h5>
				<div class="card">
					<div class="card-body">
						<p>Available USDT: 49374.318959945 USDT</p>
						<div class="mb-3">
							<input type="text" class="form-control mb-2" placeholder="Amount">
							<select class="form-select mb-2">
								<option selected>Tether</option>
							
							</select>
							<button class="btn btn-warning w-100 yellow-button">Withdraw</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.html'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
