window.addEventListener("load", function() {
	request = new XMLHttpRequest();
	request.open("GET", "json", true);
	request.send();
	
	request.onload = function(){
		results = JSON.parse(request.response);
		innerHTML = "";
		innerHTML += "<tr>";
		for (key in results[0]) {
			innerHTML += "<th>" + key + "</th>";
		}
		innerHTML += "</tr>";
		results.forEach(function(item){
			innerHTML += "<tr>";
			for (key in item) {
				innerHTML += "<td>" + item[key] + "</td>";
			}
			innerHTML += "</tr>";
		});
		document.getElementById("resultsTable").innerHTML = innerHTML;
	};
});
