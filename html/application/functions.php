<?php 
/*
 * -------------------------------------
 * Miguel Vasquez
 * functions.php
 * -------------------------------------
 */
class _Function
{
	// spaces
	public static function spaces($string) {
	  return ( preg_replace('/(\s+)/u',' ',$string ) );
	
	}
	
	public static function checkEmail( $str ) {
		return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-\.]+)+[A-z]{2,4}$/", $str);
	}

 	public static function send_mail( $from, $to, $subject, $body ) {
		$headers = '';
		$headers .= "MIME-Version: 1.0\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "From: $from\n";
		$headers .= "Reply-To: $from\n" . 
		'X-Mailer: PHP/' . phpversion();
	
		mail( $to, $subject , $body, $headers );
	}
	
	/* Emoticons */
	public static function emoticons( $text )
    {
		

		$change = array(
		
	':smile2618:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2618.gif title="2618" width="32" />',
	':smile2620:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2620.gif title="2620" width="32" />',
	':smile2676:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2676.gif title="2676" width="32" />',
	':smile2678:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2678.gif title="2678" width="32" />',
	':smile2681:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2681.gif title="2681" width="32" />',
	':smile2728:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2728.gif title="2728" width="32" />',
	':smile2729:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2729.gif title="2729" width="32" />',
	':smile2730:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2730.gif title="2730" width="32" />',
	':smile2733:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2733.gif title="2733" width="32" />',
	':smile2734:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2734.gif title="2734" width="32" />',
	':smile2770:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2770.gif title="2770" width="32" />',
	':smile2771:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2771.gif title="2771" width="32" />',
	':smile2772:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2772.gif title="2772" width="32" />',
	':smile2773:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2773.gif title="2773" width="32" />',
	':smile2775:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2775.gif title="2775" width="32" />',
	':smile2776:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2776.gif title="2776" width="32" />',
	':smile2786:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2786.gif title="2786" width="32" />',
	':smile2787:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2787.gif title="2787" width="32" />',
	':smile2788:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2788.gif title="2788" width="32" />',
	':smile2806:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2806.gif title="2806" width="32" />',
	':smile2808:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2808.gif title="2808" width="32" />',
	':smile2809:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2809.gif title="2809" width="32" />',
	':smile2812:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2812.gif title="2812" width="32" />',
	':smile2814:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2814.gif title="2814" width="32" />',
	':smile2835:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2835.gif title="2835" width="32" />',
	':smile2836:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2836.gif title="2836" width="32" />',
	':smile2838:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2838.gif title="2838" width="32" />',
	':smile2857:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2857.gif title="2857" width="32" />',
	':smile2858:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2858.gif title="2858" width="32" />',
	':smile2859:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2859.gif title="2859" width="32" />',
	':smile2860:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2860.gif title="2860" width="32" />',
	':smile2861:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2861.gif title="2861" width="32" />',
	':smile2869:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2869.gif title="2869" width="32" />',
	':smile2870:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2870.gif title="2870" width="32" />',
	':smile2871:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2871.gif title="2871" width="32" />',
	':smile2872:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2872.gif title="2872" width="32" />',
	':smile2873:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2873.gif title="2873" width="32" />',
	':smile2875:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2875.gif title="2875" width="32" />',
	':smile2876:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2876.gif title="2876" width="32" />',
	':smile2879:' => '<img src='.URL_BASE.'public/img/icons/emoji_64/2879.gif title="2879" width="32" />',
/*		
		':evilgrin:' => '<img src="'.URL_BASE.'public/img/icons/emotion_evilgrin.png" title=":evilgrin:" />',
		':grin:' => '<img src="'.URL_BASE.'public/img/icons/emotion_grin.png" title=":grin:" />',
		':D' => '<img src="'.URL_BASE.'public/img/icons/emotion_happy.png" title=":D" />',
		':d' => '<img src="'.URL_BASE.'public/img/icons/emotion_happy.png" title=":D" />',
		':)' => '<img src="'.URL_BASE.'public/img/icons/emotion_smile.png" title=":)" />',
		':O' => '<img src="'.URL_BASE.'public/img/icons/emotion_suprised.png" title=":O" />',
		':o' => '<img src="'.URL_BASE.'public/img/icons/emotion_suprised.png" title=":O" />',
		':P' => '<img src="'.URL_BASE.'public/img/icons/emotion_tongue.png" title=":P" />',
		':p' => '<img src="'.URL_BASE.'public/img/icons/emotion_tongue.png" title=":P" />',
		':(' => '<img src="'.URL_BASE.'public/img/icons/emotion_unhappy.png" title=":(" />',
		':waii:' => '<img src="'.URL_BASE.'public/img/icons/emotion_waii.png" title=":waii:" />',
		';)' => '<img src="'.URL_BASE.'public/img/icons/emotion_wink.png" title=";)" />',
		':smile:' => '<img src="'.URL_BASE.'public/img/icons/yahoo_messenger.png" title=":smile:" />',
		':bigsmile:' => '<img src="'.URL_BASE.'public/img/icons/bigsmile.png" title=":bigsmile:" />',
		':smiletears:' => '<img src="'.URL_BASE.'public/img/icons/smiletears.png" title=":smiletears:" />',
		':sad:' => '<img src="'.URL_BASE.'public/img/icons/sad.png" title=":sad:" />',
		':bashful:' => '<img src="'.URL_BASE.'public/img/icons/bashful.png" title=":bashful:" />',
		':inlove:' => '<img src="'.URL_BASE.'public/img/icons/inlove.png" title=":inlove:" />',
		':stress:' => '<img src="'.URL_BASE.'public/img/icons/stress.png" title=":stress:" />',
		':kiss:' => '<img src="'.URL_BASE.'public/img/icons/kiss.png" title=":kiss:" />',
		':angry:' => '<img src="'.URL_BASE.'public/img/icons/angry.png" title=":angry:" />',
		":'(" => '<img src="'.URL_BASE.'public/img/icons/sad2.png" title=":\'(" />',
		':crying:' => '<img src="'.URL_BASE.'public/img/icons/crying.png" title=":crying:" />',
		'3:)' => '<img src="'.URL_BASE.'public/img/icons/evil.png" title="3:)" />',
		'o.O' => '<img src="'.URL_BASE.'public/img/icons/rare.png" title="o.O" />',
		'O:)' => '<img src="'.URL_BASE.'public/img/icons/angel2.png" title="O:)" />',
		'o:)' => '<img src="'.URL_BASE.'public/img/icons/angel2.png" title="O:)" />',
		':*' => '<img src="'.URL_BASE.'public/img/icons/kiss2.png" title=":*" />',
		'-_-' => '<img src="'.URL_BASE.'public/img/icons/serious.png" title="-_-" />',
		':cool:' => '<img src="'.URL_BASE.'public/img/icons/cool.png" title=":cool:" />',
		':film:' => '<img src="'.URL_BASE.'public/img/icons/film.png" title=":film:" />',
		':glasses:' => '<img src="'.URL_BASE.'public/img/icons/3d_glasses.png" title=":glasses:" />',
		':award:' => '<img src="'.URL_BASE.'public/img/icons/award_star_bronze_3.png" title=":award:" />',
		':beer:' => '<img src="'.URL_BASE.'public/img/icons/beer.png" title=":beer:" />',
		':android:' => '<img src="'.URL_BASE.'public/img/icons/android.png" title=":android:" />',
		':angel:' => '<img src="'.URL_BASE.'public/img/icons/angel.png" title=":angel:" />',
		':battery:' => '<img src="'.URL_BASE.'public/img/icons/battery_charge.png" title=":battery:" />',
		':bookspelling:' => '<img src="'.URL_BASE.'public/img/icons/book_spelling.png" title=":bookspelling:" />',
		':bulb:' => '<img src="'.URL_BASE.'public/img/icons/bulb.png" title=":bulb:" />',
		':cake:' => '<img src="'.URL_BASE.'public/img/icons/cake.png" title=":cake:" />',
		':candle:' => '<img src="'.URL_BASE.'public/img/icons/candle.png" title=":candle:" />',
		':car:' => '<img src="'.URL_BASE.'public/img/icons/car.png" title=":car:" />',
		':voltage:' => '<img src="'.URL_BASE.'public/img/icons/caution_high_voltage.png" title=":voltage:" />',
		':cat:' => '<img src="'.URL_BASE.'public/img/icons/cat.png" title=":cat:" />',
		':cash:' => '<img src="'.URL_BASE.'public/img/icons/cash_stack.png" title=":cash:" />',
		':chiness:' => '<img src="'.URL_BASE.'public/img/icons/chinese_noodles.png" title=":chiness:" />',
		':christmastree:' => '<img src="'.URL_BASE.'public/img/icons/christmas_tree.png" title=":christmastree:" />',
		':church:' => '<img src="'.URL_BASE.'public/img/icons/church.png" title=":church:" />',
		':clock:' => '<img src="'.URL_BASE.'public/img/icons/clock.png" title=":clock:" />',
		':controller:' => '<img src="'.URL_BASE.'public/img/icons/controller.png" title=":controller:" />',
		':cup:' => '<img src="'.URL_BASE.'public/img/icons/cup.png" title=":cup:" />',
		':dog:' => '<img src="'.URL_BASE.'public/img/icons/dog.png" title=":dog:" />',
		':fire:' => '<img src="'.URL_BASE.'public/img/icons/fire.png" title=":fire:" />',
		':headphone:' => '<img src="'.URL_BASE.'public/img/icons/headphone.png" title=":headphone:" />',
		'&lt;3' => '<img src="'.URL_BASE.'public/img/icons/heart.png" title="<3" />',
		':heartbroke:' => '<img src="'.URL_BASE.'public/img/icons/heartbroke.png" title=":heartbroke:" />',
		':injection:' => '<img src="'.URL_BASE.'public/img/icons/injection.png" title=":injection:" />',
		':iphone:' => '<img src="'.URL_BASE.'public/img/icons/iphone.png" title=":iphone:" />',
		':ipod:' => '<img src="'.URL_BASE.'public/img/icons/ipod.png" title=":ipod:" />',
		':soup:' => '<img src="'.URL_BASE.'public/img/icons/soup.png" title=":soup:" />',
		':ladybird:' => '<img src="'.URL_BASE.'public/img/icons/ladybird.png" title=":ladybird:" />',
		':lightbulb:' => '<img src="'.URL_BASE.'public/img/icons/lightbulb.png" title=":lightbulb:" />',
		':life_vest:' => '<img src="'.URL_BASE.'public/img/icons/life_vest.png" title=":life_vest:" />',
		':moneybox:' => '<img src="'.URL_BASE.'public/img/icons/moneybox.png" title=":moneybox:" />',
		':moneydollar:' => '<img src="'.URL_BASE.'public/img/icons/money_dollar.png" title=":moneydollar:" />',
		':moneyeuro:' => '<img src="'.URL_BASE.'public/img/icons/money_euro.png" title=":moneyeuro:" />',
		':moneypound:' => '<img src="'.URL_BASE.'public/img/icons/money_pound.png" title=":moneypound:" />',
		':moneyyen:' => '<img src="'.URL_BASE.'public/img/icons/money_yen.png" title=":moneyyen:" />',
		':poker:' => '<img src="'.URL_BASE.'public/img/icons/poker.png" title=":poker:" />',
		':poo:' => '<img src="'.URL_BASE.'public/img/icons/poo.png" title=":poo:" />',
		':popcorn:' => '<img src="'.URL_BASE.'public/img/icons/popcorn.png" title=":popcorn:" />',
		':rain:' => '<img src="'.URL_BASE.'public/img/icons/rain.png" title=":rain:" />',
		':ribbon:' => '<img src="'.URL_BASE.'public/img/icons/ribbon.png" title=":ribbon:" />',
		':ring:' => '<img src="'.URL_BASE.'public/img/icons/ring.png" title=":ring:" />',
		':santa:' => '<img src="'.URL_BASE.'public/img/icons/santa.png" title=":santa:" />',
		':scull:' => '<img src="'.URL_BASE.'public/img/icons/scull.png" title=":scull:" />',
		':shopping:' => '<img src="'.URL_BASE.'public/img/icons/shopping.png" title=":shopping:" />',
		':8ball:' => '<img src="'.URL_BASE.'public/img/icons/sport_8ball.png" title=":8ball:" />',
		':basketball:' => '<img src="'.URL_BASE.'public/img/icons/sport_basketball.png" title=":basketball:" />',
		':football:' => '<img src="'.URL_BASE.'public/img/icons/sport_football.png" title=":football:" />',
		':soccer:' => '<img src="'.URL_BASE.'public/img/icons/sport_soccer.png" title=":soccer:" />',
		':baseball:' => '<img src="'.URL_BASE.'public/img/icons/baseball.png" title=":baseball:" />',
		':tennis:' => '<img src="'.URL_BASE.'public/img/icons/sport_tennis.png" title=":tennis:" />',
		':star:' => '<img src="'.URL_BASE.'public/img/icons/star.png" title=":star:" />',
		':wheel:' => '<img src="'.URL_BASE.'public/img/icons/steering_wheel_2.png" title=":wheel:" />',
		':television' => '<img src="'.URL_BASE.'public/img/icons/television.png" title=":television:" />',
		':theater:' => '<img src="'.URL_BASE.'public/img/icons/theater.png" title=":theater:" />',
		':beauty:' => '<img src="'.URL_BASE.'public/img/icons/things_beauty.png" title=":beauty:" />',
		':thumbup:' => '<img src="'.URL_BASE.'public/img/icons/thumb_up.png" title=":thumbup:" />',
		':thumbdown:' => '<img src="'.URL_BASE.'public/img/icons/thumb_down.png" title=":thumbdown:" />',
		':travel:' => '<img src="'.URL_BASE.'public/img/icons/travel.png" title=":travel:" />',
		':batman:' => '<img src="'.URL_BASE.'public/img/icons/user_batman.png" title=":batman:" />',
		':weathercloudy:' => '<img src="'.URL_BASE.'public/img/icons/weather_cloudy.png" title=":weathercloudy:" />',
		':sun:' => '<img src="'.URL_BASE.'public/img/icons/weather_sun.png" title=":sun:" />',
		':student:' => '<img src="'.URL_BASE.'public/img/icons/user_student.png" title=":student:" />',
		':king:' => '<img src="'.URL_BASE.'public/img/icons/user_king.png" title=":king:" />',
		':studentfemale:' => '<img src="'.URL_BASE.'public/img/icons/user_student_female.png" title=":studentfemale:" />',
		':ninja:' => '<img src="'.URL_BASE.'public/img/icons/user_ninja.png" title=":ninja:" />',
		':policeman:' => '<img src="'.URL_BASE.'public/img/icons/user_policeman.png" title=":policeman:" />',
		':gun:' => '<img src="'.URL_BASE.'public/img/icons/gun.png" title=":gun:" />',
		':law:' => '<img src="'.URL_BASE.'public/img/icons/law.png" title=":law:" />',
		':bagmoney:' => '<img src="'.URL_BASE.'public/img/icons/bagmoney.png" title=":bagmoney:" />',
		':alien:' => '<img src="'.URL_BASE.'public/img/icons/alien.png" title=":alien:" />',
		':mouse:' => '<img src="'.URL_BASE.'public/img/icons/mouse.png" title=":mouse:" />',
		':pizza:' => '<img src="'.URL_BASE.'public/img/icons/pizza.png" title=":pizza:" />',
		':burger:' => '<img src="'.URL_BASE.'public/img/icons/burger.png" title=":burger:" />',
		':fries:' => '<img src="'.URL_BASE.'public/img/icons/fries.png" title=":fries:" />',
		':chicken:' => '<img src="'.URL_BASE.'public/img/icons/chicken.png" title=":chicken:" />',
		':soccer2:' => '<img src="'.URL_BASE.'public/img/icons/soccer2.png" title=":soccer2:" />',
		':donuts:' => '<img src="'.URL_BASE.'public/img/icons/donuts.png" title=":donuts:" />',
		':arm:' => '<img src="'.URL_BASE.'public/img/icons/arm.png" title=":arm:" />',
		':fist:' => '<img src="'.URL_BASE.'public/img/icons/fist.png" title=":fist:" />',
		'(Y)' => '<img src="'.URL_BASE.'public/img/icons/like.png" title="(Y)" />',
		'(y)' => '<img src="'.URL_BASE.'public/img/icons/like.png" title="(Y)" />',
		'(N)' => '<img src="'.URL_BASE.'public/img/icons/nolike.png" title="(N)" />',
		'(n)' => '<img src="'.URL_BASE.'public/img/icons/nolike.png" title="(N)" />',
		':good:' => '<img src="'.URL_BASE.'public/img/icons/good.png" title=":good:" />',
		':loves:' => '<img src="'.URL_BASE.'public/img/icons/loves.png" title=":loves:" />',
		':beer2:' => '<img src="'.URL_BASE.'public/img/icons/beer2.png" title=":beer2:" />',
		':coctail:' => '<img src="'.URL_BASE.'public/img/icons/coctail.png" title=":coctail:" />',
		':sick:' => '<img src="'.URL_BASE.'public/img/icons/sick.png" title=":sick:" />',
		':sleep:' => '<img src="'.URL_BASE.'public/img/icons/sleep.png" title=":sleep:" />',
		':music:' => '<img src="'.URL_BASE.'public/img/icons/music.png" title=":music:" />',
		':marisol:' => '<img src="'.URL_BASE.'public/img/icons/marisol.png" title=":marisol:" />',
		':rose:' => '<img src="'.URL_BASE.'public/img/icons/rose.png" title=":rose:" />',
		':flowers:' => '<img src="'.URL_BASE.'public/img/icons/flowers.png" title=":flowers:" />',
		':chick:' => '<img src="'.URL_BASE.'public/img/icons/chick.png" title=":chick:" />',
*/		
				
		
		);
		
		$output = strtr( $text , $change );
		return $output;
	}

	//============== linkText
	 public static function linkText( $text ) {
	 //   	error_log($text);
			$ret=$text;
	 	if (preg_match_all('/https?:\/\/[\w\-\.!~#?&=+%;:\*\'"(),\/]+/u',$text,$match)){
	//	error_log(print_r($match,1));
			foreach($match as $m){
	//		error_log($m[0]);
				$ret=str_replace($m[0],'<a href="'.$m[0].'" target="_blank">'.(strlen($m[0])>40?substr($m[0],0,40).'..':$m[0]).'</a>',$ret);
			}
		}
		return $ret;
		
	    return preg_replace('/https?:\/\/[\w\-\.!~#?&=+%;:\*\'"(),\/]+/u','<a href="$0" target="_blank">'.(strlen('$0')>30?substr('$0',0,30).'..':'$0').'</a>',$text);
	}
	 
	public static function bitLyUrl( $url ) {
	return $url;	
		$path   = "https://api-ssl.bitly.com/v4/";
		$user   = BIT_URL_USER;
		$key    = BIT_URL_KEY;
        $temp   = $path."shorten?login=".$user."&apiKey=".$key."&long_url=".$url."&format=json";
		$data   = file_get_contents( $temp );
		die($temp);
		$output = json_decode( $data );
		print_r($output);
		return $output->data->url;
	}
	
	public static function checkText( $str ) {
		
		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		
		$str = nl2br( htmlspecialchars( $str ) );
		$str = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $str );
		

		
		// Hashtags and @Mentions
