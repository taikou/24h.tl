var urlbase    = $('base').attr('href');
$("input").attr('autocomplete','off');
//<<<-- POPOUT --->>>>
$('.popout').hover(function(){
	$(this).fadeOut();
});

(function(c){function g(b,a){this.element=b;this.options=c.extend({},h,a);c(this.element).data("max-height",this.options.maxHeight);c(this.element).data("height-margin",this.options.heightMargin);delete this.options.maxHeight;if(this.options.embedCSS&&!k){var d=".readmore-js-toggle, .readmore-js-section { "+this.options.sectionCSS+" } .readmore-js-section { overflow: hidden; }",e=document.createElement("style");e.type="text/css";e.styleSheet?e.styleSheet.cssText=d:e.appendChild(document.createTextNode(d));
document.getElementsByTagName("head")[0].appendChild(e);k=!0}this._defaults=h;this._name=f;this.init()}var f="readmore",h={speed:100,maxHeight:200,heightMargin:16,moreLink:'<a href="#">Read More</a>',lessLink:'<a href="#">Close</a>',embedCSS:!0,sectionCSS:"display: block; width: 100%;",startOpen:!1,expandedClass:"readmore-js-expanded",collapsedClass:"readmore-js-collapsed",beforeToggle:function(){},afterToggle:function(){}},k=!1;g.prototype={init:function(){var b=this;c(this.element).each(function(){var a=
c(this),d=a.css("max-height").replace(/[^-\d\.]/g,"")>a.data("max-height")?a.css("max-height").replace(/[^-\d\.]/g,""):a.data("max-height"),e=a.data("height-margin");"none"!=a.css("max-height")&&a.css("max-height","none");b.setBoxHeight(a);if(a.outerHeight(!0)<=d+e)return!0;a.addClass("readmore-js-section "+b.options.collapsedClass).data("collapsedHeight",d);a.after(c(b.options.startOpen?b.options.lessLink:b.options.moreLink).on("click",function(c){b.toggleSlider(this,a,c)}).addClass("readmore-js-toggle"));
b.options.startOpen||a.css({height:d})});c(window).on("resize",function(a){b.resizeBoxes()})},toggleSlider:function(b,a,d){d.preventDefault();var e=this;d=newLink=sectionClass="";var f=!1;d=c(a).data("collapsedHeight");c(a).height()<=d?(d=c(a).data("expandedHeight")+"px",newLink="lessLink",f=!0,sectionClass=e.options.expandedClass):(newLink="moreLink",sectionClass=e.options.collapsedClass);e.options.beforeToggle(b,a,f);c(a).animate({height:d},{duration:e.options.speed,complete:function(){e.options.afterToggle(b,
a,f);c(b).replaceWith(c(e.options[newLink]).on("click",function(b){e.toggleSlider(this,a,b)}).addClass("readmore-js-toggle"));c(this).removeClass(e.options.collapsedClass+" "+e.options.expandedClass).addClass(sectionClass)}})},setBoxHeight:function(b){var a=b.clone().css({height:"auto",width:b.width(),overflow:"hidden"}).insertAfter(b),c=a.outerHeight(!0);a.remove();b.data("expandedHeight",c)},resizeBoxes:function(){var b=this;c(".readmore-js-section").each(function(){var a=c(this);b.setBoxHeight(a);
(a.height()>a.data("expandedHeight")||a.hasClass(b.options.expandedClass)&&a.height()<a.data("expandedHeight"))&&a.css("height",a.data("expandedHeight"))})},destroy:function(){var b=this;c(this.element).each(function(){var a=c(this);a.removeClass("readmore-js-section "+b.options.collapsedClass+" "+b.options.expandedClass).css({"max-height":"",height:"auto"}).next(".readmore-js-toggle").remove();a.removeData()})}};c.fn[f]=function(b){var a=arguments;if(void 0===b||"object"===typeof b)return this.each(function(){if(c.data(this,
"plugin_"+f)){var a=c.data(this,"plugin_"+f);a.destroy.apply(a)}c.data(this,"plugin_"+f,new g(this,b))});if("string"===typeof b&&"_"!==b[0]&&"init"!==b)return this.each(function(){var d=c.data(this,"plugin_"+f);d instanceof g&&"function"===typeof d[b]&&d[b].apply(d,Array.prototype.slice.call(a,1))})}})(jQuery);


//=========== TRIM
function trim ( cadena ) {
	return cadena.replace(/^\s+/g,'').replace(/\s+$/g,'')
}
//================= * Remove focus on click * ===================//
$('.btn, li.dropdown a').click(function() {
	$(this).blur();
});

//================= * Dropdown Click * ===================//
$('.dropdown-menu').not('#setting-actions').on({
    "click":function(e){
      e.stopPropagation();
    }
});

//================= * Input Click * ===================//
$('#upload-btn').click(function () {
	var _this = $(this);
    $("input[type='file'][name='photo_upload']").trigger('click');
     _this.blur();
});

$( "#emoticons" ).click(function() {
  $( "#emoticons-panel" ).slideToggle( 500, function() {
    // Animation complete.
  });
  $( this ).toggleClass( "btn-share-active" );
});

$( "#demojis" ).click(function() {
  $( "#demoji-panel" ).slideToggle( 500, function() {
    // Animation complete.
  });
  $( this ).toggleClass( "btn-share-active" );
});

$('.reply-user').click(function(){

    var username = $(this).attr('data-user');
    $('#reply_post').val($('#reply_post').focus().val()+" "+username+" ");
    
    scrollElement( '#reply_post' );

});

/*
 * 
 * Navigating lists
 var li = $('li');
var liSelected;
$(window).keydown(function(e){
    if(e.which === 40){
        if(liSelected){
            liSelected.removeClass('selected');
            
            $('#btnItems').blur();
            
            next = liSelected.next();
            if(next.length > 0){
                liSelected = next.addClass('selected');
            }else{
                liSelected = li.eq(0).addClass('selected');
            }
        }else{
            liSelected = li.eq(0).addClass('selected');
        }
    }else if(e.which === 38){
        if(liSelected){
            liSelected.removeClass('selected');
            next = liSelected.prev();
            if(next.length > 0){
                liSelected = next.addClass('selected');
            }else{
                liSelected = li.last().addClass('selected');
            }
        }else{
            liSelected = li.last().addClass('selected');
        }
    }
    
});
*/
$(document).on('mouseenter','.deletePhoto, .deleteCover, .deleteBg', function(){

   	 var _this   = $(this);
   	 $(_this).html('<div class="photo-delete"></div>');
 });
 
 $(document).on('mouseleave','.deletePhoto, .deleteCover, .deleteBg', function(){

   	 var _this   = $(this);
   	 $(_this).html('');
 });
   	
//<--------- waiting -------//>
(function($){
$.fn.waiting = function( p_delay ){
	var $_this = this.first();
	var _return = $.Deferred();
	var _handle = null;

	if ( $_this.data('waiting') != undefined ) {
		$_this.data('waiting').rejectWith( $_this );
		$_this.removeData('waiting');
	}
	$_this.data('waiting', _return);

	_handle = setTimeout(function(){
		_return.resolveWith( $_this );
	}, p_delay );

	_return.fail(function(){
		clearTimeout(_handle);
	});

	return _return.promise();
};
})(jQuery);

//================= * SCROLL ELEMENT * ===================//
				function scrollElement( element ){
					var offset = $(element).offset().top;
 					$('html, body').animate({scrollTop:offset}, 500);
				};

