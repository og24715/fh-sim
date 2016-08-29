<?

trait file_access {

	public function get_new_json()
	{
		$path = (get_class($this)==='Armor') ? 'data/new/armor.json' : 'data/new/weapon.json';
		file_exists($path) ?: exit("not fonund $path");
		return file_get_contents($path);	
	}
	public function get_old_json()
	{
		$path = (get_class($this)==='Armor') ? 'data/old/armor.json' : 'data/old/weapon.json';
		file_exists($path) ?: exit("not fonund $path");
		return file_get_contents($path);	
	}
	public function update_json()
	{
		$this->old_params = json_decode( $this->get_new_json() );
		$this->new_params = $this->scraping_db();
		$this->diff = $this->get_diff();

		$path = (get_class($this)==='Armor') ? 'data/old/armor.json' : 'data/old/weapon.json';
		file_put_contents( $path, json_encode( $this->old_params ) ) ?: exit('cant write' . $path);
		$path = (get_class($this)==='Armor') ? 'data/new/armor.json' : 'data/new/weapon.json';
		file_put_contents( $path, json_encode( $this->new_params ) ) ?: exit('cant write' . $path);
		$path = (get_class($this)==='Armor') ? 'data/diff/armor.json' : 'data/diff/weapon.json';
		file_put_contents( $path, json_encode( $this->diff ) ) ?: exit('cant write' . $path);	
	}

}


class Armor {
	
	use file_access;

	private $old_params;
	private $new_params;
	public $diff;

	/**
	 * 装甲DBをスクレイピングし装甲オブジェクトを返す
	 * @param  [string] $url パーツDBのURL
	 * @return [obj] 装甲のパラメータのオブジェクトを返す
	 */
	private function scraping_db() {

		$url = 'http://figureheads.jp/database/index.php';
		$dom = new DOMDocument;
		@$dom->loadHTMLFile( $url );
		$xpath = new DOMXPath( $dom );

		$xpath_query = '//*[@id="cardlist"]/li';
		$node= $xpath->query($xpath_query);

		foreach ($node as $li) {
			
			$rob_id = $xpath->evaluate('string(./a/@data-card-id)', $li);

			$class = $xpath->evaluate('string(./@class)', $li);
			$params->$rob_id->update = preg_match( '/["\s]update/', $class ) ? 'y' : 'n';

			$params->$rob_id->name = $xpath->evaluate('string(./a/@data-card-name)', $li);
			$params->$rob_id->class = $xpath->evaluate('string(./a/@data-class-id)', $li);
			$params->$rob_id->corp = $xpath->evaluate('string(./a/@data-corp-id)', $li);
			$params->$rob_id->desc = $xpath->evaluate('string(./@data-description)', $li);
			
			$params->$rob_id->param->head->armor = $xpath->evaluate('string(./@data-headminarmor)', $li);
			$params->$rob_id->param->head->heat = $xpath->evaluate('string(./@data-headminheat)', $li);
			$params->$rob_id->param->head->weight = $xpath->evaluate('string(./@data-headminweight)', $li);

			$params->$rob_id->param->body->armor = $xpath->evaluate('string(./@data-bodyminarmor)', $li);
			$params->$rob_id->param->body->heat = $xpath->evaluate('string(./@data-bodyminheat)', $li);
			$params->$rob_id->param->body->weight = $xpath->evaluate('string(./@data-bodyminweight)', $li);
		
			$params->$rob_id->param->arm->armor = $xpath->evaluate('string(./@data-armminarmor)', $li);
			$params->$rob_id->param->arm->heat = $xpath->evaluate('string(./@data-armminheat)', $li);
			$params->$rob_id->param->arm->weight = $xpath->evaluate('string(./@data-armminweight)', $li);
			
			$params->$rob_id->param->leg->armor = $xpath->evaluate('string(./@data-legminarmor)', $li);
			$params->$rob_id->param->leg->heat = $xpath->evaluate('string(./@data-legminheat)', $li);
			$params->$rob_id->param->leg->weight = $xpath->evaluate('string(./@data-legminweight)', $li);
		}

		return $params;
	}

	/**
	 * 装甲のアップデートの差分を返す
	 * @param  [obj] $new_data  [description]
	 * @param  [obj] $prev_data [description]
	 * @return [obj]            [description]
	 */
	private function get_diff() {
		$new_params = $this->new_params;
		$old_params = $this->old_params;

		foreach ($new_params as $rob_id => $robot) {
			if ( $robot->update === 'n' ) continue; // アップデートが無かったらスルー
			$update->$rob_id->name = $robot->name;
			foreach ($robot->param as $part_id => $part) {
				foreach ($part as $type => $new_value) {
					$old_value = $old_params->$rob_id->param->$part_id->$type;
					if ($new_value != $old_value) {
						$update->$rob_id->$part_id->$type->new  = $new_value;
						$update->$rob_id->$part_id->$type->old = $old_value;
					}	
				}
			}
		}
		return $update;
	}

