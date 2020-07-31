host = window.location.hostname;
previewHost = 'https://raw.githack.com';

/* if (host == 'raw.githubusercontent.com') {
	window.location = previewHost + window.location.pathname;
} else */ if (host == 'github.com') {
	pathArray = window.location.pathname.split('/');
	dunno = 'Please navigate to the file you wish to preview.';
	action = pathArray[3];
	if (action == null || action == '') {
		alert (dunno);
	} else if (action == 'blob' || action == 'edit') {
		window.location = previewHost + pathArray.slice(0, 3).join('/') + '/' + pathArray.slice(4).join('/');
	} else if (action == 'pull' || action == 'pulls') {
		alert ('This version is unable to determine which file you want to preview in pull requests. Please navigate to the specific file and try again.');
	} else {
		alert (dunno);
	}
} else alert ('This resource does not appear to be in a GitHub repository, unable to generate preview.');
