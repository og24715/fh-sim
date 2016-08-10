<?
/**
 * パーツDBからJSONを抽出する
 * 抽出に成功したらJSONを返す
 * 
 * @param  [string] $url       [データベースのURL]
 * @param  [string] $star_word [description]
 * @param  [string] $end_word  [description]
 * @return [string]            [description]
 */
function getNewJSON( $url, $start_word= 'InitCardData', $end_word= '}}' ) {
	$html= file_get_contents( $url );	
	$start= strpos( $html, $start_word );
	$end= strpos( $html, $end_word, $start);
	$length = $end + strlen($end_word) - $start;
	$json = substr( $html, $start, $length );
	$json = strstr( $json, '{');

	return $json;
}

/**
 * 古いJSONを返す
 * @param  string $url [description]
 * @return [sring]     [description]
 */
function getPrevJSON( $url ) {
	return file_get_contents( $url );
}

/**
 * 装甲のアップデートの差分を返す
 * @param  [obj] $new_data  [description]
 * @param  [obj] $prev_data [description]
 * @return [obj]            [description]
 */
function diffUpdateArmor( $new_data, $prev_data ) {

	foreach ($new_data as $rob_id => $rob_obj) {
		// アップデートが無かったらスルー
		if ( $rob_obj->updateinfo !== "update" ) continue;
		
		$rob_name= $rob_obj->cardname;
		foreach ($rob_obj as $param => $new_value) {
			
			$prev_value = $prev_data->$rob_id->$param;
			if ( $new_value != $prev_value ) {

				switch ( $param ) {
					
					// head min
					case 'headminarmor':
						$update->$rob_name->head->armor->min->prev = $prev_value;
						$update->$rob_name->head->armor->min->new = $new_value;
						break;
					case 'headminheat':
						$update->$rob_name->head->heat->min->prev = $prev_value;
						$update->$rob_name->head->heat->min->new = $new_value;
						break;
					case 'headminweight':
						$update->$rob_name->head->weight->min->prev = $prev_value;
						$update->$rob_name->head->weight->min->new = $new_value;
						break;

					// body min
					case 'bodyminarmor':
						$update->$rob_name->body->armor->min->prev = $prev_value;
						$update->$rob_name->body->armor->min->new = $new_value;
						break;
					case 'bodyminheat':
						$update->$rob_name->body->heat->min->prev = $prev_value;
						$update->$rob_name->body->heat->min->new = $new_value;
						break;
					case 'bodyminweight':
						$update->$rob_name->body->weight->min->prev = $prev_value;
						$update->$rob_name->body->weight->min->new = $new_value;
						break;

					// arm min
					case 'armminarmor':
						$update->$rob_name->arm->armor->min->prev = $prev_value;
						$update->$rob_name->arm->armor->min->new = $new_value;
						break;
					case 'armminheat':
						$update->$rob_name->arm->heat->min->prev = $prev_value;
						$update->$rob_name->arm->heat->min->new = $new_value;
						break;
					case 'armminweight':
						$update->$rob_name->arm->weight->min->prev = $prev_value;
						$update->$rob_name->arm->weight->min->new = $new_value;
						break;

					// leg min
					case 'legminarmor':
						$update->$rob_name->leg->armor->min->prev = $prev_value;
						$update->$rob_name->leg->armor->min->new = $new_value;
						break;
					case 'legminheat':
						$update->$rob_name->leg->heat->min->prev = $prev_value;
						$update->$rob_name->leg->heat->min->new = $new_value;
						break;
					case 'legminweight':
						$update->$rob_name->leg->weight->min->prev = $prev_value;
						$update->$rob_name->leg->weight->min->new = $new_value;
						break;
					
					// head max
					case 'headmaxarmor':
						$update->$rob_name->head->armor->max->prev = $prev_value;
						$update->$rob_name->head->armor->max->new = $new_value;
						break;
					case 'headmaxheat':
						$update->$rob_name->head->heat->max->prev = $prev_value;
						$update->$rob_name->head->heat->max->new = $new_value;
						break;
					case 'headmaxweight':
						$update->$rob_name->head->weight->max->prev = $prev_value;
						$update->$rob_name->head->weight->max->new = $new_value;
						break;

					// body max
					case 'bodymaxarmor':
						$update->$rob_name->body->armor->max->prev = $prev_value;
						$update->$rob_name->body->armor->max->new = $new_value;
						break;
					case 'bodymaxheat':
						$update->$rob_name->body->heat->max->prev = $prev_value;
						$update->$rob_name->body->heat->max->new = $new_value;
						break;
					case 'bodymaxweight':
						$update->$rob_name->body->weight->max->prev = $prev_value;
						$update->$rob_name->body->weight->max->new = $new_value;
						break;

					// arm max
					case 'armmaxarmor':
						$update->$rob_name->arm->armor->max->prev = $prev_value;
						$update->$rob_name->arm->armor->max->new = $new_value;
						break;
					case 'armmaxheat':
						$update->$rob_name->arm->heat->max->prev = $prev_value;
						$update->$rob_name->arm->heat->max->new = $new_value;
						break;
					case 'armmaxweight':
						$update->$rob_name->arm->weight->max->prev = $prev_value;
						$update->$rob_name->arm->weight->max->new = $new_value;
						break;

					// leg max
					case 'legmaxarmor':
						$update->$rob_name->leg->armor->max->prev = $prev_value;
						$update->$rob_name->leg->armor->max->new = $new_value;
						break;
					case 'legmaxheat':
						$update->$rob_name->leg->heat->max->prev = $prev_value;
						$update->$rob_name->leg->heat->max->new = $new_value;
						break;
					case 'legmaxweight':
						$update->$rob_name->leg->weight->max->prev = $prev_value;
						$update->$rob_name->leg->weight->max->new = $new_value;
						break;

				}
			}
		}
	}

	return $update;
}

