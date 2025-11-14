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
	var boxOpenTime = 0; // ボックスを開いた時刻
	var isTouchDevice = false; // タッチデバイスかどうか
	
	// タッチデバイスを検出（最初のtouchstartで判定）
	$(document).one('touchstart', function() {
		isTouchDevice = true;
		console.log('[Reaction Debug] Touch device detected');
	});

	// PC用: マウスホバーでリアクションボックスを表示
	$(document).on('mouseenter', 'div.dw-reactions-button', function(e){
		// タッチデバイスではホバーを無視
		if(isTouchDevice) return;
		
		if($(this).parent().data('type')=='vote'){
			console.log('[Reaction Debug] PC: mouseenter - showing box');
			$(this).addClass('reaction-show');
			openReactionBox = $(this);
		}
	});

	// PC用: マウスを離すと閉じる
	$(document).on('mouseleave', 'div.dw-reactions-button', function(e){
		// タッチデバイスではホバーを無視
		if(isTouchDevice) return;
		
		console.log('[Reaction Debug] PC: mouseleave - hiding box');
		$(this).removeClass('reaction-show');
		if(openReactionBox && openReactionBox[0] === $(this)[0]){
			openReactionBox = null;
		}
	});

	// モバイル用: タップでリアクションボックスをトグル
	$(document).on('touchend', '.dw-reactions-main-button:not(.dw_reaction_like, .dw_reaction_love, .dw_reaction_haha, .dw_reaction_wow, .dw_reaction_sad, .dw_reaction_angry)', function(e){
		var parent = $(this).parent();
		if(parent.parent().data('type')=='vote'){
			e.preventDefault();
			e.stopPropagation();
			
			console.log('[Reaction Debug] Mobile: touchend on button');
			
			// すでに開いているリアクションボックスがあれば閉じる
			if(openReactionBox && openReactionBox[0] !== parent[0]){
				openReactionBox.removeClass('reaction-show');
			}
			
			// このリアクションボックスをトグル
			if(!parent.hasClass('reaction-show')){
				console.log('[Reaction Debug] Mobile: opening box');
				boxOpenTime = Date.now(); // ボックスを開いた時刻を記録
				parent.addClass('reaction-show');
				openReactionBox = parent;
			} else {
				console.log('[Reaction Debug] Mobile: closing box');
				parent.removeClass('reaction-show');
				openReactionBox = null;
				boxOpenTime = 0;
			}
			
			return false;
		}
	});

	// リアクションボックス外をタップしたら閉じる（モバイル用）
	$(document).on('touchend', function(e){
		if(openReactionBox && !$(e.target).closest('.dw-reactions-button').length){
			console.log('[Reaction Debug] Mobile: touchend outside - closing box');
			openReactionBox.removeClass('reaction-show');
			openReactionBox = null;
			boxOpenTime = 0;
		}
	});
	
	// リアクションボックス外をクリックしたら閉じる（PC用）
	$(document).on('click', function(e){
		if(!isTouchDevice && openReactionBox && !$(e.target).closest('.dw-reactions-button').length){
			console.log('[Reaction Debug] PC: click outside - closing box');
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

	// モバイル用: リアクションアイコンをタップして選択
	$(document).on('touchend', '.dw-reaction', function(e){
		// ボックスを開いてから500ms以内は選択を無視（誤タップ防止）
		if(boxOpenTime > 0 && (Date.now() - boxOpenTime < 500)) {
			console.log('[Reaction Debug] Mobile: touchend too soon, ignoring');
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
		
		console.log('[Reaction Debug] Mobile: touchend on reaction icon');
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
		
		console.log('[Reaction Debug] Mobile: sending reaction type ' + like_type);
		
		// リアクションボックスを閉じる
		$('div.dw-reactions-button').removeClass('reaction-show');
		openReactionBox = null;
		boxOpenTime = 0;

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
				console.log('[Reaction Debug] Mobile: reaction sent successfully');
				$('.dw-reactions-post-'+main.data('post')).find('.dw-reactions-main-button').attr('class','dw-reactions-main-button').addClass('dw_reaction_'+type[2]).text(text);
				main.attr('data-vote','yes').data('type','unvote');
			}
		});
		
		return false;
	});

	// PC用: リアクションアイコンをクリックして選択
	$(document).on('click', '.dw-reaction', function(e){
		// タッチデバイスではclickイベントを無視（touchendで処理済み）
		if(isTouchDevice) return;
		
		console.log('[Reaction Debug] PC: click on reaction icon');
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
		
		console.log('[Reaction Debug] PC: sending reaction type ' + like_type);
		
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
				console.log('[Reaction Debug] PC: reaction sent successfully');
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
