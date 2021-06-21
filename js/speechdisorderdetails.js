$(function() {
    $( "input[name='speechdisorderparent']" ).change(function() {
        let show = $( "input[name='speechdisorderparent']:checked" ).val() != "none";
        $( "#speechdisorderparent_details_group").toggle(show);
        $( "#speechdisorderparent_details_group").find('textarea').prop('required', show)
    });

    $( "input[name='speechdisorderparent']" ).change();
});
