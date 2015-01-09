$(document).ready(function() {
	$('a[role=tab]').click(function() {
		window.location.hash = 'tab=' + $(this).attr('href').replace('#','');
	});
	if (window.location.hash.match(/^#tab=/g)) {
		var tabname = window.location.hash.replace(/^#tab=/, '');
		$('a[role=tab][href=#'+tabname+']').click();
	}
	
});