/**
 * 武器のアップデートの差分を返す
 * @param  [obj] $new_data  [description]
 * @param  [obj] $prev_data [description]
 * @return [obj]            [description]
 */
function diffUpdateWeapon( $new_data, $prev_data ) {

	foreach ($new_data as $weapon_id => $weapon_obj) {
		// アップデートが無かったらスルー
		if ( $weapon_obj->updateinfo !== "update" ) continue;

		$weapon_name = $weapon_obj->cardname;
		// debug
		// echo "<p>$weapon_name が調整されました。</p>";
		foreach ($weapon_obj as $param => $new_value) {
			$prev_value = $prev_data->$weapon_id->$param;
			if ( $new_value === $prev_value ) continue;

			// debug
			// echo "$param : $prev_value -> $new_value <br>";
			switch ( $param ) {
				case 'pointattackmaxgrade':
					$update->$weapon_name->attack->max->prev = $prev_value;
					$update->$weapon_name->attack->max->new = $new_value;
					break;
				case 'pointattackmingrade':
					$update->$weapon_name->attack->min->prev = $prev_value;
					$update->$weapon_name->attack->min->new = $new_value;
					break;

				case 'pointreloadmaxgrade':
					$update->$weapon_name->reload->max->prev = $prev_value;
					$update->$weapon_name->reload->max->new = $new_value;
					break;
				case 'pointreloadmingrade':
					$update->$weapon_name->reload->min->prev = $prev_value;
					$update->$weapon_name->reload->min->new = $new_value;
					break;
				
				case 'pointrangemaxgrade':
					$update->$weapon_name->range->max->prev = $prev_value;
					$update->$weapon_name->range->max->new = $new_value;
					break;
				case 'pointrangemingrade':
					$update->$weapon_name->range->min->prev = $prev_value;
					$update->$weapon_name->range->min->new = $new_value;
					break;
				
				case 'pointaimmaxgrade':
					$update->$weapon_name->aim->max->prev = $prev_value;
					$update->$weapon_name->aim->max->new = $new_value;
					break;
				case 'pointaimmingrade':
					$update->$weapon_name->aim->min->prev = $prev_value;
					$update->$weapon_name->aim->min->new = $new_value;
					break;

				case 'pointstabilitymaxgrade':
					$update->$weapon_name->stability->max->prev = $prev_value;
					$update->$weapon_name->stability->max->new = $new_value;
					break;
				case 'pointstabilitymingrade':
					$update->$weapon_name->stability->min->prev = $prev_value;
					$update->$weapon_name->stability->min->new = $new_value;
					break;
				
				case 'pointrapidfiremaxgrade':
					$update->$weapon_name->rapidfire->max->prev = $prev_value;
					$update->$weapon_name->rapidfire->max->new = $new_value;
					break;
				case 'pointrapidfiremingrade':
					$update->$weapon_name->rapidfire->min->prev = $prev_value;
					$update->$weapon_name->rapidfire->min->new = $new_value;
					break;
				
				case 'pointweightmaxgrade':
					$update->$weapon_name->weight->max->prev = $prev_value;
					$update->$weapon_name->weight->max->new = $new_value;
					break;
				case 'pointweightmingrade':
					$update->$weapon_name->weight->min->prev = $prev_value;
					$update->$weapon_name->weight->min->new = $new_value;
					break;
				
				case 'pointloadmaxgrade':
					$update->$weapon_name->load->max->prev = $prev_value;
					$update->$weapon_name->load->max->new = $new_value;
					break;
				case 'pointloadmingrade':
					$update->$weapon_name->load->min->prev = $prev_value;
					$update->$weapon_name->load->min->new = $new_value;
					break;
				
				case 'pointbulletmaxgrade':
					$update->$weapon_name->bullet->max->prev = $prev_value;
					$update->$weapon_name->bullet->max->new = $new_value;
					break;
				case 'pointbulletmingrade':
					$update->$weapon_name->bullet->min->prev = $prev_value;
					$update->$weapon_name->bullet->min->new = $new_value;
					break;
				
				case 'pointspeedmaxgrade':
					$update->$weapon_name->speed->max->prev = $prev_value;
					$update->$weapon_name->speed->max->new = $new_value;
					break;
				case 'pointspeedmingrade':
					$update->$weapon_name->speed->min->prev = $prev_value;
					$update->$weapon_name->speed->min->new = $new_value;
					break;
				
				case 'pointfallmaxgrade':
					$update->$weapon_name->fall->max->prev = $prev_value;
					$update->$weapon_name->fall->max->new = $new_value;
					break;
				case 'pointfallmingrade':
					$update->$weapon_name->fall->min->prev = $prev_value;
					$update->$weapon_name->fall->min->new = $new_value;
					break;
				
				case 'pointbombmaxgrade':
					$update->$weapon_name->bomb->max->prev = $prev_value;
					$update->$weapon_name->bomb->max->new = $new_value;
					break;
				case 'pointbombmingrade':
					$update->$weapon_name->bomb->min->prev = $prev_value;
					$update->$weapon_name->bomb->min->new = $new_value;
					break;
				
				case 'pointheatmaxgrade':
					$update->$weapon_name->heat->max->prev = $prev_value;
					$update->$weapon_name->heat->max->new = $new_value;
					break;
				case 'pointheatmingrade':
					$update->$weapon_name->heat->min->prev = $prev_value;
					$update->$weapon_name->heat->min->new = $new_value;
					break;
				
				case 'pointcoolmaxgrade':
					$update->$weapon_name->cool->max->prev = $prev_value;
					$update->$weapon_name->cool->max->new = $new_value;
					break;
				case 'pointcoolmingrade':
					$update->$weapon_name->cool->min->prev = $prev_value;
					$update->$weapon_name->cool->min->new = $new_value;
					break;
				
				case 'pointareamaxgrade':
					$update->$weapon_name->area->max->prev = $prev_value;
					$update->$weapon_name->area->max->new = $new_value;
					break;
				case 'pointareamingrade':
					$update->$weapon_name->area->min->prev = $prev_value;
					$update->$weapon_name->area->min->new = $new_value;
					break;
				
				case 'pointlockonmaxgrade':
					$update->$weapon_name->lockon->max->prev = $prev_value;
					$update->$weapon_name->lockon->max->new = $new_value;
					break;
				case 'pointlockonmingrade':
					$update->$weapon_name->lockon->min->prev = $prev_value;
					$update->$weapon_name->lockon->min->new = $new_value;
					break;

				case 'pointchargemaxgrade':
					$update->$weapon_name->charge->max->prev = $prev_value;
					$update->$weapon_name->charge->max->new = $new_value;
					break;
				case 'pointchargemingrade':
					$update->$weapon_name->charge->min->prev = $prev_value;
					$update->$weapon_name->charge->min->new = $new_value;
					break;
				
				case 'pointfallspeedmaxgrade':
					$update->$weapon_name->fallspeed->max->prev = $prev_value;
					$update->$weapon_name->fallspeed->max->new = $new_value;
					break;
				case 'pointfallspeedmingrade':
					$update->$weapon_name->fallspeed->min->prev = $prev_value;
					$update->$weapon_name->fallspeed->min->new = $new_value;
					break;
				
				case 'pointfallrangemaxgrade':
					$update->$weapon_name->fallrange->max->prev = $prev_value;
					$update->$weapon_name->fallrange->max->new = $new_value;
					break;
				case 'pointfallrangemingrade':
					$update->$weapon_name->fallrange->min->prev = $prev_value;
					$update->$weapon_name->fallrange->min->new = $new_value;
					break;
				
				case 'pointbombmaxgrade':
					$update->$weapon_name->bomb->max->prev = $prev_value;
					$update->$weapon_name->bomb->max->new = $new_value;
					break;
				case 'pointbombmingrade':
					$update->$weapon_name->bomb->min->prev = $prev_value;
					$update->$weapon_name->bomb->min->new = $new_value;
					break;
			}
		}

	}
	
	return $update;

}