	/**
	 * Tweet用のテキストを生成する
	 * @param  [obj] $this->update [description]
	 * @return [type]             [description]
	 */
	public function get_update_text() {
		$update = $this->update;
		if ( empty($update) ) exit();
		
		$wordmap = array(
			'head'   => '頭',
			'body'   => '胴',
			'arm'    => '腕',
			'leg'    => '脚',

			'armor'  => '装甲',
			'heat'   => '耐熱',
			'weight' => '重量'
		);

		foreach ($update as $rob_id => $rob_obj) {
			$text->$rob_id = "$rob_obj->name\r\n";
			foreach ($rob_obj as $part_name => $params) {
				if ($part_name==='name') continue;				
				$text->$rob_id .= "$wordmap[$part_name]";
				foreach ($params as $type => $param) {			
					$text->$rob_id .= $flag ? '　' : '';
					$text->$rob_id .= "　$wordmap[$type]:";
					$text->$rob_id .= "{$param->min->old}({$param->max->old})→";
					$text->$rob_id .= "{$param->min->new}({$param->max->new}) \r\n";
					
					$flag = 1;
				}
				$flag = 0;
			}
		}
		return $text;
	} // END create_tweet_text()
}

class Weapon {
	
	use file_access;

	private $old_params;
	private $new_params;
	public $diff;

	/**
	 * 装甲DBをスクレイピングし装甲オブジェクトを返す
	 * @param  [string] $url パーツDBのURL
	 * @return [obj] 装甲のパラメータのオブジェクトを返す
	 */
	private function scraping_db() {

		$url = 'http://figureheads.jp/database/weapon.php';
		$dom = new DOMDocument;
		@$dom->loadHTMLFile( $url );
		$xpath = new DOMXPath( $dom );

		$xpath_query = '//*[@id="cardlist"]/li';
		$node = $xpath->query($xpath_query);

		foreach ($node as $li) {
			// key
			$wep_id = $xpath->evaluate('string(./a/@data-card-id)', $li);
			// update
			$class = $xpath->evaluate('string(./@class)', $li);
			$params->$wep_id->update = preg_match( '/["\s]update/', $class ) ? 'y' : 'n';
			// name 
			$params->$wep_id->name = $xpath->evaluate('string(./a/@data-card-name)', $li);
			// category
			$params->$wep_id->category = $xpath->evaluate('string(./a/@data-category-name)', $li);
			// equip
			$xpath->evaluate('string(./@data-equip-as)', $li) === '○' && $params->$wep_id->equip[] ='as';
			$xpath->evaluate('string(./@data-equip-sp)', $li) === '○' && $params->$wep_id->equip[] ='sp';
			$xpath->evaluate('string(./@data-equip-st)', $li) === '○' && $params->$wep_id->equip[] ='st';
			$xpath->evaluate('string(./@data-equip-hv)', $li) === '○' && $params->$wep_id->equip[] ='hv';
			$xpath->evaluate('string(./@data-equip-sn)', $li) === '○' && $params->$wep_id->equip[] ='sn';
			$xpath->evaluate('string(./@data-equip-en)', $li) === '○' && $params->$wep_id->equip[] ='en';
			// corp
			$params->$wep_id->corp = $xpath->evaluate('string(./a/@data-corp-id)', $li);
			// description
			$params->$wep_id->desc = $xpath->evaluate('string(./@data-description)', $li);

			// attack
			$params->$wep_id->param->attack->max = $xpath->evaluate('number(./@data-attack-maxscore)', $li);
			$params->$wep_id->param->attack->min = $xpath->evaluate('number(./@data-attack-minscore)', $li);
			// reload
			$params->$wep_id->param->reload->max = $xpath->evaluate('number(./@data-reload-maxscore)', $li);
			$params->$wep_id->param->reload->min = $xpath->evaluate('number(./@data-reload-minscore)', $li);
			// range
			$params->$wep_id->param->range->max = $xpath->evaluate('number(./@data-range-maxscore)', $li);
			$params->$wep_id->param->range->min = $xpath->evaluate('number(./@data-range-minscore)', $li);
			// aim
			$params->$wep_id->param->aim->max = $xpath->evaluate('number(./@data-aim-maxscore)', $li);
			$params->$wep_id->param->aim->min = $xpath->evaluate('number(./@data-aim-minscore)', $li);
			// stability
			$params->$wep_id->param->stability->max = $xpath->evaluate('number(./@data-stability-maxscore)', $li);
			$params->$wep_id->param->stability->min = $xpath->evaluate('number(./@data-stability-minscore)', $li);
			// rapidfire
			$params->$wep_id->param->rapidfire->max = $xpath->evaluate('number(./@data-rapidfire-maxscore)', $li);
			$params->$wep_id->param->rapidfire->min = $xpath->evaluate('number(./@data-rapidfire-minscore)', $li);
			// weight
			$params->$wep_id->param->weight->max = $xpath->evaluate('number(./@data-weight-maxscore)', $li);
			$params->$wep_id->param->weight->min = $xpath->evaluate('number(./@data-weight-minscore)', $li);
			// load
			$params->$wep_id->param->load->max = $xpath->evaluate('number(./@data-load-maxscore)', $li);
			$params->$wep_id->param->load->min = $xpath->evaluate('number(./@data-load-minscore)', $li);
			// bullet
			$params->$wep_id->param->bullet->max = $xpath->evaluate('number(./@data-bullet-maxscore)', $li);
			$params->$wep_id->param->bullet->min = $xpath->evaluate('number(./@data-bullet-minscore)', $li);
			// speed
			$params->$wep_id->param->speed->max = $xpath->evaluate('number(./@data-speed-maxscore)', $li);
			$params->$wep_id->param->speed->min = $xpath->evaluate('number(./@data-speed-minscore)', $li);
			// fall
			$params->$wep_id->param->fall->max = $xpath->evaluate('number(./@data-fall-maxscore)', $li);
			$params->$wep_id->param->fall->min = $xpath->evaluate('number(./@data-fall-minscore)', $li);
			// bomb
			$params->$wep_id->param->bomb->max = $xpath->evaluate('number(./@data-bomb-maxscore)', $li);
			$params->$wep_id->param->bomb->min = $xpath->evaluate('number(./@data-bomb-minscore)', $li);
			// heat
			$params->$wep_id->param->heat->max = $xpath->evaluate('number(./@data-heat-maxscore)', $li);
			$params->$wep_id->param->heat->min = $xpath->evaluate('number(./@data-heat-minscore)', $li);
			// cool
			$params->$wep_id->param->cool->max = $xpath->evaluate('number(./@data-cool-maxscore)', $li);
			$params->$wep_id->param->cool->min = $xpath->evaluate('number(./@data-cool-minscore)', $li);
			// area
			$params->$wep_id->param->area->max = $xpath->evaluate('number(./@data-area-maxscore)', $li);
			$params->$wep_id->param->area->min = $xpath->evaluate('number(./@data-area-minscore)', $li);
			// lockon
			$params->$wep_id->param->lockon->max = $xpath->evaluate('number(./@data-lockon-maxscore)', $li);
			$params->$wep_id->param->lockon->min = $xpath->evaluate('number(./@data-lockon-minscore)', $li);
			// charge
			$params->$wep_id->param->charge->max = $xpath->evaluate('number(./@data-charge-maxscore)', $li);
			$params->$wep_id->param->charge->min = $xpath->evaluate('number(./@data-charge-minscore)', $li);
			// fallspeed
			$params->$wep_id->param->fallspeed->max = $xpath->evaluate('number(./@data-fallspeed-maxscore)', $li);
			$params->$wep_id->param->fallspeed->min = $xpath->evaluate('number(./@data-fallspeed-minscore)', $li);
			// fallrange
			$params->$wep_id->param->fallrange->max = $xpath->evaluate('number(./@data-fallrange-maxscore)', $li);
			$params->$wep_id->param->fallrange->min = $xpath->evaluate('number(./@data-fallrange-minscore)', $li);
		}

		return $params;
	}

