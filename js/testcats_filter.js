$(function() {
	$('#test').change(function() {
		var id = parseInt($(this).val());
		
		$.post('testcat/filter_testcats', {test_id: id}, function(data) {
			$('#testcat').html(data); 
			$('#testcat').prop('disabled', false); 
			$('#parent_testcat').html(data); 
			$('#parent_testcat').prop('disabled', false); 
		});
	});

	$('#test').change();
});