//    	$str = preg_replace_callback('~([^\{])([#@])([^\s#@!\"\$\%&\'\(\)\*\+\,\-./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~',
//    	create_function('$m', '$dir = $m[2] == "#" ? "search/?q=%23" : "./";' .
//    	'return "$m[1]<a href=\"$dir$m[3]\">$m[2]$m[3]</a>";' ),
//        ' '.$str );

		$str=' '.$str;
		
		// #Hashtags
		$str = preg_replace('~([^\{]]?)#([^\s#@!\"\$\%&\'\(\)\*\+\,\-./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~u','$1<a class="jump_from_post" data-href="search/?q=%23$2">#$2</a>',$str);

		// @Mentions
		$str = preg_replace('~@([a-zA-Z0-9_-]{3,24})~','<a class="jump_from_post" data-href="./$1">@$1</a>',$str);
		
		/* Link text */
		$str = self :: linkText( $str );

		//デコメ絵文字
		$str=preg_replace('/\{#([0-9a-zA-Z]{4,6})\}/','<img class="" onclick="input_emoji(arguments[0],'."'".'$0'."'".');" style="image-rendering:crisp-edges;" src="https://i.decoo.jp/demj/$1" title="$0" />',$str);		
		$str=preg_replace('/\{%([0-9a-zA-Z]{4,6})\}/','<img class="" onclick="input_emoji(arguments[0],'."'".'$0'."'".');" style="image-rendering:crisp-edges;" src="https://i.decoo.jp/mdec/$1" title="$0" />',$str);		
		
		/* Emoticons */
		$str = self :: emoticons( $str );
		$str = stripslashes( $str );
		
		return $str;
	}
	
	// Text With (1) line break
	public static function checkTextDb2( $str ) {
		
		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		$str = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\r\n", $str);
		$str = trim($str,"\r\n");
				
		return $str;
	}
	
	// Text With (2) line break
	public static function checkTextDb( $str ) {
		
		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		$str = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\r\n\r\n", $str);
		$str = trim($str,"\r\n");
				
		return $str;
	}
		
	public static function checkTextMessages( $str ) {
		
		$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		$str = nl2br( htmlspecialchars ( $str ) );
		$str = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $str );
		$str = stripslashes( $str );
		/* Emoticons */
		$str = self :: emoticons( $str );
		
		return wordwrap( $str, 60, "\n", TRUE );
	}
	
	public static function checkTextNoLine( $str ) {
		
		$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		$str = nl2br( htmlspecialchars ( $str ) );
		$str = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $str );
		$str = stripslashes( $str );
		// Hashtags and @Mentions
    	$str = preg_replace_callback('~([#@])([^\s#@]+)~',
    	create_function('$m', '$dir = $m[1] == "#" ? "search/?q=%23" : "./";' .
    	'return "<a href=\"$dir$m[2]\">$m[0]</a>";' ),
        $str );
		/* Emoticons */
		$str = self :: emoticons( $str );
		
		return wordwrap( $str, 60, "\n", FALSE );
	}
	
	public static function resizeImage( $image, $width, $height, $scale, $imageNew = null ) {
		
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image);
			//回転
			$exif_datas = exif_read_data($image);
			error_log('scale:'.$scale.' width:'.$width.' height:'.$height);
			error_log('exif:'.print_r($exif_datas,1));
			if(isset($exif_datas["Orientation"])){
				 if($exif_datas["Orientation"]==6){

				 // 回転
				 $source = imagerotate($source, 270, 0);
				$newImageWidth = ceil($height * $scale);
				$newImageHeight = ceil($width * $scale);
				 $tmp=$height;
				 $height=$width;
				 $width=$tmp;
 			 	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
			error_log('newwidth:'.$newImageWidth.' newheight:'.$newImageHeight.' width:'.$width.' height:'.$height);

				 }
				 if($exif_datas["Orientation"]==8){

				 // 回転
				 $source = imagerotate($source, 90, 0);
				$newImageWidth = ceil($height * $scale);
				$newImageHeight = ceil($width * $scale);
				$tmp=$height;
				 $height=$width;
				 $width=$tmp;
				 $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);

				 }
				 if($exif_datas["Orientation"]==3){

				 // 回転
				 $source = imagerotate($source, 180, 0);

				 }
			}
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif( $newImage, $imageNew ); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg( $newImage, $imageNew ,90 ); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng( $newImage, $imageNew );  
			break;
    }
	
	chmod($image, 0777);
	return $image;
	}

