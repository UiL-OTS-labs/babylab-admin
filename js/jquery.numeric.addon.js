$(function($) {
	$(".numeric").numeric();
	$(".integer").numeric(false, function() {
		alert("Integers only");
		this.value = "";
		this.focus();
	});
	$(".positive").numeric( {
		negative : false
	}, function() {
		alert("No negative values");
		this.value = "";
		this.focus();
	});
	$(".positive-integer").numeric( {
		decimal : false,
		negative : false
	}, function() {
		alert("Positive integers only");
		this.value = "";
		this.focus();
	});
});