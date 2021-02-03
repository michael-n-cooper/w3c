<?php 
	require_once "_db_connect.phi";
	
	//$sth = $dbh->prepare("select item_id, handle, item, description, type_id, type from items join item_types on items.item_key = item_types.item_key join types on item_types.type_key = types.type_key;");
		//$sth->bindValue(":group", $group, PDO::PARAM_STR);
		
	//$sth = typeQuery("wcag-3-gl");
	$sth = constructQuery("select type_id, type from types", array("type_id"=>"wcag-3-gl"));
	$sth->execute();
	
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		$sth_items = constructQuery("select items.item_key, item_id, types.type_key, type_id, handle, item, description from items join item_types on items.item_key = item_types.item_key join types on item_types.type_key = types.type_key", array("type_id"=>$row["type_id"]));
		$sth_items->execute();
		while ($row_item = $sth_items->fetch(PDO::FETCH_ASSOC)) {
			$sth_child_ids = constructQuery("select item_key from hierarchy", array("parent_item_key"=>$row_item["item_key"], "parent_type_key"=>$row_item["type_key"]));
			$sth_child_ids->execute();
			$child_item_keys = null;
			while ($row_child_ids = $sth_child_ids->fetch()) {
				$child_item_keys[] = $row_child_ids["item_key"];
			}
			$sth_child = constructQuery("select item_id, handle, item, description from items", null, array("item_key"=>$child_item_keys));
			$sth_child->execute();
			while ($row_child = $sth_child->fetch(PDO::FETCH_ASSOC)) {
				$row_item["items"][] = $row_child;
			}
			$row["items"][] = $row_item;
		}
		$result["types"][] = $row;
	}
	
	header('Content-type: application/json');
	print (json_encode($result));
	
	// construct a query using a starting sql statement and arrays of "and" and "or" restrictions,
	// create a PDOStatement, and bind values, returning the PDOStatement
	// $ands and $ors are associative arrays whose keys are the parameter to filter on, and values are the values
	// values can be arrays themselves if needing to filter on multiple values of the same parameter (in or relationship with each other)
	function constructQuery($base, $ands = null, $ors = null) {
		global $dbh;
		
		//check that "and" and "or" parameters are arrays
		if (!is_null($ands) and !is_array($ands)) print ("parameter \"ands\" must be an array");
		if (!is_null($ors) and !is_array($ors)) print ("parameter \"ors\" must be an array");
		
		// start a where clause
		$sql = $base;
		if (!is_null($ands) or !is_null($ors)) $sql .= " where";

		// put in sql "and" statements
		if (!is_null($ands)) {
			$i = 0;
			foreach($ands as $key => $val) {
				if ($i > 0) $sql .= " and";
				if (is_array($val)) {
					$sql .= " (";
					$j = 0;
					foreach($val as $innerval) {
						if ($j > 0) $sql .= " or";
						$sql .= " " . $key . " = ?";
						$j++;
					}
					$sql .= ")";
				}
				else $sql .= " " . $key . " = ?";
				$i++;
			}
		}
		
		// join "and" and "or" if needed
		if (!is_null($ands) and !is_null($ors)) $sql .= " and";
		
		// put in sql "or" statements
		if (!is_null($ors)) {
			$sql .= "(";
			$i = 0;
			foreach($ors as $key => $val) {
				if ($i > 0) $sql .= " or";
				if (is_array($val)) {
					$sql .= " (";
					$j = 0;
					foreach($val as $innerval) {
						if ($j > 0) $sql .= " or";
						$sql .= " " . $key . " = ?";
						$j++;
					}
					$sql .= ")";
				}
				else $sql .= " " . $key . " = ?";
				$i++;
			}
			$sql .= ")";
		}
		
		//print $sql;
		
		// initialize PDO statement
		$sth = $dbh->prepare($sql);
		
		$i = 1;

		// bind "and" values
		if (!is_null($ands)) {
			foreach ($ands as $val) {
				if (is_array($val)) {
					foreach($val as $innerval) {
						$sth->bindValue($i, $innerval);
						$i++;
					}
				}
				else {
					$sth->bindValue($i, $val);
					$i++;
				}
			}
		}

		// bind "or" values
		if (!is_null($ors)) {
			foreach ($ors as $val) {
				if (is_array($val)) {
					foreach($val as $innerval) {
						$sth->bindValue($i, $innerval);
						$i++;
					}
				}
				else {
					$sth->bindValue($i, $val);
					$i++;
				}
			}
		}
		
		return $sth;
	}
?>
