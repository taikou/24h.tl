//<----- TimeLine
function TimeLine() {	
	
	var param     = /^[0-9]+$/i;
	var _FirstId  = $('li.hoverList:first').attr('data');
	var _list     = $('.content').html();
	
	if( !param.test( _FirstId ) ) {
		return false;
	}
	
	if( _list != '' ) {
		
		//****** COUNT DATA
		$.get("public/ajax/timeline.php", { since_id:_FirstId }, function( res ) {	
		if ( res ) {
			
			if( res.total != 0 ) {
				$('.news_post').html( res.total + ' ' + res.html ).fadeIn();
				clear_ss();
			}
		   }//<-- DATA
	     	
		},'json');
	}//<<<--- _list != '
}//End Function TimeLine

//******* SHOW POSTS CLICK
$(document).on('click','.news_post',function(e){ 
    
    var param     = /^[0-9]+$/i;
	var _FirstId  = $('li.hoverList:first').attr('data');
	
	if( !param.test( _FirstId ) ) {
		return false;
	}
	
	$('.news_post').fadeOut( 1 ).html(''); 
	
	$.get("public/ajax/timeline.php", { since_id:_FirstId }, function( data ) { 
		if ( data ) { 
			var total_data = data.posts.length; 
			
			for( var i = 0; i < total_data; ++i ) { 
				$( data.posts[i] ).hide().prependTo( '.posts' ).fadeIn( 500 ); 
				} 
				//<**** - timeago
				jQuery("span.timeAgo").timeago(); 
				//<**** - tooltip
				$(".showTooltip").tooltip();
		
				refrech_contents();
						
				//<**** - readmore
				$('.p-text').readmore({
					maxHeight: 120,
					moreLink: '<a href="#">'+ReadMore+'</a>',
					lessLink: '<a href="#">'+ReadLess+'</a>',
					sectionCSS: 'display: block; width: 100%;',
					
				});
				}//<-- DATA 
			},'json'); 
		});
		
timer = setInterval("TimeLine()", 5000);