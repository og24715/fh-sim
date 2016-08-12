<?
/**
 * アプデ前のパラメーターオブジェクトを返す
 * @param  [string] $path アプデ前のJSONへのパス
 * @return [obj]    　　　 古いパラメーターオブジェクトを返す
 */
function get_prev_params( $path ) {
	return json_decode( file_get_contents($path) );
}

/**
 * 装甲DBをスクレイピングし装甲オブジェクトを返す
 * @param  [string] $url パーツDBのURL
 * @return [obj] 装甲のパラメータのオブジェクトを返す
 */
function get_new_armor_params( $url ) {

	$dom = new DOMDocument;
	@$dom->loadHTMLFile( $url );
	$xpath = new DOMXPath( $dom );

	$xpath_query = 'body/div[@id="wp"]/div[@id="contents"]/div[@id="card_list_layer"]/div[@id="card_list_wrapper"]/ul[@id="cardlist"]//li';
	$node = $xpath->query($xpath_query);
	$parts = [];
	foreach ($node as $li) {
		
		$rob_id = $xpath->evaluate('string(./a/@data-card-id)', $li);

		$class = $xpath->evaluate('string(./@class)', $li);
		$parts[$rob_id]->update = preg_match( '/["\s]update/', $class ) ? 'y' : 'n';

		$parts[$rob_id]->name = $xpath->evaluate('string(./a/@data-card-name)', $li);
		
		$parts[$rob_id]->head->armor->min = $xpath->evaluate('string(./@data-headminarmor)', $li);
		$parts[$rob_id]->head->armor->max = $xpath->evaluate('string(./@data-headmaxarmor)', $li);
		$parts[$rob_id]->head->heat->min = $xpath->evaluate('string(./@data-headminheat)', $li);
		$parts[$rob_id]->head->heat->max = $xpath->evaluate('string(./@data-headmaxheat)', $li);
		$parts[$rob_id]->head->weight->min = $xpath->evaluate('string(./@data-headminweight)', $li);
		$parts[$rob_id]->head->weight->max = $xpath->evaluate('string(./@data-headmaxweight)', $li);

		$parts[$rob_id]->body->armor->min = $xpath->evaluate('string(./@data-bodyminarmor)', $li);
		$parts[$rob_id]->body->armor->max = $xpath->evaluate('string(./@data-bodymaxarmor)', $li);
		$parts[$rob_id]->body->heat->min = $xpath->evaluate('string(./@data-bodyminheat)', $li);
		$parts[$rob_id]->body->heat->max = $xpath->evaluate('string(./@data-bodymaxheat)', $li);
		$parts[$rob_id]->body->weight->min = $xpath->evaluate('string(./@data-bodyminweight)', $li);
		$parts[$rob_id]->body->weight->max = $xpath->evaluate('string(./@data-bodymaxweight)', $li);
	
		$parts[$rob_id]->arm->armor->min = $xpath->evaluate('string(./@data-armminarmor)', $li);
		$parts[$rob_id]->arm->armor->max = $xpath->evaluate('string(./@data-armmaxarmor)', $li);
		$parts[$rob_id]->arm->heat->min = $xpath->evaluate('string(./@data-armminheat)', $li);
		$parts[$rob_id]->arm->heat->max = $xpath->evaluate('string(./@data-armmaxheat)', $li);
		$parts[$rob_id]->arm->weight->min = $xpath->evaluate('string(./@data-armminweight)', $li);
		$parts[$rob_id]->arm->weight->max = $xpath->evaluate('string(./@data-armmaxweight)', $li);
		
		$parts[$rob_id]->leg->armor->min = $xpath->evaluate('string(./@data-legminarmor)', $li);
		$parts[$rob_id]->leg->armor->max = $xpath->evaluate('string(./@data-legmaxarmor)', $li);
		$parts[$rob_id]->leg->heat->min = $xpath->evaluate('string(./@data-legminheat)', $li);
		$parts[$rob_id]->leg->heat->max = $xpath->evaluate('string(./@data-legmaxheat)', $li);
		$parts[$rob_id]->leg->weight->min = $xpath->evaluate('string(./@data-legminweight)', $li);
		$parts[$rob_id]->leg->weight->max = $xpath->evaluate('string(./@data-legmaxweight)', $li);
	}

	return $parts;
}

/**
 * 装甲のアップデートの差分を返す
 * @param  [obj] $new_data  [description]
 * @param  [obj] $prev_data [description]
 * @return [obj]            [description]
 */
function diffUpdateArmor( $new_data, $prev_data ) {

	foreach ($new_data as $rob_id => $rob_obj) {
		if ( $rob_obj->update === 'n' ) continue; // アップデートが無かったらスルー
		$rob_name = $rob_obj->name;
		foreach ($rob_obj as $parts_id => $parts_obj) {
			if ( $parts_id === 'update' || $parts_id === 'name' ) continue;
			foreach ($parts_obj as $param => $value) {
				$prev_value = $prev_data->$rob_id->$parts_id->$param;
				if ($value->min !== $prev_value->min ||
					$value->max !== $prev_value->max) {
					$update->$rob_id->$parts_id->$param->max->new  = $value->max;
					$update->$rob_id->$parts_id->$param->max->prev = $prev_value->max;
					$update->$rob_id->$parts_id->$param->min->new  = $value->min;
					$update->$rob_id->$parts_id->$param->min->prev = $prev_value->min;			
				}	
			}
		}
		isset($update->$rob_id) && $update->$rob_id->name = $rob_name;
	}

	return $update;
}

/**
 * JSON ファイルを更新する
 * @param  [string] $file  [armorかweapon]
 * @param  [obj]    $parts [パーツオブジェクト]
 */
function update_prev_json( $file, $parts ) {
	$url = array(
		'armor'  => 'prevdata/prev_armor_data.json',
		'weapon' => 'prevdata/prev_weapon_data.json',
	);
	file_put_contents( $url[$file], json_encode( $parts ) );
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
