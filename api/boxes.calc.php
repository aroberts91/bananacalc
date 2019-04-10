<?php
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require $DOC_ROOT . '/bananarama/api/config.php';
require $DOC_ROOT . '/bananarama/include/base.inc';

function calculateBoxes( $requested_qty ) {
	try {
		$db = new PDO( DSN, USER, PASSWORD );

		$query = 'SELECT qty '
			.	'FROM boxes '
			.	'ORDER BY qty ASC';

		$stmt = $db->prepare( $query );
		$stmt->execute();
		$box_arr = $stmt->fetchAll( PDO::FETCH_ASSOC );
	}
	catch ( PDOException $e ) {
		throw new PDOException( $e->getMessage() );
	}

	//To be returned to the user
	$order_arr = [];
	$remaining_order = $requested_qty;

	//Get the smallest box size available
	$smallest_box = $box_arr[ 0 ][ 'qty' ];

	while( $remaining_order > 0 )
	{
		for( $ind = count( $box_arr ); $ind > 0; $ind-- )
		{
			if( $remaining_order <= 250 )
			{
				$key_str = (string)$smallest_box;
				$remaining_order = 0;

				//If we already have a small box, increment, or assign if not.
				array_key_exists( $key_str, $order_arr ) ? $order_arr[ $key_str ]++ : $order_arr[ $key_str ] = 1;
				break;
			}

			$curr_box = $box_arr[ $ind - 1 ]['qty'];
			$key_str = (string)$curr_box;

			if( ( $remaining_order - $curr_box ) < 0 )
				continue;

			array_key_exists( $key_str, $order_arr ) ? $order_arr[ $key_str ]++ : $order_arr[ $key_str ] = 1;
			$remaining_order -= $curr_box;
			break;
		}
	}

	//Sanity check, we use the number of bananas we know need to be sent to check for a better box combination
	$sent_qty = 0;

	foreach( $order_arr as $box => $qty )
		$sent_qty += ( (int)$box * $qty );

	//If we know we're sending more than the requested number of bananas, recheck available boxes with the actual number
	//as we may be able to this in less boxes
	if( $sent_qty === $requested_qty )
		return [ 'order_arr' => $order_arr, 'sent_qty'	=> $sent_qty ];

	return calculateBoxes( $sent_qty );
}

$order_qty = intParam( 'order_qty' );

if( !$order_qty )
{
	$res = [ 'status' => 400, 'message'  => 'No order quantity provided'];
	header('Content-Type: application/json');
	echo json_encode( $res );
	exit;
}

$order_arr = calculateBoxes( $order_qty );

header( 'Content-Type: application/json' );
echo json_encode( $order_arr );