$(function() {
	$('#test').change(function() {
		var id = $(this).val();
		
		$.post('filter_testcats', {test_id: id}, function(data) {
			$('#testcat').html(data); 
			$('#testcat').prop('disabled', false); 
			$('#parent_testcat').html(data); 
			$('#parent_testcat').prop('disabled', false); 
		});
	});
});