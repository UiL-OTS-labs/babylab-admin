$(function() {
	$("#test, #participant").change(function() {
		var test_id = $("#test").val();
		var participant_id = $("#participant").val();
		
		var request = $.ajax ({
			url: 'score/show_all_testcats',
			type: 'POST',
			data: {test: test_id, participant: participant_id},
			success: function(response) {
				// Update the div with scores
				$("#scores").html(response); 
				// Reset positive integer validation
				$(".positive-integer").numeric( {
					decimal : false,
					negative : false
				}, function() {
					alert("Positive integers only");
					this.value = "";
					this.focus();
				});
			} 
		});
	}).trigger('change');	// Triggers call on page load
});