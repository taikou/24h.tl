(function($){
	$.fn.extend({
		disableSelection: function() {
			this.each(function() {
				this.onselectstart = function() {
				    return false;
				};
				this.unselectable = "on";
				$(this).css('-moz-user-select', 'none');
				$(this).css('-webkit-user-select', 'none');
				$(this).css('-ms-user-select', 'none');
				$(this).css('user-select', 'none');
			});
			return this;
		}
	});

	// 開いているリアクションボックスを追跡
	var openReactionBox = null;
	var touchStartTime = 0;
	var isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

	// PC用: マウスホバーでリアクションボックスを表示
	if (!isTouchDevice) {
		$(document).on('mouseenter', 'div.dw-reactions-button', function(e){
			if($(this).parent().data('type')=='vote'){
				$(this).addClass('reaction-show');
			}
		});

		$(document).on('mouseleave', 'div.dw-reactions-button', function(e){
			$(this).removeClass('reaction-show');
		});
	}

	// モバイル用: タップでリアクションボックスを表示/非表示
	$(document).on('touchstart', '.dw-reactions-main-button:not(.dw_reaction_like, .dw_reaction_love, .dw_reaction_haha, .dw_reaction_wow, .dw_reaction_sad, .dw_reaction_angry)', function(e){
		var parent = $(this).parent();
		if(parent.parent().data('type')=='vote'){
			e.preventDefault();
			e.stopPropagation();
			touchStartTime = Date.now();
			
			// すでに開いているリアクションボックスがあれば閉じる
			if(openReactionBox && openReactionBox[0] !== parent[0]){
				openReactionBox.removeClass('reaction-show');
			}
			
			// このリアクションボックスをトグル
			if(!parent.hasClass('reaction-show')){
				parent.addClass('reaction-show');
				openReactionBox = parent;
			} else {
				parent.removeClass('reaction-show');
				openReactionBox = null;
			}
			
			return false;
		}
	});

	// PC用: クリックでリアクションボックスを表示
	if (!isTouchDevice) {
		$(document).on('click', '.dw-reactions-main-button:not(.dw_reaction_like, .dw_reaction_love, .dw_reaction_haha, .dw_reaction_wow, .dw_reaction_sad, .dw_reaction_angry)', function(e){
			var parent = $(this).parent();
			if(parent.parent().data('type')=='vote' && !parent.hasClass('reaction-show')){
				e.preventDefault();
				e.stopPropagation();
				// 他のリアクションボックスを閉じる
				$('div.dw-reactions-button').removeClass('reaction-show');
				// このリアクションボックスを表示
				parent.addClass('reaction-show');
				return false;
			}
		});
	}

	// リアクションボックス外をタップしたら閉じる（モバイル用）
	$(document).on('touchend', function(e){
		// タップ直後（500ms以内）は無視
		if(Date.now() - touchStartTime < 500) return;
		
		if(openReactionBox && !$(e.target).closest('.dw-reactions-button').length){
			openReactionBox.removeClass('reaction-show');
			openReactionBox = null;
		}
	});

	// リアクションボックス外をクリックしたら閉じる（PC用）
	if (!isTouchDevice) {
		$(document).on('click', function(e){
			if(!$(e.target).closest('.dw-reactions-button').length){
				$('div.dw-reactions-button').removeClass('reaction-show');
			}
		});
	}

	$(document).on('taphold','div.dw-reactions-button',function(e){
		if($(this).parent().data('type')=='vote'){
			e.preventDefault();
			$(this).addClass('reaction-show');
			$(this).disableSelection();
		}
	});

	$('div.dw-reactions-button').disableSelection();

	// リアクションアイコンをタップ/クリック
	$(document).on('touchstart click', '.dw-reaction', function(e){
		e.preventDefault();
		e.stopPropagation();

		var t = $(this), $class = t.attr('class'), main = t.parent().parent().parent(), vote_type = main.attr('data-type'), voted = main.attr('data-vote'), text = t.find('strong').text();
		
		res = $class.split(' ');
		type = res[1].split('-');
		switch(type[2]){
			case 'like': like_type=1; break;
			case 'love': like_type=2; break;
			case 'haha': like_type=3; break;
			case 'wow': like_type=4; break;
			case 'sad': like_type=5; break;
			case 'angry': like_type=6; break;
		}
		
		// リアクションボックスを閉じる
		$('div.dw-reactions-button').removeClass('reaction-show');
		openReactionBox = null;

		$.ajax({
			url: '/public/ajax/favorites.php',
			dataType: 'json',
			type: 'POST',
			data: {
				id: main.data('post'),
				token: main.data('token'),
				type: like_type,
			},
			success: function(data) {
	//				$('.dw-reactions-post-'+main.data('post')).find('.dw-reactions-count').html(data.data.html);
					$('.dw-reactions-post-'+main.data('post')).find('.dw-reactions-main-button').attr('class','dw-reactions-main-button').addClass('dw_reaction_'+type[2]).text(text);
					main.attr('data-vote','yes').data('type','unvote');
			}
		});
		
		return false;
	});

	// すでにリアクション済みの場合のクリック処理
	$(document).on('click','.dw-reactions-main-button.dw_reaction_like, .dw-reactions-main-button.dw_reaction_love, .dw-reactions-main-button.dw_reaction_haha, .dw-reactions-main-button.dw_reaction_wow, .dw-reactions-main-button.dw_reaction_sad, .dw-reactions-main-button.dw_reaction_angry', function(e) {
		e.preventDefault();
		e.stopPropagation();

		var t = $(this), parent = t.parent().parent();
		type = parent.attr('data-type');
		text = t.parent().find('.dw-reaction-like strong').text();

		// リアクションボックスを閉じる
		$('div.dw-reactions-button').removeClass('reaction-show');

		$.ajax({
			url: '/public/ajax/favorites.php',
			dataType: 'json',
			type: 'POST',
			data: {
				id: parent.data('post'),
				token: parent.data('token'),
				type: 1,
			},
			success: function(data) {

				if ( data == '2' ) {
					$('.dw-reactions-post-'+parent.data('post')).find('.dw-reactions-main-button').attr('class', 'dw-reactions-main-button').text(text);
//					parent.attr('data-type', 'vote');
					parent.data('type','vote');
				} else {
					$('.dw-reactions-post-'+parent.data('post')).find('.dw-reactions-main-button').addClass('dw_reaction_like');
//					parent.attr('data-type', 'unvote');
					parent.data('type','unvote');
				}
	//			$('.dw-reactions-post-'+parent.data('post')).find('.dw-reactions-count').html(data.data.html);
			}
		});
	})
})(jQuery);