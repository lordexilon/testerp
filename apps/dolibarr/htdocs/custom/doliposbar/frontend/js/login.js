

// Centering the form vertically on every window resize:
$(window).resize(function(){
	var cf = $('#carbonForm');
	
	$('#carbonForm').css('margin-top',($(window).height()-cf.outerHeight())/2)
});

// Helper function that creates an error tooltip:
function showTooltip(elem,txt)
{
	// elem is the text box, txt is the error text
	$('<div class="errorTip">').html(txt).appendTo(elem.closest('.formRow'));
}