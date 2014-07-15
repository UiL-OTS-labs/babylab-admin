$(function() {
	$('#test').change(function() {
		var id = $(this).val();
		
		$.post('score/filter_testcats', {test_id: id}, function(data) {
			$('#testcat').html(data); 
			$('#testcat').prop('disabled', false); 
		});
	});
});