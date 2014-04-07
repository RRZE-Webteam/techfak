//jQuery(document).ajaxSuccess(function(e, xhr, settings) {
//	var widget_id_base = 'links';
//	if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) {}
//});

jQuery( function( $ ) {
    var sidebarAllowance = {
        'sidebar-1': ['categories', 'links', 'meta', 'nav_menu', 'pages'],
        'sidebar-2': ['nav_menu'],
        'sidebar-4': ['archives', 'calendar', 'categories', 'links', 'meta', 'pages', 'recent-comments', 'recent-posts', 'rss', 'search', 'tag_cloud', 'text', 'twitter']
    };
    var sidebarLimits = {
        'sidebar-1': 10,
        'sidebar-2': 1,
        'sidebar-4': 10
    };
    var realSidebars = $( '#widgets-right div.widgets-sortables' );
    var availableWidgets = $( '#widget-list' ).children( '.widget' );

    var checkAllowance = function( sidebar, ui ) {
        var sidebarId = sidebar.id;
        if ( sidebarAllowance[sidebarId] === undefined ) {
            return;
        }

        if( ui === undefined ) {
            var widgets = $( sidebar ).sortable( 'toArray' );
            $.each(widgets, function() {
                var widget = this.replace(/^(widget-\d*_)/, '');
                widget = widget.replace(/(-\d*)$/, '');
                if ( $.inArray( widget, sidebarAllowance[sidebarId] ) == -1 ) {
                    $('#'+this).children('.widget-top').addClass('widget-notallowed');
                    $('#'+this).children('.widget-inside').removeClass('widget-inside').css('display', 'none');
                }
            });
            return;
        }

        var id = ui.item.attr( 'id' );
        var widget = id.replace(/^(widget-\d*_)/, '');
        widget = widget.replace(/-__i__/, '');
        widget = widget.replace(/(-\d*)$/, '');

        if ( $.inArray( widget, sidebarAllowance[sidebarId] ) > -1 ) {
            return;
        }

        if( id.search(/-__i__/) > -1 ) {
            $( sidebar ).stop().css("background-color", "#C43C43").animate({backgroundColor: "#FCFCFC"}, 1500);
        }
        var widget = $( sidebar ).find( 'div[id|='+id.replace(/-__i__/, '')+']' );
        widget.children('.widget-top').addClass('widget-notallowed');
        widget.children('.widget-inside').removeClass('widget-inside');

        return;
    }

    var checkLength = function( sidebar, delta ) {
        var sidebarId = sidebar.id;
        if ( undefined === sidebarLimits[sidebarId] ) {
            return;
        }

        // Find out how many widgets the sidebar already has
        var widgets = $( sidebar ).sortable( 'toArray' );
        $( sidebar ).toggleClass( 'sidebar-full', sidebarLimits[sidebarId] <= widgets.length + (delta || 0) );
        $( sidebar ).toggleClass( 'sidebar-morethanfull', sidebarLimits[sidebarId] < widgets.length + (delta || 0) );

        var notFullSidebars = $( 'div.widgets-sortables' ).not( '.sidebar-full' );

        availableWidgets.draggable( 'option', 'connectToSortable', notFullSidebars );
        realSidebars.sortable( 'option', 'connectWith', notFullSidebars );
    }

    // Check existing sidebars on startup
    realSidebars.map( function() {
        checkAllowance( this );
        checkLength( this );
    } );

    // Update when dragging to this (sort-receive)
    // and away to another sortable (sort-remove)
    realSidebars.bind( 'sortreceive sortremove', function( event, ui ) {
        checkAllowance( this, ui );
        checkLength( this );
    } );

    // Update when dragging back to the "Available/Inactive widgets" stack
    realSidebars.bind( 'sortstop', function( event, ui ) {
        var id = ui.item.attr('id')
        if ( ui.item.hasClass( 'deleting' ) ) {
            checkLength( this, -1 );
        } else {
            $('#wp_inactive_widgets').find('#'+id).children('.widget-top').removeClass('widget-notallowed');
        }
    } );

    // Update when the "Delete" link is clicked
    $( 'a.widget-control-remove' ).live( 'click', function() {
        checkLength( $( this ).closest( 'div.widgets-sortables' )[0], -1 );
    } );


} );
