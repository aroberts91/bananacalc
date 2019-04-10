<?php
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require $DOC_ROOT . '/bananarama/api/config.php';

try {
	$db = new PDO( DSN, USER, PASSWORD );

	$query = 'SELECT id, qty '
		.	'FROM boxes '
		.	'ORDER BY qty ASC';

	$stmt = $db->prepare( $query );
	$stmt->execute();

	$res = $stmt->fetchAll( PDO::FETCH_ASSOC );
}
catch ( PDOException $e ) {
	throw new PDOException( $e->getMessage() );
}

if( !$res )
	$res = [ 'status' => 400, 'message' => 'No Banana boxes found' ];

header( 'Content-Type: application/json' );
echo json_encode( $res );