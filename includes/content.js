jQuery( function( $ ) {
    //$.fn.equalizeHeights = function(){
    //  return this.height(Math.max.apply(this, $(this).map(function(i,e){ return $(e).height() }).get() ) )
    //}
    //$('#main, #zusatzinfo').equalizeHeights();

    var contentHeight = $("#main").height();
    var sidebarHeight = $("#zusatzinfo").height();
    if( sidebarHeight+220 > contentHeight ) {
        $("#main").css({'height': sidebarHeight+'px', 'margin-bottom': '5em'});
    }
} );
