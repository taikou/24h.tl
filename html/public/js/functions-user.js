$(document).ready(function() {
$('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) 
	        && $(this).has(e.target).length === 0 
	        && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

	//<**** - Tooltip
    $('.showTooltip').tooltip();

    
    // Popover
    
    /* -- Credit ----
     * 
     * http://stackoverflow.com/questions/10238089/how-can-you-ensure-twitter-bootstrap-popover-windows-are-visible
     * 
     */
    
    var getPlacementFunction = function (defaultPosition, width, height) {
    return function (tip, element) {
        var position, top, bottom, left, right;

        var $element = $(element);
        var boundTop = $(document).scrollTop();
        var boundLeft = $(document).scrollLeft();
        var boundRight = boundLeft + $(window).width();
        var boundBottom = boundTop + $(window).height();

        var pos = $.extend({}, $element.offset(), {
            width: element.offsetWidth,
            height: element.offsetHeight
        });

        var isWithinBounds = function (elPos) {
            return boundTop < elPos.top && boundLeft < elPos.left && boundRight > (elPos.left + width) && boundBottom > (elPos.top + height);
        };

        var testTop = function () {
            if (top === false) return false;
            top = isWithinBounds({
                top: pos.top - height,
                left: pos.left + pos.width / 2 - width / 2
            });
            return top ? "top" : false;
        };

        var testBottom = function () {
            if (bottom === false) return false;
            bottom = isWithinBounds({
                top: pos.top + pos.height,
                left: pos.left + pos.width / 2 - width / 2
            });
            return bottom ? "bottom" : false;
        };

        var testLeft = function () {
            if (left === false) return false;
            left = isWithinBounds({
                top: pos.top + pos.height / 2 - height / 2,
                left: pos.left - width
            });
            return left ? "left" : false;
        };

        var testRight = function () {
            if (right === false) return false;
            right = isWithinBounds({
                top: pos.top + pos.height / 2 - height / 2,
                left: pos.left + pos.width
            });
            return right ? "right" : false;
        };

        switch (defaultPosition) {
            case "top":
                if (position = testTop()) return position;
            case "bottom":
                if (position = testBottom()) return position;
            case "left":
                if (position = testLeft()) return position;
            case "right":
                if (position = testRight()) return position;
            default:
                if (position = testTop()) return position;
                if (position = testBottom()) return position;
                if (position = testLeft()) return position;
                if (position = testRight()) return position;
                return defaultPosition;
        }
    }
};
	// Var THIS
	var _this = $(this);
	
	// PopOver
    $('.popover-item').popover({
    	
    	trigger: 'manual',
    	placement: getPlacementFunction(_this.attr("data-placement"),280, 190),
    	animation: false,
    	html: true,
    	title: function() {
          return $('.wrap-popover').html();
       },
    	content: function() {
          return $('.popover-card').html();
        }
        
    }).on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 50);
    });
    
    // PopOver User
    $('.popover-user').popover({
    	
    	trigger: 'manual',
    	placement: getPlacementFunction(_this.attr("data-placement"),280, 190),
    	animation: false,
    	html: true,
    	title: function() {
          return $('.wrap-popover-user').html();
       },
    	content: function() {
          return $('.popover-card-user').html();
        }
        
    }).on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 50);
    });
    
 /*
 * ---------------------------------------
 * Popover
 * http://stackoverflow.com/questions/16150163/bootstrap-show-one-popover-and-hide-other-popovers
 * ---------------------------------------
 * 
 */
     var $currentPopover = null;
     $(document).on('shown.bs.popover', function (ev) {
     	var $target = $(ev.target);
    if ($currentPopover && ($currentPopover.get(0) != $target.get(0))) {
      $currentPopover.popover('toggle');
    }
    $currentPopover = $target;
  });

  $(document).on('hidden.bs.popover', function (ev) {
    var $target = $(ev.target);
    if ($currentPopover && ($currentPopover.get(0) == $target.get(0))) {
      $currentPopover = null;
    }
  });

  // Miscellaneous Functions  
   
   
   // Like Function
   $('body').on('click', 'button.like-button', function () {
    var _this    = $(this);
     
     if( _this.hasClass( 'btn-default' ) ) {
     	
		_this.addClass( 'btn-success' );
			_this.removeClass( 'btn-default' );
				_this.parent().find('.ico-like').removeClass( 'icon-thumbs-up' ).addClass('glyphicon glyphicon-ok');
        	_this.blur();
	} else if ( _this.hasClass('btn-success') ) {
		_this.removeClass( 'btn-success' );
			_this.addClass( 'btn-default' );
			_this.parent().find('.ico-like').addClass( 'icon-thumbs-up' ).removeClass('glyphicon glyphicon-ok');
		_this.blur();
	}
});

	// Add Function
   $('body').on('click', 'button.add-button', function () {
	    var _this    = $(this);
	     
	     if( _this.hasClass( 'btn-default' ) ) {
	     	
			_this.addClass( 'btn-info' );
				_this.removeClass( 'btn-default' );
					_this.parent().find('.ico-add').removeClass( 'glyphicon glyphicon-plus' ).addClass('glyphicon glyphicon-ok');
	        	_this.blur();
		} else if ( _this.hasClass('btn-success') ) {
			_this.removeClass( 'btn-success' );
				_this.addClass( 'btn-default' );
				_this.parent().find('.ico-add').removeClass('glyphicon glyphicon-ok').addClass('glyphicon glyphicon-plus');
			_this.blur();
		} else if ( _this.hasClass('btn-info') ) {
			_this.removeClass( 'btn-info' );
				_this.addClass( 'btn-default' );
				_this.parent().find('.ico-add').removeClass('glyphicon glyphicon-ok').addClass('glyphicon glyphicon-plus');
			_this.blur();
		}
	}); //<<--- End Add Function

}); //*************** End DOM ***************************//
