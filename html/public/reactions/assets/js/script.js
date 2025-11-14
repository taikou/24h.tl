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
	var isTouchDevice = false;
	var boxOpenTime = 0; // ボックスを開いた時刻

	// タッチデバイスを検出
	$(document).one('touchstart', function() {
		isTouchDevice = true;
	});

	// PC用: マウスホバーでリアクションボックスを表示
	$(document).on('mouseenter', 'div.dw-reactions-button', function(e){
		if(!isTouchDevice && $(this).parent().data('type')=='vote'){
			$(this).addClass('reaction-show');
			openReactionBox = $(this);
		}
	});

	$(document).on('mouseleave', 'div.dw-reactions-button', function(e){
		if(!isTouchDevice){
			$(this).removeClass('reaction-show');
			if(openReactionBox && openReactionBox[0] === $(this)[0]){
				openReactionBox = null;
			}
		}
	});

	// モバイル用: タップでリアクションボックスをトグル
	$(document).on('touchend', '.dw-reactions-main-button:not(.dw_reaction_like, .dw_reaction_love, .dw_reaction_haha, .dw_reaction_wow, .dw_reaction_sad, .dw_reaction_angry)', function(e){
		var parent = $(this).parent();
		if(parent.parent().data('type')=='vote'){
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			
			// すでに開いているリアクションボックスがあれば閉じる
			if(openReactionBox && openReactionBox[0] !== parent[0]){
				openReactionBox.removeClass('reaction-show');
				openReactionBox.find('.dw-reaction').removeClass('mobile-instant');
			}
			
			// このリアクションボックスをトグル
			if(!parent.hasClass('reaction-show')){
				boxOpenTime = Date.now(); // ボックスを開いた時刻を記録
				parent.addClass('reaction-show mobile-active');
				// モバイルでは即座に表示するクラスを追加
				parent.find('.dw-reaction').addClass('mobile-instant');
				openReactionBox = parent;
			} else {
				parent.removeClass('reaction-show mobile-active');
				parent.find('.dw-reaction').removeClass('mobile-instant');
				openReactionBox = null;
			}
			
			return false;
		}
	});

	// リアクションボックス外をタップしたら閉じる（モバイル用）
	$(document).on('touchend', function(e){
		if(openReactionBox && !$(e.target).closest('.dw-reactions-button').length){
			openReactionBox.removeClass('reaction-show mobile-active');
			openReactionBox.find('.dw-reaction').removeClass('mobile-instant');
			openReactionBox = null;
		}
	});

	// リアクションボックス外をクリックしたら閉じる（PC用）
	$(document).on('click', function(e){
		if(!isTouchDevice && openReactionBox && !$(e.target).closest('.dw-reactions-button').length){
			openReactionBox.removeClass('reaction-show');
			openReactionBox = null;
		}
	});

	$(document).on('taphold','div.dw-reactions-button',function(e){
		if($(this).parent().data('type')=='vote'){
			e.preventDefault();
			$(this).addClass('reaction-show');
			$(this).disableSelection();
		}
	});

	$('div.dw-reactions-button').disableSelection();

	// リアクションアイコンをタップして選択（モバイル用）
	$(document).on('touchend', '.dw-reaction', function(e){
		// ボックスを開いてから300ms以内は選択を無視
		if(Date.now() - boxOpenTime < 300) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
		
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
		$('div.dw-reactions-button').removeClass('reaction-show mobile-active');
		$('.dw-reaction').removeClass('mobile-instant');
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

	// リアクションアイコンをクリックして選択（PC用）
	$(document).on('click', '.dw-reaction', function(e){
		if(isTouchDevice) return; // タッチデバイスでは無視
		
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
