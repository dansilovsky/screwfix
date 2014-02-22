var selectable = $('ol');

selectable.mousedown(function() {
	selectable.find('li').mouseover(function() {
		$(this).css('background', 'grey');
	});
});





