$(function() {
	$( "input[name='multilingual']" ).change(function() {
		$( "#languages").toggle($( "input[name='multilingual']:checked" ).val() == "1");
	});

	$( "input[name='multilingual']" ).change();
});