//** jQuery Scroll to Top Control script- (c) Dynamic Drive DHTML code library: http://www.dynamicdrive.com.
//** Available/ usage terms at http://www.dynamicdrive.com (March 30th, 09')
//** v1.1 (April 7th, 09'):
//** 1) Adds ability to scroll to an absolute position (from top of page) or specific element on the page instead.
//** 2) Fixes scroll animation not working in Opera. 
$(document).ready(function() {
if (typeof(scrool_top_icon)!='undefined' && scrool_top_icon=='write') var controlhtml='<img src="public/img/emoji/1f58a.png" class="goTop" />';
else var controlhtml='<img src="public/img/top.png" class="goTop" />';
var templatepath = $("#templatedirectory").html();
var scrolltotop={
	//startline: Integer. Number of pixels from top of doc scrollbar is scrolled before showing control
	//scrollto: Keyword (Integer, or "Scroll_to_Element_ID"). How far to scroll document up when control is clicked on (0=top).
	setting: {startline:100, scrollto: 0, scrollduration:1000, fadeduration:[700, 500]},
	controlHTML: controlhtml, //HTML for control, which is auto wrapped in DIV w/ ID="topcontrol"
	controlattrs: {offsetx:15, offsety:12}, //offset of control relative to right/ bottom of window corner
	anchorkeyword: '#top', //Enter href value of HTML anchors on the page that should also act as "Scroll Up" links

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) //if control is positioned using JavaScript
			this.$control.css({opacity:0}) //hide control immediately after clicking it
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto)
		if (typeof dest=="string" && jQuery('#'+dest).length==1) //check element set by string exists
			dest=jQuery('#'+dest).offset().top
		else
			dest=0
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		var $window=jQuery(window)
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety
		this.$control.css({left:controlx+'px', top:controly+'px'})
	},

	togglecontrol:function(){
		var scrolltop=jQuery(window).scrollTop()
		if (!this.cssfixedsupport)
			this.keepfixed()
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0])
			this.state.isvisible=true
		}
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1])
			this.state.isvisible=false
		}
	},
	
	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop
			var iebrws=document.all
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body')
			mainobj.$control=$('<div id="topcontrol" class="tooltip">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:0, opacity:0, 'z-index': 50, cursor:'pointer'})
				.click(function(){mainobj.scrollup(); return false})
				.appendTo('body')
			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') //loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}) //IE6- seems to require an explicit width on a DIV containing text
			mainobj.togglecontrol()
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup()
				return false
			})
			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol()
			})
		})
	}
}

scrolltotop.init();
});

//==================================================//
//=               *  TOOGLE MENU *               =//
//==================================================//
	$('.toogle').click(function() {
		$('.boxLogin').slideToggle(2);
		$(this).addClass('active');
		$('#user').focus();
		return false
	});
	
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if ( !$clicked.parents().hasClass("toogle") 
		&& !$clicked.parents().hasClass("form_login") 
		&& !$clicked.hasClass("form_login") 
		&& !$clicked.hasClass( 'button_class' )
		)
		{
			$(".boxLogin").slideUp(1);
			$('.toogle').removeClass('active');
		}
	});
	
	$('.reply-button').click(function() {
		
		scrollElement( '#reply_post' );
		$('#reply_post').focus();
	});

	
$(document).on('mouseenter', '.follow_active' ,function(){
	
	var unfollow  = $(this).attr('data-unfollow');
	var following = $(this).attr('data-following');
	
	$(this).html( '<i class="icon-user3 myicon-right"></i> ' + unfollow);
	$(this).addClass('btn-danger').removeClass('btn-info');
	 })
	 
	$(document).on('mouseleave', '.follow_active' ,function()
	 {
	 	var unfollow  = $(this).attr('data-unfollow');
		var following = $(this).attr('data-following');
	 	$(this).html( '<i class="icon-user3 myicon-right"></i> ' + following);
	 	$(this).removeClass('btn-danger').addClass('btn-info');
	 });

