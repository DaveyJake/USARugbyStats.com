$( document ).ready(function() {

    $( "#drawer" ).click(function(){
        if($("#inner").hasClass("open")) {
            $( "#inner" ).removeClass( "open" );
            $( "#drawer" ).removeClass( "active" );
        } else {
            $( "#inner" ).addClass( "open" );
            $( "#drawer" ).addClass( "active" );
        }
    });

    $( "#standings .expand" ).click(function(){
        if($("#content").hasClass("col-sm-5")) {
            $( "#content" ).removeClass( "col-sm-5" );
            $( "#content" ).addClass( "col-sm-8" );
            $( "aside#standings" ).addClass( "col-sm-4" );
            $( "aside#standings" ).removeClass( "col-sm-7" );
            $( ".more" ).toggle();
        } else {
            $( "#content" ).addClass( "col-sm-5" );
            $( "#content" ).removeClass( "col-sm-8" );
            $( "aside#standings" ).addClass( "col-sm-7" );
            $( "aside#standings" ).removeClass( "col-sm-4" );
            $( ".more" ).toggle();
        }
    });

    $('.data').DataTable( {
    	paging: false,
    	responsive: {
            details: {
                type: 'column'          
            }
        }
	} );

    $('#team-view a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })

    $(".result-time span:contains('W')").css( "color", "#223850" );

    $("#schedule tbody tr, #standings tbody tr").click(function(){
     window.location=$(this).find("a").attr("href"); 
     return false;
    });

	
});