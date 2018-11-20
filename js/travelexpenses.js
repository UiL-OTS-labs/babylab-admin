$(function () {
    $('#city').change(function () {
        city = $(this).val();

        if(city.toLowerCase().trim() != 'utrecht')
            $('#travel_expenses_warning').show()
        else
            $('#travel_expenses_warning').hide()
    })
});