//==================================================//
//=               *  logout *               =//
//==================================================//
$('.logout').click(function(){
	
	var time = 50;
	var out  = 'logout=out';
	
	$('body').click(false);
	
	$('body').keydown(function (event) {

	    if( event.which  == 116 || event.which  == 27  ) {
	     	return false;   
	    }
   });//======== FUNCTION 
   
	setTimeout(function(){
	$.ajax({
		
		type: 'GET',
		url: 'public/ajax/logout.php',
		data: out,
		success:function( msj )
		{
			if ( msj == 'OK' )
			{
				window.location.reload();
	//			window.location.href('/');
			}
		},// success
		error:function(){
			bootbox.alert("Error");
			}
	});
	
	},time);
});
//===================================================//
//=                  LIVEQUERY                      =//
//===================================================//
(function($) {
$.extend($.fn, {
	livequery: function(type, fn, fn2) {
		var self = this, q;

		// Handle different call patterns
		if ($.isFunction(type))
			fn2 = fn, fn = type, type = undefined;

		// See if Live Query already exists
		$.each( $.livequery.queries, function(i, query) {
			if ( self.selector == query.selector && self.context == query.context &&
				type == query.type && (!fn || fn.$lqguid == query.fn.$lqguid) && (!fn2 || fn2.$lqguid == query.fn2.$lqguid) )
					// Found the query, exit the each loop
					return (q = query) && false;
		});

		// Create new Live Query if it wasn't found
		q = q || new $.livequery(this.selector, this.context, type, fn, fn2);

		// Make sure it is running
		q.stopped = false;

		// Run it immediately for the first time
		q.run();

		// Contnue the chain
		return this;
	},

	expire: function(type, fn, fn2) {
		var self = this;

		// Handle different call patterns
		if ($.isFunction(type))
			fn2 = fn, fn = type, type = undefined;

		// Find the Live Query based on arguments and stop it
		$.each( $.livequery.queries, function(i, query) {
			if ( self.selector == query.selector && self.context == query.context &&
				(!type || type == query.type) && (!fn || fn.$lqguid == query.fn.$lqguid) && (!fn2 || fn2.$lqguid == query.fn2.$lqguid) && !this.stopped )
					$.livequery.stop(query.id);
		});

		// Continue the chain
		return this;
	}
});

$.livequery = function(selector, context, type, fn, fn2) {
	this.selector = selector;
	this.context  = context;
	this.type     = type;
	this.fn       = fn;
	this.fn2      = fn2;
	this.elements = [];
	this.stopped  = false;

	// The id is the index of the Live Query in $.livequery.queries
	this.id = $.livequery.queries.push(this)-1;

	// Mark the functions for matching later on
	fn.$lqguid = fn.$lqguid || $.livequery.guid++;
	if (fn2) fn2.$lqguid = fn2.$lqguid || $.livequery.guid++;

	// Return the Live Query
	return this;
};

$.livequery.prototype = {
	stop: function() {
		var query = this;

		if ( this.type )
			// Unbind all bound events
			this.elements.unbind(this.type, this.fn);
		else if (this.fn2)
			// Call the second function for all matched elements
			this.elements.each(function(i, el) {
				query.fn2.apply(el);
			});

		// Clear out matched elements
		this.elements = [];

		// Stop the Live Query from running until restarted
		this.stopped = true;
	},

	run: function() {
		// Short-circuit if stopped
		if ( this.stopped ) return;
		var query = this;

		var oEls = this.elements,
			els  = $(this.selector, this.context),
			nEls = els.not(oEls);

		// Set elements to the latest set of matched elements
		this.elements = els;

		if (this.type) {
			// Bind events to newly matched elements
			nEls.bind(this.type, this.fn);

			// Unbind events to elements no longer matched
			if (oEls.length > 0)
				$.each(oEls, function(i, el) {
					if ( $.inArray(el, els) < 0 )
						$.event.remove(el, query.type, query.fn);
				});
		}
		else {
			// Call the first function for newly matched elements
			nEls.each(function() {
				query.fn.apply(this);
			});

			// Call the second function for elements no longer matched
			if ( this.fn2 && oEls.length > 0 )
				$.each(oEls, function(i, el) {
					if ( $.inArray(el, els) < 0 )
						query.fn2.apply(el);
				});
		}
	}
};

$.extend($.livequery, {
	guid: 0,
	queries: [],
	queue: [],
	running: false,
	timeout: null,

	checkQueue: function() {
		if ( $.livequery.running && $.livequery.queue.length ) {
			var length = $.livequery.queue.length;
			// Run each Live Query currently in the queue
			while ( length-- )
				$.livequery.queries[ $.livequery.queue.shift() ].run();
		}
	},

	pause: function() {
		// Don't run anymore Live Queries until restarted
		$.livequery.running = false;
	},

	play: function() {
		// Restart Live Queries
		$.livequery.running = true;
		// Request a run of the Live Queries
		$.livequery.run();
	},

	registerPlugin: function() {
		$.each( arguments, function(i,n) {
			// Short-circuit if the method doesn't exist
			if (!$.fn[n]) return;

			// Save a reference to the original method
			var old = $.fn[n];

			// Create a new method
			$.fn[n] = function() {
				// Call the original method
				var r = old.apply(this, arguments);

				// Request a run of the Live Queries
				$.livequery.run();

				// Return the original methods result
				return r;
			}
		});
	},

	run: function(id) {
		if (id != undefined) {
			// Put the particular Live Query in the queue if it doesn't already exist
			if ( $.inArray(id, $.livequery.queue) < 0 )
				$.livequery.queue.push( id );
		}
		else
			// Put each Live Query in the queue if it doesn't already exist
			$.each( $.livequery.queries, function(id) {
				if ( $.inArray(id, $.livequery.queue) < 0 )
					$.livequery.queue.push( id );
			});

		// Clear timeout if it already exists
		if ($.livequery.timeout) clearTimeout($.livequery.timeout);
		// Create a timeout to check the queue and actually run the Live Queries
		$.livequery.timeout = setTimeout($.livequery.checkQueue, 20);
	},

	stop: function(id) {
		if (id != undefined)
			// Stop are particular Live Query
			$.livequery.queries[ id ].stop();
		else
			// Stop all Live Queries
			$.each( $.livequery.queries, function(id) {
				$.livequery.queries[ id ].stop();
			});
	}
});
// Register core DOM manipulation methods
$.livequery.registerPlugin('append', 'prepend', 'after', 'before', 'wrap', 'attr', 'removeAttr', 'addClass', 'removeClass', 'toggleClass', 'empty', 'remove', 'html');

// Run Live Queries when the Document is ready
$(function() { $.livequery.play(); });

})(jQuery);


		
	//==================== EXPAND ========================//
	$(document).on('click','a.expand',function() {
		
		var _this   = $(this);
		var _hide   = _this.attr('data-hide');
		
		_this.addClass('activeSpan');
		_this.parents('li').find( '.details-post' ).slideDown(); 
		//_this.parents('li').find('.grid-reply').slideDown(); 
		//_this.parents('li').find('.spanReply').slideDown();		
		_this.parent().find('.textEx').html(_hide); 
		_this.removeClass('expand');
		
		if( _this.hasClass( 'reply' ) ){
			_this.parents().find('#reply_post').focus();
		}
	});
	//==================== Hide ========================//
	$(document).on('click','a.activeSpan',function() {
		
		var _this   = $(this);
		var _expand = _this.attr('data-expand');
		
		_this.addClass('expand');
		_this.parents('li').find('.details-post').slideUp();
		_this.parent('li').find('.grid-reply').slideUp();
		_this.parent('li').find('.spanReply').slideUp(); 
		_this.parent().find('.textEx').html(_expand); 
		_this.removeClass('activeSpan');
	});
	
	$('.optionsUser > li:last').css({'border':'none'});
	
	//========== SEND MESSAGE
	$('.sendMessage').click(function() {
		var element     = $(this);
		var _thisText   = $(this).html();
		var _thisUser   = $(this).attr('data-username');
		var id_user     = $(this).attr( 'data-id' );
		var dataSend    = $(this).attr('data-send');
		var _document   = $('body');
		
		 /* Reposition Modal */
		function _repositionBox(){ 
			var verticalOffset = -75;
			var left = (($(window).width() / 2) - ($(".popoutUser_message").outerWidth() / 2));
			if( left < 0 ) { left = 0; } 
			$(".popoutUser_message").css({
				left: left + 'px' 
				});
			}
			//<--- * REPOSITION POPOUT * ---->
			_repositionBox();
			$(window).bind('resize', _repositionBox );
   
		$('#boxSettings').slideUp(1);
		$('.settings_user').removeClass('activeClass');
		
		$('#container_popout_message').fadeIn( 1 );
		$('.textPopout_message').html( _thisText+' &rsaquo; @' + _thisUser );
		$('.content_user_message').html('<div id="grid_post"> <form action="" method="post" accept-charset="UTF-8" id="send_msg_profile"><input type="hidden" name="id_user" id="id_user" value="'+id_user+'" /><textarea name="message" id="message"></textarea> <button id="button_message" disabled="disabled" style="opacity: 0.5; cursor: default;" type="submit">'+dataSend+'</button> <div data-max="140" id="counter"></div> </form> <span class="notfound" style="display:none; width: 500px; padding: 0; overflow: hidden; text-align: center;"></span></div><!-- grid_post -->');
		
		$('.popoutUser_message').fadeIn( 500 );
		$('#message').focus();
		
            _document.addClass('scroll_none');
            
            // ESC
            _document.keydown(function (event) {
             if( event.which  == 27  ) {
             	$('.content_user_message, .textPopout_message').html('');
             	_document.removeClass('scroll_none').removeAttr('class');
             	$('#container_popout_message, .popoutUser_message').fadeOut( 1 );
             }
     		});//======== FUNCTION 
     		
     		// BIND CLICK
	     	$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if ( !$clicked.parents().hasClass("popoutUser_message") && !$clicked.hasClass("popoutUser_message") ) {
				$('.content_user_message, .textPopout_message').html('');
				_document.removeClass('scroll_none').removeAttr('class');
				$('#container_popout_message, .popoutUser_message').fadeOut( 1 );
			}
		  });
		  
		  // CLOSE BUTTON
		  $('.close_popout_message').click(function(){
		  	$('.content_user_message, .title_popout_message').html('');
     		_document.removeClass('scroll_none').removeAttr('class');
     		$('#container_popout_message, .popoutUser_message').fadeOut( 1 );
     	  });//<----
            
     	  return false;
	});//<<<--- SEND MESSAGE
	
	
			/*=============== SEND REPLY ===================*/	
			$(document).on('click','#button_reply',function(s){
				
				s.preventDefault();
				
				var element     = $(this);
				var error       = false;
				var _reply_post = element.parents('li').find('#reply_post').val();
				var saveHtml    =  element.parents('li').find('#button_reply').html();
			 	var _wait       = '...';
			 	var _saveHtml   = saveHtml + _wait;
						
				if( trim( _reply_post ) == '' && trim( _reply_post ).length  == 0 ){
					var error = true;
					return false;
				}
				

				if( error == false ){
					element.parents('li').find('#button_reply').attr({'disabled' : 'true'}).html(_saveHtml).css({'opacity':'0.5','cursor':'default'});
					
					$.post("public/ajax/replyPost.php", element.parents('li').find("#form_reply_post").serialize(), function(msg){
						
						if( msg.length != 0 ){
							element.parents('li').find( '.grid-media' ).append( msg );
							 jQuery("span.timeAgo").timeago();
							 element.parents('li').find('#reply_post').val('');
							 element.parents('li').find('#button_reply').html(saveHtml);
							 $('.showTooltip').tooltip();
							 $('.p-text').readmore({
								maxHeight: 120,
								moreLink: '<a href="#">'+ReadMore+'</a>',
								lessLink: '<a href="#">'+ReadLess+'</a>',
								sectionCSS: 'display: block; width: 100%;',
								
							});
						} 
					});//<-- END DE $POST AJAX
				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
			
			
			/*=============== SEND REPLY ===================*/	
			$('#button-reply-status').click(function(s){
				
				s.preventDefault();
				
				var element     = $(this);
				var error       = false;
				var _reply_post = $('#reply_post').val();
				var saveHtml    = $(this).html();
			 	var _wait       = '...';
			 	var _saveHtml   = saveHtml + _wait;
				
				if( trim( _reply_post ) == '' && trim( _reply_post ).length  == 0 ){
					var error = true;
					return false;
				}
				

				if( error == false ){
					$('#button-reply-status').attr({'disabled' : 'true'}).html(_saveHtml).css({'opacity':'0.5','cursor':'default'});
					
					$.post("public/ajax/replyPost.php", $("#form_reply_post").serialize(), function(msg){
						
						if( msg.length != 0 ) {
							$( msg ).hide().appendTo('#reply-status-wrap').fadeIn( 500 );
							 jQuery("span.timeAgo").timeago();
							 $('#reply_post').val('');
							 $('#button-reply-status').html(saveHtml);
							 $('.showTooltip').tooltip();
							 
						} else {
							$('#button-reply-status').attr({'disabled' : 'true'});
						}
					});//<-- END DE $POST AJAX
				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
			
			
			$(document).on('click','#button_message',function(s){
				s.preventDefault();
				
				var element     = $(this);
				var error       = false;
				var _message    = $('#message').val();
				var dataWait    = $('.msgModal').attr('data-wait');
				var dataSuccess = $('.msgModal').attr('data-success');
				var dataSent    = $('.msgModal').attr('data-send');
				var dataError   = $('.msgModal').attr('data-error');
				
				if( _message == '' && trim( _message ).length  == 0 )
				{
					var error = true;
					return false;
				}
				

				if( error == false ){
					$('#button_message').attr({'disabled' : 'true'}).html(dataWait).css({'opacity':'0.5','cursor':'default'});
					
					$.post("public/ajax/send_message.php", $("#send_msg_profile").serialize(), function(msg){
						
						if( msg.length != 0 ){
							 $('#message').val('');
							 $('#button_message').html(dataSent);
							 $('.popout').html(dataSuccess).fadeIn(500).delay(4000).fadeOut();
							 $('body').removeClass('scroll_none').removeAttr('class');
							 $('#myModal').modal('hide');
						}
						else
						{
							$('.popout').html(dataError).fadeIn(500).delay(4000).fadeOut();
						}
						
					});//<-- END DE $POST AJAX
				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
			
			
	//=========== ADD POST
	$('textarea#add_post').keyup(function(){
		
		var $allowed = $('body').attr('data-max');
		var _photoId   = $('input#photoId').val();
		
		if ( trim( $(this).val() ).length >= 1 && trim( $(this).val() ).length <= $allowed  )
		{
			$('#button_add').removeAttr('disabled').css({'opacity':'1','cursor':'pointer'});
			return false;
		}
		else if( trim( $(this).val() ).length == 0 && _photoId.length != 0 )
		{
			$('#button_add').removeAttr('disabled').css({'opacity':'1','cursor':'pointer'});
			return false;
		}
		else
		{
			$('#button_add').attr({'disabled' : 'true'}).css({'opacity':'0.5','cursor':'default'});
			return false;
		}
	});
	
	//=========== REPLY POST
	$(document).on('keyup','textarea#reply_post, textarea#reply_msg',function(){
		
		var $allowed   = $('body').attr('data-max');
		
		if ( trim( $(this).val() ).length >= 1 && trim( $(this).val() ).length <= $allowed )
		{
			$(this).parent().find('#button_reply, #button-reply-status, #button-reply-msg').removeAttr('disabled').css({'opacity':'1','cursor':'pointer'});
			return false;
		}
		else
		{
			$(this).parent().find('#button_reply, #button-reply-status, #button-reply-msg').attr({'disabled' : 'true'}).css({'opacity':'0.5','cursor':'default'});
			return false;
		}
	});
	
	//=========== MESSAGE
	$(document).on('keyup','#message',function(){
		
		var $allowed   = $('body').attr('data-max');
		
		if ( trim( $(this).val() ).length >= 1 && trim( $(this).val() ).length <= $allowed )
		{
			$(this).parents().find('#button_message').removeAttr('disabled').css({'opacity':'1','cursor':'pointer'});
			return false;
		}
		else
		{
			$(this).parents().find('#button_message').attr({'disabled' : 'true'}).css({'opacity':'0.5','cursor':'default'});
			return false;
		}
	});
	
	function isValidURL(url){
    	var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

	    if(RegExp.test(url)){
	        return true;
	    }else{
	        return false;
	    }
	    }
	    
	    var $thtml = $('#add_post').html();

  		  
$(document).ready(function(){
	
	/*= DELETE POST =*/
	$(document).on('click',".trash",function(){
	var element   = $(this);
	var id        = element.attr("data");
	var token_id  = element.attr("data-token");
	var _title    = element.attr("data-message");
	var _confirm  = element.attr('data-confirm');
	var image     = element.attr("data-image");
	var info      = 'id=' + id + '&token=' + token_id + '&image=' + image;
	var lists     = $('li.hoverList').length;
		
	bootbox.confirm( _title, function( r ) {
	
	if( r == true ) {
	
	if( lists == 1 ) {
		 $('#button_add').removeClass('post-user-profile');
	}
	    
	     
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/delete_post.php",
		   dataType: 'json',
		   data: info,
		   success: function( data ){
		   if( data.status == 'ok' ) { 
		   	element.parents('li').fadeTo(200,0.00, function(){
   		        element.parents('li').slideUp(200, function(){
   		  	     element.parents('li').remove();
				 clear_ss();
   		       });
   		      });
		   }//<-- IF
			   else {
			   	bootbox.alert(data.res);
			   }
		    }//<-- RESULT 
	      });//<--- AJAX

	    }//END IF R TRUE 
	 
	  }); //Jconfirm  
	      
});//<--- Click
	  
	  /*= DELETE POST =*/
	$(".trashStatus").click(function(){
	var element   = $(this);
	var id        = element.attr("data");
	var token_id  = element.attr("data-token");
	var image     = element.attr("data-image");
	var _title    = element.attr("data-message");
	var _confirm  = element.attr('data-confirm');
	var info      = 'id=' + id + '&token=' + token_id + '&image=' + image;
	var url       = $('.username-posts').attr('href');
	
	bootbox.confirm( _title, function( r ) {
	
	     if( r == true )
	     {
	
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/delete_post.php",
		   data: info,
		   dataType: 'json',
		   success: function( data ){
		   if( data.status == 'ok' )
		   { 
		   	  window.location.href = url;
		   }//<-- IF
			   else {
			   	bootbox.alert(data.res);
			   }
		 }//<-- RESULT 
	   });//<--- AJAX

	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
	      
});//<--- Click
	  
	  /*= ADD_FAV =*/
	$(document).on('click',".favorite",function(){
	var element   = $(this);
	var id        = element.attr("data");
	var token_id  = element.attr("data-token");
	var _favorite = element.attr('data-fav');
	var favorited = element.attr('data-fav-active');
	var favs      = element;
	var info      = 'id=' + id + '&token=' + token_id;
	var timeQuery = 1000;
	
	//element.removeClass( 'favorite' );
	//$('.popout').html('Wait...').fadeIn();
	
	if( favs.hasClass( 'favorited' ) ) {
		   	  element.removeClass('favorited');
		   	  element.parents('li').find('.add_fav').remove();
		   	  element.html( '<span class="fa fa-thumbs-o-up myicon-right"></span> ' + _favorite);
		   	  $('.statusProfile').find('.add_fav').remove();
		}
		else {
			
		   	  element.addClass('favorited');
		   	  element.html('<span class="fa fa-thumbs-o-up myicon-right"></span> ' + favorited);
		   	  element.parents('li').append('<span class="add_fav"></span>');
		   	  $('.statusProfile').append('<span class="add_fav"></span>');
		}
		   	
	setTimeout(function(){
		
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/favorites.php",
		   data: info,
		   success: function( result ){
		   	
		   	if( result == '') {
			   	 window.location.reload();
			   	  element.removeClass('favorited');
			   	  element.parents('li').find('.add_fav').remove();
			   	   element.html( '<span class="fa fa-thumbs-o-up myicon-right"></span> ' + _favorite);
			   	  $('.statusProfile').find('.add_fav').remove();
		   	}
		 }//<-- RESULT 
	   });//<--- AJAX

	},timeQuery );
	      
});//<----- CLICK

  /*= REPOST =*/
	$(document).on('click',".repost_button",function(){
	var element   = $(this);
	var id        = element.attr("data");
	var token_id  = element.attr("data-token");
	var _repost   = element.attr("data-fav");
	var reposted  = element.attr("data-rep-active");
	var reps      = element.parent().find('span');
	var info      = 'id=' + id + '&token=' + token_id;
	var timeQuery = 1000;
	
	//element.removeClass( 'repost_button' );
	
	if( element.hasClass( 'repostedSpan' ) ) {
		   	  element.removeClass('repostedSpan');
		   	  element.html( '<span class="fa fa-retweet myicon-right"></span> ' + _repost);
		}
		else {
			
		   	  element.addClass('repostedSpan');
		   	  element.html('<span class="fa fa-retweet myicon-right"></span> ' + reposted);
		}
		   	
	setTimeout(function(){
		
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/reposted.php",
		   data: info,
		   success: function( result ) {
		   	
		   	if( result == '') {
			   	 window.location.reload();
		   	}
		 }//<-- RESULT 
	   });//<--- AJAX

	},timeQuery );
	      
});//<----- CLICK

    /*= FOLLOW =*/
	$(document).on('click',".whofollow",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	var username   = element.attr('data-username');
	var info       = 'id=' + id;
	var timeQuery  = 100;
	
	element.removeClass( 'whofollow' );
	
	setTimeout(function(){
		
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/follow.php",
		   data: info,
		   dataType : 'json',
		   success: function( result ){
		   if( result.status == 1 )
		   { 
		   	 element.addClass( 'whofollow' );
		   	  element.addClass( 'btn-info' ).removeClass( 'btn-default' );
		   	 $('.popout').html( _following + ' @' + username ).fadeIn().delay(2500).fadeOut();
		   	 element.html( '<i class="icon-user3 myicon-right"></i>' + _following + ' @' + username );
		   }
		   else if(  result.status == 2 )
		   {
		   	 element.addClass( 'whofollow' );
		   	 element.removeClass( 'btn-info' ).addClass( 'btn-default' );
		     element.html( '<i class="icon-user3 myicon-right"></i> ' + _follow + ' @' + username );
		   }
		   else if(  result.status == 3 )
		   {
		   	 element.addClass( 'whofollow' );
		   	  element.addClass( 'btn-info' ).removeClass( 'btn-default' );
		   	 $('.popout').html( _following + ' @' + username ).fadeIn().delay(2500).fadeOut();
		   	 element.html( '<i class="icon-user3 myicon-right"></i>' + _following + ' @' + username );
		   }
		   //<-- IF
			   else {
			   	 bootbox.alert(result.error);
			   	 $('.popout').fadeOut();
			   }
		 }//<-- RESULT 
	   });//<--- AJAX

	 
	},timeQuery );
	      
});//<----- CLICK
	  
	  $(document).on('click','.getData',function(){
	  	 	
	  	var element = $(this);
	  	element.removeClass( 'getData' );
	  	$postId     = element.attr('data');
	  	$tokenId    = element.attr('data-token');
	  	$data       = 'postId='+ $postId +'&token=' + $tokenId;
	  	
	  	$.ajax({ 
	  		  
	  		  type     : 'GET',
	  		  url      : 'public/ajax/getData.php',
	  		  dataType : 'json',
	  		  data     : $data
	  		  }).done( function( data ) { 
	  		  	if( data ) {
	  				
	  		  		 element.removeAttr('data').removeAttr('data-token');
	  		  		//<--- PHOTOS Y FAVORITES ----->
	  		  		if( data.media != '' ) {
	  		  			element.parents('li').find( '.grid-media' ).before( data.media );
	  		  		}
	  		  		if( data.replys != '' ) {
	  		  			var total_data_reply = data.replys.length;
						
						for( var i = 0; i < total_data_reply; i++ ) {
								element.parents('li').find( '.grid-media' ).append( data.replys[i] );
								$('.p-text').readmore({
								maxHeight: 120,
								moreLink: '<a href="#">'+ReadMore+'</a>',
								lessLink: '<a href="#">'+ReadLess+'</a>',
								sectionCSS: 'display: block; width: 100%;',
								
							});
							}
							
							jQuery("span.timeAgo").timeago();
	  		  		}
	  		  		if( data.favs != '' ) {
	  		  			var total_data = data.favs.length;
						
						for(var i = 0; i < total_data; i++ ) {
								element.parents('li').find( '.favs-list' ).append( data.favs[i] );
								
								
							}
	  		  			
	  		  		}
	  		  		
	  		  		$('textarea').autosize();
	  		  		$('.showTooltip').tooltip();
					var date = new Date($('.datetime').data('timestamp'));
					$('.datetime').text(date.toLocaleString());
					console.log('toString');
	  		  		
	  		  	}
	  		  	});//<--- Done
	  		  	
	  		  //<---- * end ajax * ---->
	  	 });//<---- * end click * ---->
	  	 
	  	  //<---------- * Background Position * ---------->
	  	 $('#bg-pos').click(function(e){
	  	 	
	  	 	e.preventDefault();
	  	 	
	  	 	var element = $(this);
	  	 	element.attr({'disabled' : 'true'}).css({'opacity':'0.5','cursor':'default'});
	  	 	
	  	 	var data    = element.attr('data-bgpos');
	  	 	var query   = '_bgPosition='+data;
	  	 	
	  	 	$.ajax({
	  	 		type : 'GET',
	  	 		url  : urlbase+'public/ajax/edit_bg_position.php',
	  	 		dataType: 'json',
	  	 		data : query,
	  	 		
	  	 	}).done(function( response ){
	  	 		
	  	 		if( response.action == 1 ) {
	  	 			$('.popout').html(response.result).fadeIn(200).delay(2500).fadeOut(200);
	  	 			element.attr({'disabled' : false}).css({'opacity':1,'cursor':'pointer'});
	  	 		}
	  	 		else {
	  	 			$('.popout').html(response.result).fadeIn(200).delay(2500).fadeOut(200);
	  	 			element.attr({'disabled' : false}).css({'opacity':1,'cursor':'pointer'});
	  	 		}
	  	 	});//<--- Done
	  	 });//<---- * End click * ---->
	  	 
	  	 //<---------- * Avatar Edit * ---------->
	  	 $('#btn_pos_avatar').click(function(e){
	  	 	
	  	 	e.preventDefault();
	  	 	
	  	 	var element = $(this);
	  	 	element.attr({'disabled' : 'true'}).css({'opacity':'0.5','cursor':'default'});
	  	 	
	  	 	var data    = element.attr('data-pos');
	  	 	var query   = '_avatarPosition='+data;
	  	 	
	  	 	$.ajax({
	  	 		type : 'POST',
	  	 		url  : urlbase+'public/ajax/positionAvatar.php',
	  	 		dataType: 'json',
	  	 		data : query,
	  	 		
	  	 	}).done(function( response ){
	  	 		
	  	 		if( response.error == 0 ) {
	  	 			$('#modalAvatar').modal('hide');
	  	 			$('.popout').html(response.output).fadeIn(200).delay(2500).fadeOut(200);
	  	 			$('.profile-avatar').attr('src', urlbase+'public/avatar/'+response.photo);
	  	 			$('.userAvatar').html('<img src="'+urlbase+'public/avatar/'+response.photo+'" alt="Avatar" class="img-rounded" width="24" height="24">')
	  	 			element.attr({'disabled' : false}).css({'opacity':1,'cursor':'pointer'});
	  	 		}
	  	 		else {
	  	 			$('.popout').html(response.output).fadeIn(200).delay(2500).fadeOut(200);
	  	 			element.attr({'disabled' : false}).css({'opacity':1,'cursor':'pointer'});
	  	 		}
	  	 	});//<--- Done
	  	 });//<---- * End click * ---->
	  	 
	  	 //<---------- * Remove Reply * ---------->
	  	 $(document).on('click','.removeReply',function(){
	  	 	
	  	 	var element = $(this);
	  	 	var data    = element.attr('data');
	  	 	var query   = '_replyId='+data;
	  	 	
	  	 	$.ajax({
	  	 		type : 'GET',
	  	 		url  : urlbase+'public/ajax/delete_reply.php',
	  	 		data : query,
	  	 		
	  	 	}).done(function( result ){
	  	 		
	  	 		if( result == 1 )
	  	 		{
	  	 			element.parents('.reply-list').fadeTo( 200,0.00, function(){
   		             element.parents('.reply-list').slideUp( 200, function(){
   		  	           element.parents('.reply-list').remove();
   		              });
   		           });
	  	 		}
	  	 		else
	  	 		{
	  	 			element.removeClass('removeReply');
	  	 			return false;
	  	 		}
	  	 		
	  	 	});//<--- Done
	  	 	
	  	 });//<---- * end click * ---->
	  	 
	  	 
	  	  //<---------- * Remove Reply * ---------->
	  	 $(document).on('click','.removeMsg',function(){
	  	 	
	  	 	var element = $(this);
	  	 	var data    = element.attr('data');
	  	 	var query   = '_msgId='+data;
	  	 	
	  	 	element.parents('li').fadeTo( 200,0.00, function(){
   		             element.parents('li').slideUp( 200, function(){
   		  	           element.parents('li').remove();
   		              });
   		           });
   		           
	  	 	$.ajax({
	  	 		type : 'POST',
	  	 		url  : urlbase+'public/ajax/delete_msg.php',
	  	 		dataType: 'json',
	  	 		data : query,
	  	 		
	  	 	}).done(function( data ){
	  	 		
	  	 		if( data.status === 0 ) {
	  	 			bootbox.alert(data.error);
	  	 			return false;
	  	 		}
	  	 		
	  	 	});//<--- Done
	  	 });//<---- * End click * ---->
	  	 
	  	 //<---------- * Remove All Messages * ---------->
	  	 $(document).on('click', '.removeAllMsg', function(){
	  	 	
	  	 	var element = $(this);
	  	 	var data    = element.attr('data');
	  	 	var query   = '_userId='+data;
	  	 	
	  	 	element.parents('li').fadeTo( 200,0.00, function(){
   		             element.parents('li').slideUp( 200, function(){
   		  	           element.parents('li').remove();
   		              });
   		           });
   		           
	  	 	$.ajax({
	  	 		type : 'POST',
	  	 		url  : urlbase+'public/ajax/delete_all_msg.php',
	  	 		dataType: 'json',
	  	 		data : query,
	  	 		
	  	 	}).done(function( data ){
	  	 		
	  	 		if( data.status === 0 ) {
	  	 			bootbox.alert(data.error);
	  	 			return false;
	  	 		}
	  	 		
	  	 	});//<--- Done
	  	 });//<---- * End click * ---->
	  	 
	//<<<--- * Report Post ---->>>>/
	$(document).on('click',".reportPost",function(){
	var element   = $(this);
	var id        = element.attr("data");
	var token     = element.attr('data-token');
	var info      = '_postId=' + id+'&_token='+token;
	
	element.removeClass( 'reportPost' );

		
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/report_post.php",
		   dataType: 'json',
		   data: info,
		   success: function( data ){
		   	
		   if( data.status == 'ok' ) { 
		   	 $('.popout').html(data.res).fadeIn().delay(2500).fadeOut();
		   } 
		   //<-- IF
			   else {
			   	bootbox.alert(data.res);
			   	 $('.popout').fadeOut();
			   }
		 }//<-- RESULT 
	   });//<--- AJAX

	 
	
	      
});//<----- CLICK

//<<<--- * Report User ---->>>>/
	$(document).on('click',".report_user_spam",function(){
	var element   = $(this);
	var id        = element.attr("data-id");
	var info      = '_userId=' + id;
	
	element.removeClass( 'report_user_spam' );
	
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/report_user.php",
		   dataType : 'json',
		   data: info,
		   success: function( data ){
		   	
		   if( data.status == 'ok' ) { 
		   	 $('.popout').html(data.res).fadeIn().delay(2500).fadeOut();
		   } 
		   //<-- IF
			   else {
			   	bootbox.alert(data.res);
			   	 $('.popout').fadeOut();
			   }
		 }//<-- RESULT 
	   });//<--- AJAX

});//<----- CLICK

