<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Wallet Overview</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
		  integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="CSS/custom.css">
</head>

<body class="d-flex flex-column h-100">
<?php include 'navbar.html'; ?>

<div class="d-flex">
    <?php include 'sidebar.html'; ?>

	<div class="p-4 w-100">
		<div class="d-flex justify-content-between align-items-center mb-4 containter-fluid p-0">
			<h2>Wallet Overview</h2>
			<div>
				<button class="btn me-2 yellow-button">Deposit</button>
				<button class="btn btn-secondary me-2">Withdraw</button>
				<button class="btn btn-secondary">Transfer</button>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Estimated Balance</h5>
						<h3 class="card-text">993.3 BTC</h3>
						<div class="chart-container">
						
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div>
			<h4>Fiat Balance</h4>
			<div class="row mb-4">
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">EUR</h5>
							<p class="card-text">20849.74 EUR</p>
							<button class="btn btn-warning">Deposit</button>
							<button class="btn btn-secondary">Exchange</button>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">USD</h5>
							<p class="card-text">58849.74 USD</p>
							<button class="btn btn-warning">Deposit</button>
							<button class="btn btn-secondary">Exchange</button>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">BGN</h5>
							<p class="card-text">301849.74 EUR</p>
							<button class="btn btn-warning">Deposit</button>
							<button class="btn btn-secondary">Exchange</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<h4>Crypto Balance</h4>
		<div class="row">
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">ADA</h5>
						<p class="card-text">1712.00 ADA</p>
						<button class="btn btn-warning">Deposit</button>
						<button class="btn btn-secondary">Exchange</button>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">BTC</h5>
						<p class="card-text">993.313456 BTC</p>
						<button class="btn btn-warning">Deposit</button>
						<button class="btn btn-secondary">Exchange</button>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">ETH</h5>
						<p class="card-text">82.02 ETH</p>
						<button class="btn btn-warning">Deposit</button>
						<button class="btn btn-secondary">Exchange</button>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">USDT</h5>
						<p class="card-text">208849.74 ADA</p>
						<button class="btn btn-warning">Deposit</button>
						<button class="btn btn-secondary">Exchange</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.html'; ?>

<script src="/docs/5.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>

</body>
</html>
