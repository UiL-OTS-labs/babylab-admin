$(function() {
	$('#test').change(function() {
		var id = $(this).val();
		
		$.post('score/filter_testsurveys', {test_id: id}, function(data) {
			$('#testsurvey').html(data); 
			$('#testsurvey').prop('disabled', false); 
		});
	});
});