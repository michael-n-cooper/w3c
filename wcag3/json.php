<?php 
	require_once "_db_connect.phi";
	
	$sth = $dbh->prepare("select item_id, handle, item, description, type_id, type from items join item_types on items.item_key = item_types.item_key join types on item_types.type_key = types.type_key;");
		//$sth->bindValue(":group", $group, PDO::PARAM_STR);
	$sth->execute();
	header('Content-type: application/json');
	print (json_encode($sth->fetchAll(PDO::FETCH_ASSOC)));
?>
