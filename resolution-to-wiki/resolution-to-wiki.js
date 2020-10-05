function resolutions() {
var resolutions = document.querySelector('#ResolutionSummary').parentNode.nextElementSibling.getElementsByTagName('a');
if (!resolutions.length > 0) alert ('No resolutions found in these minutes.');

var pathArray = window.location.pathname.split(new RegExp('[/-]'));
var date = pathArray.slice(1, 4).join('-');
var output = '';

for (i = 0; i < resolutions.length; i++) {
	item = resolutions[i];
	output += date + ': ' + '[' + item.href + ' ' + item.innerHTML.replace(/\s*/, ' ') + ']\n';
};

alert (output);
}