/**
 * 装甲のアップデートをつぶやく
 * @param  [obj] $diff_armor [description]
 * @return [type]             [description]
 */
function tweetUpdateArmor( $diff_armor ) {
	
	if ( empty($diff_armor) ) exit();

	$wordmap = array(
		'head'   => '頭',
		'body'   => '胴',
		'arm'    => '腕',
		'leg'    => '脚',

		'armor'  => '装甲値',
		'heat'   => '耐熱値',
		'weight' => '重　量'
	);

	foreach ($diff_armor as $rob_name => $rob_obj) {
		// debug
		// echo "$rob_name に調整がありました<br>";
		$text = "$rob_name\r\n";
		
		foreach ($rob_obj as $part_name => $params) {
			// debug
			// echo "$part_name <br>";
			$text .= "$wordmap[$part_name]";
			foreach ($params as $param_name => $param) {
				// debug
				// echo $param_name . ' : ';
				// echo "{$param->min->prev}({$param->max->prev}) ->";
				// echo "{$param->min->new}({$param->max->new}) <br>";
				$text .= $flag ? '　' : '';

				$text .= "　$wordmap[$param_name]:";
				$text .= "{$param->min->prev}({$param->max->prev})→";
				$text .= "{$param->min->new}({$param->max->new}) \r\n";
				
				$flag = 1;
			}
			$flag = 0;

		}
		oauthTweet( $text );
	}

}