$(document).on('click', '#sign-btn-focus', function() {
	$('#user').focus();
});
//<<<--- * Block User ---->>>>/
	$(document).on('click',".block_user_id",function(){
	var element   = $(this);
	var id        = element.attr("data-id");
	var info      = '_userId=' + id;
	var timeQuery = 500;
	
	element.removeClass( 'block_user_id' );
	
	setTimeout(function(){
		
		 $.ajax({
		   type: "POST",
		   url: "public/ajax/block_user.php",
		   dataType: 'json',
		   data: info,
		   success: function( data ){
		   
		   if( data.status == 'ok' ) { 
		   	 $('.popout').html(data.res).fadeIn().delay(2500).fadeOut();
		   	 setTimeout(function(){ 
		   	 	window.location.reload();
		   	 },2500 );
		   } 
		   
		   else {
			   	 bootbox.alert(data.res);
			   	 $('.popout').fadeOut();
			   }

		 }//<-- RESULT 
	   });//<--- AJAX

	 
	},timeQuery );
	      
});//<----- CLICK

function toggleFollowButton(element){
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	element.removeClass( 'followBtn' );
	//$('.popout').html('Wait...').fadeIn();
	
	if( element.hasClass( 'follow_active' ) ) {
		element.addClass( 'followBtn btn-default' );
		   	element.removeClass( 'follow_active unfollow_button btn-danger btn-info' );
		   element.html( '<i class="icon-user3 myicon-right"></i> ' + _follow );
		   element.blur();
		   	  
		}
		else {
			
			element.addClass( 'followBtn' );
		   	  element.removeClass( 'follow_active unfollow_button btn-danger btn-default' );
		   	    element.addClass( 'followBtn' );
		   	   element.addClass( 'follow_active btn-info' );
		   	  element.html( '<i class="icon-user3 myicon-right"></i> ' + _following );
		   	  element.blur();
		}

}