public static function resizeImageFixed( $image, $width, $height, $imageNew = null ) {
		
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImage = imagecreatetruecolor($width,$height);
	
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
  	}
	if( $width/$imagewidth > $height/$imageheight ){
        $nw = $width;
        $nh = ($imageheight * $nw) / $imagewidth;
        $px = 0;
        $py = ($height - $nh) / 2;
    } else {
        $nh = $height;
        $nw = ($imagewidth * $nh) / $imageheight;
        $py = 0;
        $px = ($width - $nw) / 2;
    }
	
	imagecopyresampled($newImage,$source,$px, $py, 0, 0, $nw, $nh, $imagewidth, $imageheight);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$imageNew); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$imageNew,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$imageNew);  
			break;
    }
	
		chmod($image, 0777);
		return $image;
	}
	
	public static function getHeight( $image ) {
		$size   = getimagesize( $image );
		$height = $size[1];
		return $height;
	}
	
	public static function getWidth( $image ) {
		$size  = getimagesize( $image);
		$width = $size[0];
		return $width;
	}
	
	//<--- stringRandom
	public static function stringRandom( $long = 16, $chars = '0123456789abcdefghijklmnopqrstuvwxyz!~^#!{}@+*' ) {
    $string = '';
    $max = mb_strlen( $chars ) - 1 ;

    for( $i = 0; $i < $long; ++$i ){
    	
        $string .= mb_substr( $chars, mt_rand( 0, $max ), 1 );
    }
    return $string;
	
	}
	
	//=============== ID HASH
	public static function idHash( $id ) {
		return sha1('~#bae!´+*%=?¿B63~23!~^'.( $id ).microtime().self::stringRandom() );
	}
	
	//=================== cropString
	public static function cropString( $content, $chars ) {
		
	 	return	mb_substr( $content,0, $chars, 'UTF-8' )."..."; 	
	}
	
	
	
	//=================== cropString
	public static function cropStringLimit( $content, $chars ) {
		return	mb_substr( $content,0, $chars, 'UTF-8' ); 
	}
	
	function focusText( $find, $text, $repl = '<strong style="color: #FF7000;">%s</strong>', $ord = 32 ) {
	$find = self::spaces( trim ( $find ) );
    $char = is_numeric( $ord ) ? chr( $ord ) : $ord[0]; // caracter?
    $fn = create_function('$test', 'static $_num = 0, $_tags;
            if (is_numeric($test[1])) return $_tags[$test[1]];
                        $_tags[$_num] = $test[1];
                $tag = "__!{$_num}__";
    ++$_num; return $tag;');

    $text = preg_replace_callback('/((?:<|&lt;|\[).+(?:>|&gt;|\]))/', $fn, $text);

    $found = array();
    $word = explode( $char, $find );

    foreach ( $word as $test )
    { 
        $found []= preg_quote(strip_tags( $test ) );
    }

    $expr = join('|', $found); 
    $text = preg_replace("/($expr)/is", strtr($repl, array('%s' => '\\1')), $text);

    $text = preg_replace_callback('/__!(\d+)__/', $fn, $text);
    return $text;
} 
	
	public static function randomString( $length = 10, $uc = TRUE, $n = TRUE, $sc = FALSE ) {
	    $source = 'abcdefghijklmnopqrstuvwxyz';
	    if( $uc == 1 ) { $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
	    if( $n  == 1 ) { $source .= '1234567890'; }
	    if( $sc == 1 ) { $source .= '-_'; }//'|@#~$%()=^*+[]{}-_'; }
	    
	    if( $length > 0 ) {
	        $rstr = "";
	        $source = str_split( $source, 1 );
	        for( $i = 1; $i <= $length; ++$i ) {
	            mt_srand();
	            $num   = mt_rand( 1, count( $source ) );
	            $rstr .= $source[ $num - 1 ];
	        }//<-- * FOR * -->
	    }//<-- * IF * -->
	    return $rstr;
	}
	
    public static function isValidYoutubeURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'youtube.com', 'www.youtube.com', 'youtu.be', 'www.youtu.be' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	    $oembedURL = 'https://www.youtube.com/oembed?url=' . urlencode($url).'&format=json';
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );

	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		}  else {
			return false;
		}
	}//<-------- FUNCTION END
	
	public static function isValidVimeoURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'vimeo.com', 'player.vimeo.com' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'vimeo.com/api/oembed.json?url=' . urlencode( $url );
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- FUNCTION END	
	
	public static function getYoutubeId( $url ) {
	 $pattern = 
	     '%^# Match any youtube URL
	    (?:https?://)? 
	    (?:www\.)?     
	    (?:             
	      youtu\.be/    
	    | youtube\.com  
	      (?:           
	        /embed/    
	      | /v/         
	      | /shorts/    
	      | .*v=        
	      )            
	    )              
	    ([\w-]{10,12})  
	    ($|&).*         
	    $%x'
	    ;
	        ;
	    $result = preg_match( $pattern, $url, $matches );
	    if ( false !== $result ) {
	        return $matches[1];
	    }
	    return false;
	}//<<<-- End
	
	public static function isValidSoundCloudURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'soundcloud.com' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'soundcloud.com/oembed?url='.urlencode( $url ).'/&format=json';
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- FUNCTION END	
	
	public static function formatNumber( $number ) {
    if( $number >= 1000 &&  $number < 1000000 ) {
       	
       return number_format( $number/1000, 1 ). "k";
    } else if( $number >= 1000000 ) {
		return number_format( $number/1000000, 1 ). "m"; 
	} else {
        return $number;
    }
   }//<<<<--- End Function
   
   public static function convertAscii( $entry ) {
		 $changes = array(
		'!' => '%21',
		'"' => '%22',
		'#' => '%23',
		'$' => '%24',
		'&' => '%26',
		"'" => '%27',
		'(' => '%28',
		')' => '%29',
		'*' => '%2A',
		'+' => '%2B',
		'-' => '%2D',
		'`' => '%60',
		'@' => '%40',
		'<' => '%3C',
		'=' => '3D',
		'>' => '3E',
		'?' => '3F',
		'^' => '5E'
		);
		
		$output = strtr( $entry , $changes );
		return $output;
	}//<<<<---- * End * ---->>>>

	
	//Languages Array
	public static function arrayLang(){
		$arrayLang = array(
		'Japanese - 日本語' => 'ja',
		'English (US)' => 'en',			
		'Catalan - català' => 'ca',
		'Chinese - 繁體中文' => 'zh-tw',
		'Deutsch - German' => 'de',
		'Dutch - Nederlands' => 'nl',
		'French - Français' => 'fr',
		'Italian - Italiano' => 'it',
		'Portuguese - Português' => 'pt',
		'Romanian - Română' => 'ro',
		'Russian - Русский' => 'ru',
		'Spanish - Español' => 'es',
		'Turkish - Türkçe' => 'tr',
		'Czech - Čeština' => 'cs',
		'Greek - Ελληνικά' => 'el'
		
		);
		return $arrayLang;
	}
	
   // getLang
    public static function getLang() {
    	
    	/* Prefix */
    	$prefix = 'lang_';
		/* Root */
		$root  = 'languages';
		//<--------- * LANGUAGE GET * ---------->
		if( isset( $_GET['lang'] ) ) {
        	
			//unset( $_SESSION['LANG'] );
            $_lang = $_GET['lang'];
			$lang =  $root . DS . $prefix.$_lang.'.php';
			
			if( is_readable( $lang ) ) {
				
				require_once $lang;
			} else {
				require_once $root . DS . $prefix.'en.php';;
			}
			
            $_SESSION['lang'] = $_lang;
			
			
        }elseif( $_SESSION['lang']  && !$_SESSION['LANG'] ) {

			//unset( $_SESSION['LANG'] );
            $_lang = $_SESSION['lang'];
			$lang =  $root . DS . $prefix.$_lang.'.php';
			
			if( is_readable( $lang ) ) {
				require_once $lang;
			} else {
				require_once $root . DS . $prefix.'en.php';;
			}
			
            $_SESSION['lang'] = $_lang;
			
			
        } else if ( isset( $_SESSION['lang'] ) && !isset( $_SESSION['lang_user'] ) ) {
        	$_lang = $_SESSION['lang'];
            $lang =  $root . DS . $prefix.$_lang.'.php';
			
            if( is_readable( $lang ) ) {
				
				require_once $lang;
			} else {
				require_once $root . DS . $prefix.'en.php';;
			}
			//<--------- * LANGUAGE USER DEFAULT * ---------->
        }  else if ( isset( $_SESSION['lang_user'] ) ) {
        	$_lang = $_SESSION['lang_user'];
            $lang  =  $root . DS . $prefix.$_lang.'.php';
			
            if( is_readable( $lang ) ) {
				
				require_once $lang;
			} else {
				require_once $root . DS . $prefix.'en.php';;
			}
			
            $_SESSION['lang'] = $_lang;
			
			
        } else {
            if ( $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '' ) {
                $languages = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
				
				$existsLang = 1;
				
				foreach ( self :: arrayLang() as $key => $value ) {
					
					/* Languages */
	                if( strpos( $languages[0], ''.$value.'' ) !== FALSE ) {
	                	$existsLang = 0;
	                	$_lang = $value;
						$lang =  $root . DS . $prefix.$_lang.'.php';
						require_once $lang;
						$_SESSION['lang'] = $_lang;
						break;
					} 
				}
				//<<<-- If not exists
				if( $existsLang == 1 ) {
					$_lang = 'en';
					$lang =  $root . DS . $prefix.$_lang.'.php';
					require_once $lang;
					$_SESSION['lang'] = $_lang;
				}
                
            } else  {
                $_lang = 'en';
				$lang =  $root . DS . $prefix.$_lang.'.php';
				require_once $lang;
				$_SESSION['lang'] = $_lang;
            }
        }
        return $lang;
    }//<--------- End getLang()
    
    // DailyMotion
    public static function isValidDailymotionURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'www.dailymotion.com', 'dailymotion.com' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'www.dailymotion.com/services/oembed?format=json&url=' . urlencode( $url );
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- Method END	
	
	// Screenr
    public static function isValidScreenrURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'www.screenr.com', 'screenr.com' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'www.screenr.com/api/oembed.json?url=' . urlencode( $url );
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- Method END	
	
	// Dotsub
    public static function isValidDotsubURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'www.dotsub.com', 'dotsub.com' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'dotsub.com/services/oembed?url=' . urlencode( $url ).'&format=json';
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- Method END	
	
	// Hulu
    public static function isValidHuluURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'www.hulu.com', 'hulu.com' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'www.hulu.com/api/oembed.json?url=' . urlencode( $url );
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- Method END	
	
	// SlideShare
    public static function isValidSlideShareURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'www.slideshare.net', 'slideshare.net', 'es.slideshare.net', 'fr.slideshare.net', 'pt.slideshare.net','de.slideshare.net'  ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'www.slideshare.net/api/oembed/2?url=' . urlencode( $url ).'&format=json';
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- Method END	
	
	// SlideShare
    public static function isValidBlipURL( $url ) {

	    $parse = parse_url($url);
	    $host  = strtolower( $parse['host'] );
	    if ( !in_array( $host, array( 'www.blip.tv', 'blip.tv' ) ) ) {
	        return false;
	    }
	
	    $ch = curl_init();
	    $oembedURL = 'blip.tv/oembed/?url=' . urlencode( $url );
	    curl_setopt( $ch, CURLOPT_URL, $oembedURL );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		
	    $output = curl_exec( $ch );
	    //unset( $output );
	
	    $info = curl_getinfo( $ch );
	    curl_close( $ch );
	
	    if ( $info['http_code'] !== 404 ) {
			return json_decode( $output );
		} else {
			return false;
		}
	}//<-------- Method END	
	
	public static function file_get_contents_curl($url) {
	    $ch = curl_init();
		//Mozilla/32.0.3 (X11; Linux x86_64) AppleWebKit/537.16 (KHTML, like Gecko) \ Chrome/38.0.2125.104 Safari/537.16
		$ua = 'Mozilla/32.0.3 (X11; Linux x86_64) AppleWebKit/537.16 (KHTML, like Gecko) Chrome/38.0.2125.104 Safari/537.16';
//		$ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1 Mobile/15E148 Safari/604.1';
		
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);

	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
	    curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
	    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	    $data = curl_exec($ch);
	    curl_close($ch);
		
	    return $data;
	}//<-------- Method END	
	
	public static function expand_link( $url ) {
		
	$headers = get_headers($url,1);
	
		if (!empty($headers['Location'])) {
			$headers['Location'] = (array) $headers['Location'];
			$url = array_pop($headers['Location']);
		}
		return $url;
	}//<-------- Method END	
    
	
	
	
	public static function send_push($user_id, $title, $body, $url='https://24h.tl/', $imgurl=''){
		$contents = array('en'=>$body);
		$headings = array('en'=>$title);
		if(is_array($user_id)){
			$before=false;
			foreach($user_id as $uid){
				if($before)$filters[]=array('operator'=>'OR');
				$filters[]=array('field'=>'tag', 'key'=>'user_id', 'relation'=>'=', 'value'=>$uid);
				$before=true;
			}
		}else{
			$filters=array(array('field'=>'tag', 'key'=>'user_id', 'relation'=>'=', 'value'=>$user_id));
		}
		$fields = array (
			'app_id' => '8af3f38a-d025-42e7-a06d-a0ac4d56ea2b',
			'filters' => $filters,
			'headings' => $headings,
			'contents' => $contents,
			'ios_attachments' => array('id1'=>$imgurl),
			'ios_sound' => 'Notification.wav',
			'ios_badgeType' => 'Increase',
			'ios_badgeCount' => 1,
			'url' => $url
		);
		$fields=json_encode($fields);
		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic NDliNmI2N2MtN2FhMi00MzM4LWJkYTEtNDUxYTYzZjIxMzRi'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
//error_log('onesignal res:'.$response);
		return $response;
	}
	
	public static function check_sns_instagram($username, $sns_id){
		$url='https://www.instagram.com/'.$sns_id;
		$html=file_get_contents($url);
		if(preg_match('/,"external_url":"https?:\/\/24h.tl\/'.$username.'\/?/i',$html)){
			return true;
		}else{
			return false;
		}
	}

	public static function check_sns_twitter($username, $sns_id){
		$url='https://twitter.com/'.$sns_id;
		$html=file_get_contents($url);
		list($tmp,$html)=split('ProfileHeaderCard-bio',$html,2);
		list($html,$tmp)=split('ProfileHeaderCard-joinDate',$html,2);
		if(preg_match('/title="https?:\/\/24h.tl\/'.$username.'\/?/i',$html)){
			return true;
		}else{
			return false;
		}
	}
	
}//<------------------- * END CLASS * ----------->

?>