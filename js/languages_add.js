$(function() {
	var i = $("#languages").children().size() - 1;
	
	$( ".add_l" ).click(function() {
		i++;
		$("#languages").append('<div class="pure-control-group"><label for="language">Taal ' + i + '</label> <input type="text" name="language[]" placeholder="Taal"> <input type="text" name="percentage[]" placeholder="Percentage"> <a class="del_l">x verwijder taal</a></div>');
		return false;
	});
	
	$( "body" ).on("click", ".del_l", function(e) {
		$(this).parent("div").remove();
		i--;
		return false;
	});
});
