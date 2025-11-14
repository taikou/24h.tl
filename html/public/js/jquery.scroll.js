(function($) {

	$.fn.scrollPagination = function(options) {
		
		var settings = { 
			nop     : 10, // The number of posts per scroll to be loaded
			offset  : 0, // Initial offset, begins at 0 in this case
			error   : '', // When the user reaches the end this is the message that is
			                            // displayed. You can change this if you want.
			delay   : 500, // When you scroll down the posts will load after a delayed amount of time.
			               // This is mainly for usability concerns. You can alter this as you see fit
			scroll  : true, // The main bit, if set to false posts will not load as the user scrolls. 
			query   : 0,
			file    : null,
			totalGlobal : 0
			
			               // but will still load if the user clicks.
		}
		
		// Extend the options so they work with the plugin
		if(options) {
			$.extend(settings, options);
		}
		
		// For each so that we keep chainability.
		return this.each(function() {		
			
			// Some variables 
			$this = $(this);
			$settings = settings;
			var offset = $settings.offset;
			var query  = $settings.query;
			var busy = false; // Checks if the scroll action is happening 
			var MAX_LENGTH = $('body').attr('data-max');
			
			// Custom messages based on settings
			if($settings.scroll == true) $initmessage = '';
			else $initmessage = '';
			
			// Append custom messages and extra UI
			$this.append('<div class="content"></div><div class="loading-bar displayLoad"></div>');
			
			//=========== TRIM
			function trim( string ) {
				return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
			}
			
			function afterGetData(data){
					$this.find('.loading-bar').removeClass( 'displayLoad' );
					// Change loading bar content (it may have been altered)
					$this.find('.loading-bar').html($initmessage);
						
					// If there is no data returned, there are no more posts to be shown. Show error
					//$this.find('.loading-bar').html($settings.error);
					if( trim( data ).length < 2 ) { 
						$this.find('.loading-bar').remove();
						$('#container-loader').remove();
						
						if( $settings.totalGlobal != 0 ) {
							$this.find('.content').append('<div class="panel-footer text-center">'+AllLoaded+'</div>');
						}
												
					}
					else {
						//jAlert( data.length  );
						$('#container-loader, .load').remove();
						// Offset increases
					    //offset = offset+$settings.nop; 
						    
						// Append the data to the content div
					   	$this.find('.content').append(data);

						refrech_contents();

					   	$(".showTooltip").tooltip();
					   	
					   	$('.p-text').readmore({
						maxHeight: 120,
						moreLink: '<a href="#">'+ReadMore+'</a>',
						lessLink: '<a href="#">'+ReadLess+'</a>',
						sectionCSS: 'display: block; width: 100%;',
						
					});
					   	
					   	$(".media_galery").colorbox({
					   	rel:'galery'
					   	});
					   	
					   	//$("#reply_post").charCount({ allowed: MAX_LENGTH, warning: 10});
					   	
					   	$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				        $(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				        $(".soundcloud").colorbox({iframe:true, innerWidth:500, innerHeight:160, height: false });
				
				
					   	//<-- * TOTAL LI * -->
						var total   = $('.content > li').length;
						
						if( total < $settings.nop )
						{
							$this.find('.loading-bar').remove();
						}
						jQuery("span.timeAgo").timeago();
						// No longer busy!	
						
						offset = $('li.hoverList:last').attr('data');
						query  = 1;
						
						busy = false;
					}	
			}
			
			function getData() {
				if($settings.file=='get_all_post.php' && offset==0 && query==0){
					var keeptime = 300 // 5min.
					var ss_key='first_load_posts';

					var date = new Date();
					var timestamp = Math.floor(date.getTime()/1000);
					var ss = sessionStorage;
					if(ss.getItem(ss_key)!='' && ss.getItem(ss_key + '_time')>(timestamp - keeptime)){
						var data = ss.getItem(ss_key);
						afterGetData(data);
//		console.log('session');
					}else{

						// Post data to ajax.php
						$.get('public/ajax/'+$settings.file, {

							action        : 'scrollIndex',
							number        : $settings.nop,
							offset        : offset,
							query         : query,

						}, function(data) {
							ss.setItem(ss_key,data);
							ss.setItem(ss_key + '_time',timestamp);					
							afterGetData(data);
						});

	//					console.log('ajax');

					}				
				}else{
						// Post data to ajax.php
						$.get('public/ajax/'+$settings.file, {

							action        : 'scrollIndex',
							number        : $settings.nop,
							offset        : offset,
							query         : query,

						}, function(data) {
							afterGetData(data);
						});
				}


			}	
			
			getData(); // Run function initially
			
			// If scrolling is enabled
			if($settings.scroll == true) {
				// .. and the user is scrolling
				$(window).scroll(function() {
					
					$('#lightboxOverlay').height($(document).height());
					// Check the user is at the bottom of the element
					if($(window).scrollTop() + $(window).height() > $this.height() && !busy) {
						
						
						// Now we are working, so busy is true
						busy = true;
						
						if( $settings.totalGlobal < $settings.nop )
						{
							return false;
						}
						// Tell the user we're loading posts
						//$this.find('.loading-bar').html('Loading...');
						
						// Run the function to fetch the data inside a delay
						// This is useful if you have content in a footer you
						// want the user to see.
						setTimeout(function() {
							
							getData();
							
						}, $settings.delay);
							
					}	
				});
			}
			
			// Also content can be loaded by clicking the loading bar/
			$this.find('.loading-bar').click(function() {
			
				if(busy == false) {
					busy = true;
					//$this.find('.loading-bar').html('Loading...');
					setTimeout(function() {
							
							getData();
							
						}, $settings.delay);
				}
			
			});
			
		});
	}

})(jQuery);
