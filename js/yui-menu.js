YUI({
    classNamePrefix: 'pure'
}).use('gallery-sm-menu', function (Y) {

    var horizontalMenu = new Y.Menu({
        container         : '#horizontal-menu',
        sourceNode        : '#menu-items',
        orientation       : 'horizontal',
        hidedelay		  : 0,
        showdelay		  : 0,
        hideOnOutsideClick: false,
        hideOnClick       : false
    });

    horizontalMenu.render();
    horizontalMenu.show();

});