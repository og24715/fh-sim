<?php

// require 'connect.php';
require 'function.php';

define("NEW_ARMOR_DB",     "testdata/new/armor.html"); // http://figureheads.jp/database/index.php
define("PREV_ARMOR_JSON",  "prevdata/prev_armor_data.json");
define("NEW_WEAPON_DB",    "http://figureheads.jp/database/weapon.php");
define("PREV_WEAPON_JSON", "prevdata/prev_weapon_data.json");

$new_armor_data  = get_new_armor_params( NEW_ARMOR_DB ); // 新しい装甲パラメーターオブジェクトを取得する
$prev_armor_data = get_prev_params( PREV_ARMOR_JSON ); // 古い装甲のパラメーターオブジェクトを取得する

echo '<pre>';
print_r( diffUpdateArmor( $new_armor_data, $prev_armor_data ) );
echo '</pre>';


// json を更新する
// update_prev_json( 'armor', $new_armor_data );
// update_prev_json( 'weapon', $new_weapon_data );

/*
// 新しい装甲 JSON を読み込む
$new_armor_json = getNewJSON( ARM_DB_HTML );
$new_armor_data = json_decode( $new_armor_json );
echo 'new_armor_data...' . ( (isset($new_armor_data)) ? "ok":"err" ) . '<br>';
// 古い装甲 JSON を読み込む
$prev_armor_json = getPrevJSON( ARM_PREV_JSON );
$prev_armor_data = json_decode( $prev_armor_json );
echo 'prev_armor_data...' . ( (isset($prev_armor_data)) ? "ok":"err" ) . '<br>';
// 装甲のアップデートの差分を取る
$diff_armor = diffUpdateArmor( $new_armor_data, $prev_armor_data );

// 新しい武器 JSON を読み込む
$new_weapon_json = getNewJSON( WEAPON_DB_HTML );
$new_weapon_data = json_decode( $new_weapon_json );
echo 'new_weapon_data...' . ( (isset($new_weapon_data)) ? "ok":"err" ) . '<br>';
// 古い武器 JSON を読み込む
$prev_weapon_json = getPrevJSON( WEAPON_PREV_JSON ); 
$prev_weapon_data = json_decode( $prev_weapon_json );
echo 'prev_weapon_data...' . ( (isset($prev_weapon_data)) ? "ok":"err" ) . '<br>';
// 武器のアップデートの差分を取る
$diff_weapon = diffUpdateWeapon( $new_weapon_data, $prev_weapon_data );

// つぶやく
tweetUpdateArmor( $diff_armor );
tweetUpdateWeapon( $diff_weapon );

// JSON を更新する
file_put_contents('testdata/old/prev_armor_data.json', $new_armor_json);
file_put_contents('testdata/old/prev_weapon_data.json', $new_weapon_json);