/*= FOLLOW =*/
	$(document).on('click',".followBtn",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var username   = element.attr("data-username");
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	var info       = 'id=' + id;
	var timeQuery  = 1000;
	var needfollowpass = element.attr("data-needfollowpass");
	var follow_pass = '';
	


	
	if( !element.hasClass( 'follow_active' ) && needfollowpass=='yes'){
		var followpassmsg = element.attr("data-followpassmsg");
		bootbox.prompt(followpassmsg, function(result){ 
			follow_pass = result;
			setTimeout(function(){

				 $.ajax({
				   type: "POST",
				   url: "public/ajax/follow.php",
				   dataType: 'json',
				   data: {
					id:id,
					follow_pass:follow_pass
				   },
				   success: function( result ){

					if( result.status == 0 ) { 
		//				element.addClass( 'followBtn' );
		//				  element.removeClass( 'follow_active unfollow_button btn-danger followBtn' );
		//				   element.html( '<i class="icon-user3 myicon-right"></i> ' + _follow );
			//		   	  element.html( type );
						  $('.popout').html( result.error  ).fadeIn();
		//				  element.blur();
					}else{
						toggleFollowButton(element);
					}
				 }//<-- RESULT 
			   });//<--- AJAX


			},timeQuery );
		});
	}else{
	
		setTimeout(function(){

			 $.ajax({
			   type: "POST",
			   url: "public/ajax/follow.php",
			   dataType: 'json',
			   data: {
				id:id
			   },
			   success: function( result ){

				if( result.status == 0 ) { 
					element.addClass( 'followBtn' );
					  element.removeClass( 'follow_active unfollow_button btn-danger followBtn' );
					   element.html( '<i class="icon-user3 myicon-right"></i> ' + _follow );
		//		   	  element.html( type );
					  $('.popout').html( result.error  ).fadeIn();
					  element.blur();
				}else{
					toggleFollowButton(element);
				}
			 }//<-- RESULT 
		   });//<--- AJAX


		},timeQuery );
	}
	      
});//<----- CLICK


		//<--------- * See MSG * ------>
		$(document).on('click','.see_msg',function(e){
			
			e.preventDefault();
			
			var _this     = $(this);
			var id        = _this.attr("data");
			var info      = '_userId=' + id;
			var titleInit = $('.titleBar').attr('data-title');
			var _reply    = _this.attr('data-reply');
			var username  = _this.attr('data-username');
			var MAX_LENGTH = $('body').attr('data-max');
			var _avatar   = $('.photo-card-live > img').attr('src');
			
			$('.titleBar').html('<a href="'+urlbase+'messages/">'+titleInit+'</a> &rsaquo; ' + username);
			
			 $('.content').html('');
			 
			 /* Loader */
			var loaderGif = '<div id="container-loader"> <div class="loading-bar"></div> </div>';
			$('.content').append(loaderGif);
			 
			 $.ajax({
			   type: "POST",
			   url: "public/ajax/get_message_id.php",
			   data: info,
			   success: function( result ){
			   if( result.length > 1 ) {
			   	
			   	 $('#container-loader').remove(); 
			   	 $('.content').append(result);
			   	 $('<div class="panel-footer"> <div class="media"> <span href="#" class="pull-left"> <img src="'+_avatar+'" class="media-object img-circle" width="40" /> </span> <div class="media-body"> <form action="" method="post" accept-charset="UTF-8" id="form_reply_post"> <input type="hidden" name="id_reply" id="id_reply" value="'+id+'"> <textarea class="form-control textarea-text" id="reply_msg" name="reply_msg"></textarea> <p class="help-block"> <button type="submit" id="button-reply-msg" disabled="disabled" style="opacity: 0.5; cursor: default;" class="btn btn-info btn-xs btn-border">'+_reply+'</button> </p> </form> </div><!-- media-body --> </div><!-- media --> </div><!--./ panel-footer -->').insertAfter('.content');
			   	 
			   	 $("#reply_msg").charCount({ allowed: MAX_LENGTH, warning: 10});
			   	 
			   	$('.p-text').readmore({
					maxHeight: 120,
					moreLink: '<a href="#">'+ReadMore+'</a>',
					lessLink: '<a href="#">'+ReadLess+'</a>',
					sectionCSS: 'display: block; width: 100%;',
					
				});
			   	 
			   	 $(".showTooltip").tooltip();
			   	 
			   	 jQuery("span.timeAgo").timeago();
			   	 
			   	 scrollElement( 'li.media:last' );
			   	 
			   	 var _numElement =  $('li.media').length;
			   	 
			   	 if( _numElement > 5 ) {
			   	 	
			   	 	$( '#reply_msg' ).focus();
			   	 } else {
			   	 	$( '.grid_2' ).focus();

			   	 }
			   	    $('#reply_msg').focus();
			   	    $('textarea').autosize(); 
			   	    
			   } else {
				   	 	window.location.reload();
				    	 
				   	 $('.popout').fadeOut();
				   }
			 }//<-- RESULT 
		   });//<--- AJAX
		});//<<<--- Click
	  
	  
	  $(document).on('click','#button-reply-msg',function(s){
				s.preventDefault();
				
				var element   = $(this);
				var error     = false;
				var _message  = $('#reply_msg').val();
				var saveHtml  = element.html();
			 	var _wait     = '...';
			 	var _saveHtml = saveHtml + _wait;
					
				if( _message == '' && trim( _message ).length  == 0 ) {
					var error = true;
					return false;
				}
				

				if( error == false ) {
					$('#button-reply-msg').attr({'disabled' : 'true'}).html(_saveHtml).css({'opacity':'0.5','cursor':'default'});
					
					$.post("public/ajax/send_message_id.php", $("#form_reply_post").serialize(), function(msg){
						
						if( msg.length != 0 ) {
							$(msg).hide().appendTo('.content').fadeIn( 800 );
							 $('#reply_msg').val('');
							 $('#button-reply-msg').html(saveHtml);
							 jQuery("span.timeAgo").timeago();
							 $(".showTooltip").tooltip();
							 
				$('.p-text').readmore({
					maxHeight: 120,
					moreLink: '<a href="#">'+ReadMore+'</a>',
					lessLink: '<a href="#">'+ReadLess+'</a>',
					sectionCSS: 'display: block; width: 100%;',
					
				});
							 
						} 
						
					});//<-- END DE $POST AJAX
				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>


	$('#buttonSearch').click(function(e){			
		var search    = $('#btnItems').val();
		if( trim( search ).length < 1  || trim( search ).length == 0 || trim( search ).length > 100 ) {
			return false;
		} else {
			return true;
			
		}
	});//<--- * FIN FUNCIÓN DE BÚSQUEDA * --->
	
			
	//<------------- * AUTOCOMPLETE * ---------->
$(window).bind("load", function() {
	
$('#btnItems').keyup(function(e){
	
	e.preventDefault();
    e.stopPropagation();
    
    
    var valueClean  = $(this).val().replace(/\#+/gi,'%23');
    var _valueClean = $(this).val().replace(/<(?=\/?script)/ig, "&lt;");
    
    $('.searchGlobal').html( '<a href="search/?q='+trim( valueClean )+'"><i class="searchIco"></i> '+trim( _valueClean )+'</a>' );
			
	//$(this).waiting( 500 ).done(function() {
		 	if( e.which != 16 
		 		&& e.which != 17 
				&& e.which != 18 
				&& e.which != 20 
				&& e.which != 32 
		 		&& e.which != 37 
		 	    && e.which != 38 
		 	    && e.which != 39 
		 	    && e.which != 40 
		 	    ) {
     		 	$('.toogle_search > li.list').remove();
     		 	
			}
    		
			var $element     = $(this);
			var inputOutput  = $element.val();
			var value        = inputOutput.replace(/\s+/gi,' ');
     		
     				
     		// || e == ''
			if( trim( value ).length == 0 || trim( value ).length >= 50  ) {
				$('.boxSearch').slideUp( 1 );
			} else if( e.which == 16 
				|| e.which == 17 
				|| e.which == 18 
				|| e.which == 20 
				|| e.which == 32 
				|| e.which == 37 
				|| e.which == 38 
				|| e.which == 39 
				|| e.which == 40 
				) {
				return false;
			} else {

					
				
			$(this).waiting( 500 ).done(function() {
				
				$.get("public/ajax/autocomplete.php", { look : trim( value ) }, function( sql ) {
				
					if ( sql != '' ) {
						$('.toogle_search > li.list').remove();
						$( sql ).hide().appendTo('.toogle_search').slideDown( 1 );
						
  							
					}
					//<-- * TOTAL LI * -->
					var total   = $('.toogle_search > li').length;
					
					$('.boxSearch').slideDown( 1 );
				
				});
				});//<----- * WAITING * ---->
				
			}
			
		 //});//<----- * WAITING * ---->
				
			$(document).bind('click', function(ev) {
			var $clicked = $(ev.target);
			if ( !$clicked.parents().hasClass("boxSearch") && !$clicked.hasClass("mention") )
			{
				$(".boxSearch").slideUp( 5 );
			}
	 		});//<-------- * FIN CLICK * --------->
	    
   });//<--------- * END KEYUP * ------>
});//<----------- * DOM LOAD  * --------->

/*
$(document).on('click', '.openModal', function(e){
 	
 	e.preventDefault();
    e.stopPropagation();
    
    $('.content_user').html('');
    
    $('.content_user').append('<div class="preload_profile"></div>');
    
    //<--- VARS
    var element   = $(this);
    var param     = /^[0-9]+$/i;
    var _document = $('body');
    var userId    = element.attr('data-id');
	
	if( !param.test( userId ) ) {
		return false;
	}
	setTimeout(function() {
		$.get("public/ajax/profile_summary.php", { id_user : userId }, function( response ) {
		
		if ( response ) {
			
				$('.preload_profile').remove();
			if( response.status == 1 ) {
				$('.content_user').hide().html( response.html ).slideDown( 500 );
				//<**** - Tooltip
    			$('.showTooltip').tooltip();
				
				
			} else {
				$('.content_user').append('<div class="error_show">'+response.html+'</div>');
				element.removeAttr('data-id')
				}		
		}//<-- DATA 
	},'json'); 
	
	}, 500 );
	
 	$('#container_popout').show();// Show Container Popout
 	$('.popoutUser').fadeIn();

	    _document.addClass('scroll_none_popout');
	    
	    _document.keydown(function (event) {
	     if( event.which  == 27  )
	     {
	     _document.removeClass('scroll_none_popout').removeAttr('class');
	     $('#container_popout').hide();
	 	 $('.popoutUser').fadeOut();
	    }
	});//======== FUNCTION 
	
	
     		
});//<----------- *  End Click * ------------->
*/
	
	
 //**** close click
 $(document).on('click','#container_popout, .close_popout',function(e){
 	$('body').removeClass('scroll_none_popout').removeAttr('class');
		 $('#container_popout').hide();
		 $('.popoutUser').fadeOut();
 });
 
 
 /* Reposition Modal */
 function _reposition(){
				
	var verticalOffset = -75;
	
	var left = (($(window).width() / 2) - ($(".popoutUser").outerWidth() / 2));
	if( left < 0 ) { left = 0; }

	
	$(".popoutUser").css({
		left: left + 'px'
	});
}
	//<--- * REPOSITION POPOUT * ---->
   _reposition();
   $(window).bind('resize', _reposition );
   
      
   $('#reloadUsers').on('click',function(s){
				s.preventDefault();
				
				var _this = $(this);
				
					$('.preloader-user').fadeIn();
					
					$.post("public/ajax/reload_users.php", function(result){
						
						if( result.length != 0 ) {
							$('#whoBox').html( result ).fadeIn( 800 );
							 $('.preloader-user').delay(100).fadeOut();
							 $('.showTooltip').tooltip();
						} 
					});//<-- END $POST AJAX
					
			});//<<<-------- * END FUNCTION CLICK * ---->>>>

//#############################################################################
//#                               Version 1.4			                      #
//#############################################################################

//<<<--- * Load more messages ID ---->>>>/
	$(document).on('click',".loadMessagesOld",function(){
		
	var element     = $(this);
	var _id         = $('li.list-slimscroll:first').attr('data');
	var _idUser     = $('li.list-slimscroll:first').attr('data-user');
	var _number     = $('li.list-slimscroll').length;
	var totalGlobal = element.attr('data-total');
	var info        = '_id=' + _id + '&_userId=' + _idUser + '&_number=' + _number;
	
	element.css({background:'url("'+ urlbase +'public/img/preload.gif") center center no-repeat',  'text-indent':'-9999px', 'cursor': 'default' });
	element.removeClass('loadMessagesOld');
						
		$.ajax({
		   type: "POST",
		   url: "public/ajax/load_more_message_id.php",
		   data: info,
		   success: function( data ){
		   
		   if( data.length > 1 ) { 
		   	$(data).hide().insertAfter('#loadMsgPanel').fadeIn(500);
		   	
		   	$('.p-text').readmore({
					maxHeight: 120,
					moreLink: '<a href="#">'+ReadMore+'</a>',
					lessLink: '<a href="#">'+ReadLess+'</a>',
					sectionCSS: 'display: block; width: 100%;',
					
				});
				
				 $(".showTooltip").tooltip();
			   	 jQuery("span.timeAgo").timeago();
		   	 
		   	 var total   = $('li.list-slimscroll').length;
				
		   	 if( totalGlobal == total ) {
		   	 	$('#loadMsgPanel').remove();
		   	 } else {
		   	 	element.css({background:'none',  'text-indent':'0', 'cursor': 'pointer' });
	
		   	 	element.addClass('loadMessagesOld');
		   	 }

		   }  else {
			   	 bootbox.alert('Error');
			   	 $('#loadMsgPanel').remove();
			   }
		 }//<-- RESULT 
	   });//<--- AJAX
});//<----- CLICK

$(document).on('click',"#geolocationBtn",function(){  

 var geocoder;

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
} 
//Get the latitude and the longitude;
function successFunction(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    codeLatLng(lat, lng)
}

function errorFunction(){
    bootbox.alert("Geocoder failed");
}

 geocoder = new google.maps.Geocoder();

  function codeLatLng(lat, lng) {

    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'location': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      console.log(results)
        if (results[1]) {
         //formatted address
         //<*********** ----> alert(results[0].formatted_address) <---- **********--->
        //find country name
             for (var i=0; i<results[0].address_components.length; i++) {
            for (var b=0;b<results[0].address_components[i].types.length;b++) {

            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                    //this is the object you are looking for
                    city= results[0].address_components[i];
                    break;
                }
            }
        }
        //city data
        //alert(city.short_name + " " + city.long_name)
        $('#geolocation').val(results[0].formatted_address);

        } else {
          bootbox.alert("No results found");
        }
      } else {
        bootbox.alert("Geocoder failed due to: " + status);
      }
    });
  }
	$("#geolocation").slideToggle( 500, function() {
    // Animation complete.
  });
  $( this ).toggleClass( "btn-share-active-2" ).val('');
  
});

			
});//<------------ * DOM * ------------>

