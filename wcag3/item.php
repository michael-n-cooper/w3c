<!DOCTYPE html>
<html>
	<head>
		<title>Item</title>
		<script><!--
			var types;
function getJSON (url, next) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    
    xhr.onload = function() {
    
        var status = xhr.status;
        
        if (status == 200) {
            callback(null, xhr.response, next);
        } else {
            callback(status);
        }
    };
    
    xhr.send();
    
    function callback(err, data) {
	    
	    if (err != null) {
	        console.error(err);
	    } else {
	        types = data;
	        next();
	    }
    }
};

getJSON("api/types", loadTypes);

function loadTypes() {
	var typeControl = document.getElementById("type");
	types["types"].forEach(function(type){
		var option = document.createElement("option");
		option.id = type["type_id"];
		option.innerHTML = type["type"];
		typeControl.appendChild(option);
	});
}
	//--></script>
	</head>
	<body>
		<h1>Add / Edit Item</h1>
		<form>
			<table>
				<colgroup>
					<col />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<td>Type:</td>
						<td><select id="type"></select></td>
					</tr>
					<tr>
						<td>ID:</td>
						<td><input type="text" id="id"/></td>
					</tr>
					<tr>
						<td>Handle:</td>
						<td><input type="text" id="handle"/></td>
					</tr>
					<tr>
						<td>Item:</td>
						<td><input type="text" id="item"/></td>
					</tr>
					<tr>
						<td>Description:</td>
						<td><textarea id="description"></textarea></td>
					</tr>
				</tbody>
			</table>
		</form>
		
	</body>
</html>