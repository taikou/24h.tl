$delayPosts = false;

//******* SHOW POSTS CLICK
$(document).on('click','.news_post',function(e){ 
    
    $delayPosts = true;
    
    var param     = /^[0-9]+$/i;
	var _FirstId  = $('li.hoverList:first').attr('data');
	var sort      = sortAjax;
	
	if( !param.test( _FirstId ) ) {
		return false;
	}
	
	$('.news_post').fadeOut( 1 ).html(''); 
	
	$.get("public/ajax/timeline.discover.php", { since_id:_FirstId, _sort: sort }, function( data ) { 
		if ( data ) { 
			var total_data = data.posts.length; 
			
			for( var i = 0; i < total_data; ++i ) { 
				$( data.posts[i] ).hide().prependTo( '.posts' ).fadeIn( 900 ); 
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
				setTimeout(function(){
					$delayPosts = false;
				},500);
				}//<-- DATA 
			},'json'); 
		});
		
//<----- TimeLine
function TimeLine() {	
	
	var param     = /^[0-9]+$/i;
	var _FirstId  = $('li.hoverList:first').attr('data');
	var _list     = $('.content').html();
	var sort      = sortAjax;
	
	if( !param.test( _FirstId ) ) {
		return false;
	}
	
	if( _list != '' && $delayPosts == false ) {
		
		//****** COUNT DATA
		$.get("public/ajax/timeline.discover.php", { since_id:_FirstId, _sort: sort }, function( res ) {	
		if ( res ) {
			
			if( res.total != 0 ) {
				$('.news_post').html( res.total + ' ' + res.html ).fadeIn();
			}
		   }//<-- DATA
	     	
		},'json');
	}//<<<--- _list != '
}//End Function TimeLine
		
timer = setInterval("TimeLine()", 5000);