function view_compact(){
//console.log('view_compact()');
	var before_uid=0;
	var before_li;
//	var container = $('.container > .row > .col-md-8 > .panel > .posts > .content > li ');
	var container = $('.posts > .content > li ');
//console.log(container.html());
	container.each(function(){
		var this_uid = $(this).find('.openModal').data('id');
//		console.log('this_uid:' + this_uid);
		if ( this_uid == before_uid){
//			$(this).children('.timeline-title').addClass('hide');
			$(this).children('.timeline-title').css('height','30px');
			$(this).children('.timeline-title').children('.text-user-timeline').addClass('hide');
			$(this).children('.panel-heading').find('.img-rounded').css('width','24px');

			before_li.addClass('li-group-hide').removeClass('li-group');
			before_li.children('.panel-body').css('padding-bottom','0px');
		}
		before_uid = this_uid;
		before_li = $(this);
	});
	
}

function input_emoji(e,smiley){
//		var smiley = $(this).attr('title');
		$('#add_post').val($('#add_post').focus().val()+" "+smiley+" ");
		$('#button_add').removeAttr('disabled').css({'opacity':'1','cursor':'pointer'});
		e.stopPropagation();

}

function link_atall(){
	$('.posts > .content > li').each(function(){
		var url = $(this).find('.openModal').attr('href');
		var str = $(this).find('.p-text').html();
		if(str){
			$(this).find('.p-text').html(str.replace('./all', url + '/followers'));
		}
	});
		
	
}

function refrech_contents(){
	//ajax等で動的にコンテンツが書き換わったときに毎回実行される

	//連投をまとめる
	view_compact();

	//画像表示ツールの適応
	$(".link-img" + ".smartphoto").SmartPhoto({
		arrows:false,
		nav:false
	});
	$(".link-img" + ".smartphoto").removeClass('smartphoto');
	
	link_atall();

	$('.jump_from_post').click(function(e){
		location.href=$(this).data('href');
		e.stopPropagation();
	});

}



function clear_ss(){
	var ss = sessionStorage;
	ss.removeItem('first_load_posts');
	ss.removeItem('first_load_posts_time');
}