<?php 
	require "queries.phi";
	
	header('Content-type: application/json');
	print (json_encode(dataToArray()));


?>
