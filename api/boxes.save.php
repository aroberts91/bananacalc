<?php
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require $DOC_ROOT . '/bananarama/api/config.php';
require $DOC_ROOT . '/bananarama/include/base.inc';

function errorResponse( $msg )
{
	header( 'Content-Type: application/json' );
	echo json_encode( [ 'status' => 400, 'message' => $msg ] );
	exit;
}

function successResponse( $action )
{
	header( 'Content-Type: application/json' );
	echo json_encode( [ 'status' => 200, 'message' => 'success', 'action' => $action ] );
	exit;
}

try
{
	$db = new PDO( DSN, USER, PASSWORD );
}
catch ( PDOException $e )
{
	throw new PDOException( $e->getMessage() );
}

if( !isset( $_REQUEST['action'] ) )
	errorResponse( 'No action provided' );

$action 	= strParam( 'action' );
$id 		= intParam( 'box_id' );
$qty 		= intParam( 'qty' );

switch( $action )
{
	case 'add':
		$query 	= 'INSERT INTO boxes (qty) VALUES (:qty)';
		$stmt 	= $db->prepare( $query )->execute( [ 'qty' => $qty ] );
		$new_id = $db->lastInsertId();

		if( !$new_id )
			errorResponse( 'Problem adding new box. Please try again.' );

		successResponse( 'added' );
	case 'delete':
		if( !$id )
			errorResponse( 'No ID provided' );

		$query = 'DELETE FROM boxes WHERE id = :id';

		$stmt = $db->prepare( $query );
		$stmt->execute( [ 'id' => $id ] );

		if( !$stmt )
			errorResponse( 'Error deleting box. Please try again' );

		successResponse( 'deleted' );
	case 'update':
		if( !$id || !$qty )
			errorResponse( 'No ID/Quantity provided' );

		$query = 'UPDATE boxes SET qty = :qty WHERE id = :id';

		$stmt = $db->prepare( $query );
		$stmt->execute( [ 'qty' => $qty, 'id' => $id ] );

		if( !$stmt )
			errorResponse( 'Error updating box. Please try again' );

		successResponse( 'updated' );
}

