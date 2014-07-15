$(function() {
	$('#participant').autocomplete({
		minLength: 2,
  		source: 'participant/filter_participants',
		select: function( event, ui ) {
			$('#participant').val( ui.item.label );
			$('input[name="participant_id"]').val( ui.item.value );
			return false;
		}
	});
});