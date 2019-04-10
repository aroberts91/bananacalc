showTab = function( tab, event )
{
	event.preventDefault();
	const boxes_tab = $('#boxes_tab');
	const boxes_div = $('#boxes');

	const calc_tab = $('#calc_tab');
	const calc_div = $('#calc');

	switch( tab.id )
	{
		case 'boxes_tab':
			boxes_tab.addClass( 'active' );
			calc_tab.removeClass( 'active' );
			calc_div.hide();
			boxes_div.fadeIn();
			loadBoxesData();
			break;
		case 'calc_tab':
			calc_tab.addClass( 'active' );
			boxes_tab.removeClass('active');
			boxes_div.hide();
			calc_div.fadeIn();
	}
};

loadBoxesData = function()
{
	$.ajax({
		type: 'POST',
		url: './api/boxes.load.php',
		success: onLoadBoxes,
		error: ajaxFailure
	})
};

onLoadBoxes = function( data )
{
	if( !data )
		return;

	let html = '<table id="box_table" class="table table-hover table-sm">' +
	'<thead>' +
	'<tr>' +
	'<th scope="col" hidden>ID</th>' +
	'<th scope="col">Banana Quantity</th>' +
	'<th scope="col"></th>' +
	'<th scope="col"></th>' +
	'</tr>' +
	'</thead>' +
	'<tbody>';

	for( let ind = 0; ind < data.length; ind++ )
	{
		let box = data[ ind ];

		html += '<tr id="box_row_' + box.id + '">' +
			'<th scope="row" hidden>' + box.id + '</th> ' +
			'<td>' + box.qty + '</td>' +
			'<td><button class="btn btn-warning btn-sm" onclick="editBox(' + box.id + ',' + box.qty + ')">Edit</button></td>' +
			'<td><button class="btn btn-danger btn-sm" onclick="deleteBox(' + box.id + ')">Delete</button></td>' +
			'</tr>';
	}

	html += '</tbody></table>';
	$('#boxes_wrapper').html( html );
};

calculateBoxes = function()
{
	const qty = $('#banana_input').val();

	if( isNaN( qty ) || !qty )
		return showAlert( 'warning', 'Please enter a number of bananas to continue' );

	$.ajax({
		type: 'post',
		url: './api/boxes.calc.php',
		data: { order_qty: qty },
		success: onCalcBoxes,
		error: ajaxFailure
	});

	$('#calc_wrapper').hide();
	$('#results_wrapper').fadeIn();
};

onCalcBoxes = function( data )
{
	if( !data )
		return;

	$('#order_qty').html( $('#banana_input').val() );
	$('#actual_qty').html( data.sent_qty );

	let html = '<table id="results_table" class="table table-hover table-sm">' +
		'<thead>' +
		'<tr>' +
		'<th scope="col">Box Size</th>' +
		'<th scope="col">Quantity</th>' +
		'</tr>' +
		'</thead>' +
		'<tbody>';

	const order_data = Object.entries( data.order_arr );

	for( const [ size, qty ] of order_data )
	{
		html += `<tr>
					<td>${ size }</td>
					<td>${ qty }</td>
				</tr>`;
	}

	html += '</tbody></table>';
	$('#calc_results').html( html );
};

ajaxFailure = function( error )
{
	alert( JSON.stringify( error ) );
};

editBox = function( id, qty )
{
	$('#add_wrapper').hide();
	$('#edit_wrapper').fadeIn();
	$('#edit_quantity').val( qty );
	$('#box_id').val( id );
};

addBox = function()
{
	$('#edit_wrapper').hide();
	$('#add_wrapper').fadeIn();
	$('#add_quantity').focus();
};

confirmAdd = function()
{
	let qty = $('#add_quantity').val();

	if( isNaN( qty ) || !qty )
		return showAlert( 'warning', 'Please enter a number of bananas to continue' );

	$.ajax({
		type: 'post',
		url: './api/boxes.save.php',
		data: { qty: qty, action: 'add' },
		success: onBoxUpdate,
		error: ajaxFailure
	});

	$('#add_wrapper').hide();
};

deleteBox = function( id )
{
	if( !confirm( 'Are you sure you want to delete?' ) )
		return;

	$.ajax({
		type: 'post',
		url: './api/boxes.save.php',
		data: { box_id: id, action: 'delete' },
		success: onBoxUpdate,
		error: ajaxFailure
	});
};

confirmEdit = function()
{
	let qty = $('#edit_quantity').val();

	if( isNaN( qty ) || !qty )
		return showAlert( 'warning', 'Please enter a number of bananas to continue' );

	$.ajax({
		type: 'post',
		url: './api/boxes.save.php',
		data: { box_id: $('#box_id').val(), qty: qty, action: 'update' },
		success: onBoxUpdate,
		error: ajaxFailure
	});
};

onBoxUpdate = function( data )
{
	showAlert( 'success', 'Box ' + data.action + ' successfully' );
	$('#edit_wrapper').fadeOut();
	loadBoxesData();
};

showAlert = function( type, message )
{
	var warning_class = '';
	switch( type )
	{
		case 'warning':
			warning_class = 'alert-warning';
			break;
		case 'error':
			warning_class = 'alert-danger';
			break;
		case 'success':
			warning_class = 'alert-success';
			break;
	}

	let html = 	'<div class="alert alert-dismissable fade show ' + warning_class + '" role="alert">' +
					'<span>' + message + '</span>' +
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="hideAlert()">' +
						'<span aria-hidden="true">&times;</span>' +
					'</button>' +
				'</div>';

	$('#alert_wrapper').html( html ).fadeIn();


	if( type === 'error' )
		return;

	setTimeout( hideAlert, 2500 );
};

hideAlert = function()
{
	$('#alert_wrapper').fadeOut();
};

newOrder = function()
{
	$('#results_wrapper').hide();
	$('#calc_wrapper').fadeIn();
	$('#banana_input').val('');

};



