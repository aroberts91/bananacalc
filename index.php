<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Brucie's Banana Calculator</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="container" style="margin-top: 2%;">
		<div class="row">
			<div class="col-sm"></div>
			<div class="col-sm">
				<div class="card">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs nav-fill">
							<li class="nav-item">
								<a class="nav-link active" id="calc_tab" href="" onclick="showTab( this, event )">Calculator</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="boxes_tab" href="" onclick="showTab( this, event )">Box Sizes</a>
							</li>
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-pane" id="calc">
							<div id="calc_wrapper">
								<div class="row">
									<div class="col-sm">
										<h2>Banana Calculator</h2>
										<span>Enter the number of bananas ordered to calculate which boxes to send</span>
									</div>
								</div>
								<div class="row" style="margin-top: 5px;">
									<div class="col-sm">
										<input class="form-control" id="banana_input" />
									</div>
								</div>
								<div class="row" style="margin-top: 5px;">
									<div class="col-sm" style="text-align: right;">
										<button class="btn btn-primary" onclick="calculateBoxes()">Calculate</button>
									</div>
								</div>
							</div>
							<div id="results_wrapper" style="display: none;">
								<div class="row">
									<div class="col-sm-6" style="text-align: center;">
										<span class="qty_text" id="order_qty">750</span>
									</div>
									<div class="col-sm-6" style="text-align: center;">
										<span class="qty_text" id="actual_qty">800</span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6" style="text-align: center;">
										<span class="order_info">Bananas Ordered</span>
									</div>
									<div class="col-sm-6" style="text-align: center;">
										<span class="order_info">Bananas to send</span>
									</div>
								</div>
								<div class="row">
									<div id="calc_results" class="col-sm-12">

									</div>
									<div class="col-sm">
										<button class="btn btn-outline-primary btn-sm" style="float: right;" onclick="newOrder()">New Order</button>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="boxes" style="display: none;">
							<div class="row">
								<div class="col-sm">
									<h2>Available Boxes</h2>
								</div>
							</div>
							<div class="row" style="margin-top: 5px;">
								<div class="col-sm" id="boxes_wrapper">

								</div>
							</div>
							<div class="col-sm" style="text-align: right;">
								<button class="btn btn-primary" onclick="addBox()">Add Box</button>
							</div>
							<div class="row">
								<div class="col-sm" id="edit_wrapper" style="display: none;">
									<p class="h4">Edit Quantity</p>
									<div class="input-group mb-3">
										<input type="text" id="box_id" hidden class="form-control"/>
										<input type="text" id="edit_quantity" class="form-control">
										<div class="input-group-append">
											<button class="btn btn-success" type="button" onclick="confirmEdit()">Confirm</button>
										</div>
									</div>
								</div>
								<div class="col-sm" id="add_wrapper" style="display: none;">
									<p class="h4">Add Box</p class="h4">
									<div class="input-group mb-3">
										<input type="text" id="add_quantity" class="form-control">
										<div class="input-group-append">
											<button class="btn btn-success" type="button" onclick="confirmAdd()">Add</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="alert_wrapper" style="display: none;"></div>
			</div>
			<div class="col-sm"></div>
		</div>
	</div>
	<script type="text/javascript" src="js/banana.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
</html>

<style>
	.qty_text {
		font-size: 1.6em;
		font-weight: 600;
	}
</style>