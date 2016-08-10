<?

require 'connect.php';
require 'function.php';

define("ARM_DB_HTML",      "http://figureheads.jp/database/index.php");            // 運用時 figureheads.jp/database/index.php
define("ARM_PREV_JSON",    "prevdata/prev_armor_data.json");                 // 運用時 prevdata/prev_armor_data.json
define("WEAPON_DB_HTML",   "http://figureheads.jp/database/weapon.php ");           // 運用時 figureheads.jp/database/weapon.php
define("WEAPON_PREV_JSON", "prevdata/prev_weapon_data.json ");               // 運用時 prevdata/prev_weapon_data.json


// 新しい装甲 JSON を読み込む
$new_armor_json = getNewJSON( ARM_DB_HTML );
$new_armor_data = json_decode( $new_armor_json );
// 古い装甲 JSON を読み込む
$prev_armor_json = getPrevJSON( ARM_PREV_JSON );
$prev_armor_data = json_decode( $prev_armor_json );
// 装甲のアップデートの差分を取る
$diff_armor = diffUpdateArmor( $new_armor_data, $prev_armor_data );

// 新しい武器 JSON を読み込む
$new_weapon_json = getNewJSON( WEAPON_DB_HTML );
$new_weapon_data = json_decode( $new_weapon_json );
// 古い武器 JSON を読み込む
$prev_weapon_json = getPrevJSON( WEAPON_PREV_JSON ); 
$prev_weapon_data = json_decode( $prev_weapon_json );
// 武器のアップデートの差分を取る
$diff_weapon = diffUpdateWeapon( $new_weapon_data, $prev_weapon_data );


// つぶやく
tweetUpdateArmor( $diff_armor );
tweetUpdateWeapon( $diff_weapon );

print_r( $diff_armor );

// JSON を更新する
file_put_contents('testdata/old/prev_armor_data.json', $new_armor_json);
file_put_contents('testdata/old/prev_weapon_data.json', $new_weapon_json);