function tweetUpdateWeapon( $diff_weapon ) {
	
	if ( empty($diff_weapon) ) exit();

	$wordmap = array(
		'attack'    => '攻撃力　',
		'reload'    => 'リロード',
		'range'     => '射程　　',
		'aim'       => '精度　　',
		'stability' => '安定性　',
		'rapidfire' => '連射速度',
		'weight'    => '重量　　',
		'load'      => '装填数 ',
		'bullet'    => '装弾数　',
		'speed'     => '弾速　　',
		'fall'      => '落下距離',
		'bomb'      => '爆発範囲',
		'heat'      => '熱容量　',
		'cool'      => '冷却速度',
		'area'      => '落下範囲',
		'lockon'    => 'ロック速度',
		'charge'    => 'チャージ速度',
		'fallspeed' => '落下速度',
		'fallrange' => '砲撃角度',
	);

	foreach ($diff_weapon as $weapon_name => $weapon_obj) {
		// debug
		// echo "$weapon_name に調整がありました<br>";
		$text = "$weapon_name\r\n";
		
		foreach ($weapon_obj as $param_name => $value) {
			// debug
			// echo $wordmap[$param_name] . ' : ';
			// echo "{$value->min->prev}({$value->max->prev}) ->";
			// echo "{$value->min->new}({$value->max->new}) <br>";

			$text .= "$wordmap[$param_name]:";
			$text .= "{$value->min->prev}({$value->max->prev})→";
			$text .= "{$value->min->new}({$value->max->new}) \r\n";
			
		}

		oauthTweet( $text );
	}

}

/**
 * つぶやく
 * @param  [obj] &$to  [description]
 * @param  [string] $text [description]
 * @return [type]       [description]
 */
function oauthTweet( $text ) {
	
	global $to;

	try {

		$to->post('statuses/update', array('status' => $text));
	
	} catch (TwistException $e) {

		// Set error message.
		$error = $e->getMessage();

		// Overwrite HTTP status code.
		// The exception code will be zero when it thrown before accessing Twitter, we need to change it into 500.
		$code = $e->getCode() ?: 500;

	}
}
