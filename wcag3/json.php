<?php 
	require_once "_db_connect.phi";
	
	//$sth = $dbh->prepare("select item_id, handle, item, description, type_id, type from items join item_types on items.item_key = item_types.item_key join types on item_types.type_key = types.type_key;");
		//$sth->bindValue(":group", $group, PDO::PARAM_STR);
		
	$sth = typeQuery("wcag-3-gl,fast-cl");
	$sth->execute();
	
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		$sth_items = itemQuery($row["type_id"]);
		$sth_items->execute();
		$item = $sth_items->fetchAll(PDO::FETCH_ASSOC);
		$type = $row;
		$type["items"] = $item;
		$result["types"][] = $type;
	}
	
	header('Content-type: application/json');
	print (json_encode($result));
	//print (json_encode($sth->fetchAll(PDO::FETCH_ASSOC)));
	
	function itemQuery($type_id = null) {
		global $dbh;
		
		$sql = "select item_id, handle, item, description, type_id, type from items join item_types on items.item_key = item_types.item_key join types on item_types.type_key = types.type_key";
		$sql .= addWhere("type_id", $type_id);
		$sth = $dbh->prepare($sql);
		$sth = bindValues($sth, $type_id);
		return $sth;
	}
	
	function typeQuery($type_id = null) {
		global $dbh;
		
		$sql = "select type_id, type from types";
		$sql .= addWhere("type_id", $type_id);
		$sth = $dbh->prepare($sql);
		$sth = bindValues($sth, $type_id);
		return $sth;
	}
	
	function addWhere($param, $paramVal) {
		if (!is_null($paramVal)) $arr = explode(",", $paramVal);
		else $arr = array();
		$sql = "";
		if (count($arr) > 0) {
			$sql .= " where";
			
			$i = 0;
			foreach ($arr as $val) {
				if ($i > 0) $sql .= " or ";
				$sql .= " " . $param . " = ?";
				$i++;
			}
		}
		return $sql;
	}
	function bindValues($sth, $paramVal, $type = PDO::PARAM_STR) {
		if (!is_null($paramVal)) $arr = explode(",", $paramVal);
		else $arr = array();
		$i = 1;
		foreach ($arr as $val) {
			$sth->bindValue($i, $val);//, $type);
			$i++;
		}
		return $sth;
	}
?>