	/**
	 * 武器のアップデートの差分を返す
	 * @param  [obj] $new_data  [description]
	 * @param  [obj] $prev_data [description]
	 * @return [obj]            [description]
	 */
	private function get_diff() {
		$new_params = $this->new_params;
		$old_params = $this->old_params;
		
		foreach ( $new_params as $wep_id => $weapon ) {
			if ( $weapon->update === "n" ) continue; // アップデートが無かったらスルー
			$update->$wep_id->name = $weapon->name;
			foreach ($weapon->param as $param => $new_value) {
				$old_value = $old_params->$wep_id->param->$param;
				if ( $new_value != $old_value ) {
					$update->$wep_id->$param->max->new = $new_value->max;
					$update->$wep_id->$param->max->old = $old_value->max;
					$update->$wep_id->$param->min->new = $new_value->min;
					$update->$wep_id->$param->min->old = $old_value->min;
				}
			}
		}
		return $update;
	}

	public function get_update_text() {
		$update = $this->update;

		if ( empty($update) ) exit();
		$text = [];
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

		foreach ($update as $wep_id => $weapon) {
			$text->$wep_id = "$weapon->name\r\n";	
			foreach ($weapon as $param => $value) {
				if ($param === 'name') continue;
				$text->$wep_id .= "$wordmap[$param]:";
				$text->$wep_id .= "{$value->min->old}({$value->max->old})→";
				$text->$wep_id .= "{$value->min->new}({$value->max->new}) \r\n";
			}
		}
		return $text;
	} // END get_update_text()
}