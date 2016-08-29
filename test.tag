<test>
	<div class="container">
		<div class="header clearfix">
			<nav>
				<ul class="nav nav-pills pull-xs-right">
					<li class="nav-item">
						<a class="nav-link" href="http://www.amazon.co.jp/registry/wishlist/G8ZLKGYLXIHR">乞食</a>
					</li>
				</ul>
			</nav>
			<h3 class="text-muted">FH sim</h3>
		</div>

		<div class="jumbotron">
			<h1 class="display-3">FH assemble sim</h1>
			<p class="lead">サクッと重量ボーナスを知りたい時にどうぞ</p>
		</div>

		<div class="form-group row">
			<label for="class" class="col-xs-2 col-form-label">兵種</label>
			<div class="col-xs-4">
				<select onchange="{ changeClass }" id="class" class="form-control form-control-sm">
					<option selected value="noselect">**********</option>
					<option value="as">アサルト</option>
					<option value="sp">サポート</option>
					<option value="sn">スナイパー</option>
					<option value="en">エンジニア</option>
					<option value="hv">ヘヴィアサルト</option>
					<option value="st">ストライカー</option>
				</select>
			</div>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th></th>
					<th>パーツ</th>
					<th>グレード</th>
					<th>装甲値</th>
					<th>耐熱値</th>
					<th>重量</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">頭</th>
					<td>
						<select onchange="{ changeParts }" id="head" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, robot in data_rob }" value="{key}" class={ hide : robot.class !== select_class }>{ robot.corp } { robot.name }</option>
						</select>
					</td>
					<td><input type="number" value="1" min="1" max="10" class="form-control form-control-sm" onchange="{ changeGrade }" id="head_grade"></td>
					<td>{ +robot.head.armor+ addition.head.armor }</td>
					<td>{ +robot.head.heat+ addition.head.heat }</td>
					<td>{ +robot.head.weight- addition.head.weight }</td>
				</tr>
				<tr>
					<th scope="row">胴</th>
					<td>
						<select onchange="{ changeParts }" id="body" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, robot in data_rob }" value="{key}" class={ hide : robot.class !== select_class }>{ robot.corp } {robot.name}</option>
						</select>
					</td>
					<td><input type="number" value="1" min="1" max="10" class="form-control form-control-sm" onchange="{ changeGrade }" id="body_grade"></td>
					<td>{ +robot.body.armor+ addition.body.armor }</td>
					<td>{ +robot.body.heat+ addition.body.heat }</td>
					<td>{ +robot.body.weight- addition.body.weight }</td>
				</tr>
				<tr>
					<th scope="row">腕</th>
					<td>
						<select onchange="{ changeParts }" id="arm" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, robot in data_rob }" value="{key}" class={ hide : robot.class !== select_class }>{ robot.corp } {robot.name}</option>
						</select>
					</td>
					<td><input type="number" value="1" min="1" max="10" class="form-control form-control-sm" onchange="{ changeGrade }" id="arm_grade"></td>
					<td>{ +robot.arm.armor+ addition.arm.armor }</td>
					<td>{ +robot.arm.heat+ addition.arm.heat }</td>
					<td>{ +robot.arm.weight- addition.arm.weight }</td>
				</tr>
				<tr>
					<th scope="row">脚</th>
					<td>
						<select onchange="{ changeParts }" id="leg" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, robot in data_rob }" value="{key}" class={ hide : robot.class !== select_class }>{ robot.corp } {robot.name}</option>
						</select>
					</td>
					<td><input type="number" value="1" min="1" max="10" class="form-control form-control-sm" onchange="{ changeGrade }" id="leg_grade"></td>
					<td>{ +robot.leg.armor+ addition.leg.armor }</td>
					<td>{ +robot.leg.heat+ addition.leg.heat }</td>
					<td>{ +robot.leg.weight- addition.leg.weight }</td>
				</tr>
				<tr>
					<th scope="row">メイン</th>
					<td>
						<select onchange="{ changeCategory }" id="main_weapon_cat" class="form-control form-control-sm">
							<option selected value="unselect">**********</option>
							<option value="アサルトライフル" class="{ hide : !canIuse('アサルトライフル') }">アサルトライフル</option>
							<option value="ショットガン" class="{ hide : !canIuse('ショットガン') }" >ショットガン</option>
							<option value="ロケットランチャー" class="{ hide : !canIuse('ロケットランチャー') }">ロケットランチャー</option>
							<option value="ガトリングガン" class="{ hide : !canIuse('ガトリングガン') }">ガトリングガン</option>
							<option value="スナイパーライフル" class="{ hide : !canIuse('スナイパーライフル') }">スナイパーライフル</option>
							<option value="キャノン砲" class="{ hide : !canIuse('キャノン砲') }">キャノン砲</option>
							<option value="ホーミングミサイル" class="{ hide : !canIuse('ホーミングミサイル') }">ホーミングミサイル</option>
							<option value="ハンドガン" class="{ hide : !canIuse('ハンドガン') }">ハンドガン</option>
							<option value="サブマシンガン" class="{ hide : !canIuse('サブマシンガン') }">サブマシンガン</option>
							<option value="レールガン" class="{ hide : !canIuse('レールガン') }">レールガン</option>
						</select>

						<select onchange="{ changeWeapon }" id="main" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, weapon in data_wep }" value="{key}" class="{ hide : select_main_weapon_category !== weapon.category }">{ weapon.corp } {weapon.name}</option>
						</select>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td>{ robot.main.weight.min }</td>
				</tr>
				<tr>
					<th scope="row">サブ</th>
					<td>
						<select onchange="{ changeCategory }" id="sub_weapon_cat" class="form-control form-control-sm">
							<option selected value="unselect">**********</option>
							<option value="アサルトライフル" class="{ hide : !canIuse('アサルトライフル') }">アサルトライフル</option>
							<option value="ショットガン" class="{ hide : !canIuse('ショットガン') }" >ショットガン</option>
							<option value="ロケットランチャー" class="{ hide : !canIuse('ロケットランチャー') }">ロケットランチャー</option>
							<option value="ガトリングガン" class="{ hide : !canIuse('ガトリングガン') }">ガトリングガン</option>
							<option value="スナイパーライフル" class="{ hide : !canIuse('スナイパーライフル') }">スナイパーライフル</option>
							<option value="キャノン砲" class="{ hide : !canIuse('キャノン砲') }">キャノン砲</option>
							<option value="ホーミングミサイル" class="{ hide : !canIuse('ホーミングミサイル') }">ホーミングミサイル</option>
							<option value="ハンドガン" class="{ hide : !canIuse('ハンドガン') }">ハンドガン</option>
							<option value="サブマシンガン" class="{ hide : !canIuse('サブマシンガン') }">サブマシンガン</option>
							<option value="レールガン" class="{ hide : !canIuse('レールガン') }">レールガン</option>
						</select>
						<select onchange="{ changeWeapon }" id="sub" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, weapon in data_wep }" value="{key}" class="{ hide : select_sub_weapon_category !== weapon.category }">{ weapon.corp } {weapon.name}</option>
						</select>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td>{ robot.sub.weight.min }</td>
				</tr>
				<tr>
					<th scope="row">近接</th>
					<td>
						<select onchange="{ changeWeapon }" id="close_weapon" class="form-control form-control-sm">
							<option selected value="unequip">**********</option>
							<option each="{ key, weapon in data_close_wep }" value="{key}">{ weapon.corp } {weapon.name}</option>
						</select>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td>{ robot.close_weapon.weight }</td>
				</tr>
				<tr>
					<th scope="row">総重量</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>{ combinedWeight() }({ weightBonus() })</td>
				</tr>
			</tbody>
		</table>

		<footer class="footer">
			<p>&copy; <a href="http://twitter.com/og24715">@og24715</a></p>
		</footer>

	</div> <!-- /container -->


	<script>
		// armor
		fetch( 'main.php?r=armor' )
		.then( data => data.json() )
		.then( json => {
			this.data_rob = json
			this.update()
		} )
		// weapon
		fetch( 'main.php?r=weapon' )
		.then( data => data.json() )
		.then( json => {
			this.data_wep = json
			this.update()
		} )
		// close weapon
		fetch( 'data/new/close_weapon.json' )
		.then( data => data.json() )
		.then( json => {
			this.data_close_wep = json
			this.update()
		} )

		

		this.robot = {
			head : { armor:0,heat:0,weight:0, },
			body : { armor:0,heat:0,weight:0, },
			arm  : { armor:0,heat:0,weight:0, },
			leg  : { armor:0,heat:0,weight:0, },
		}

		changeParts( e ) {
			rob_id = e.target.value;
			part = e.target.id;	
			this.robot[part] = this.data_rob[rob_id].param[part]
			this.parts_desc = decodeURI(this.data_rob[rob_id].desc).replace(/<("[^"]*"|'[^']*'|[^'">])*>/g,'')
		}

		changeCategory( e ) {
			part = e.target.id
			category = e.target.value;

			switch( part ){
				case 'main_weapon_cat' :
					this.select_main_weapon_category = category
					break;
				case 'sub_weapon_cat' :
					this.select_sub_weapon_category = category
					break;
			}
		}

		changeWeapon( e ) {
			wep_id = e.target.value;
			part = e.target.id;
			switch ( part ) {
				case "main":
				case "sub":
					this.robot[part] = this.data_wep[wep_id].param
					break;
				case "close_weapon" :
					this.robot[part] = this.data_close_wep[wep_id].param
					break;
			}
		}

		canIuse( cat ) {

			equip = {
				'アサルトライフル' : ['as','sp'],
				'ショットガン' : ['as','sp'],
				'ロケットランチャー' : ['as'],
				'ガトリングガン' : ['hv'],
				'スナイパーライフル' : ['sn'],
				'キャノン砲' : ['st'],
				'ホーミングミサイル' : ['hv'],
				'ハンドガン' : ['as','sp','hv','st','sn','en'],
				'サブマシンガン' : ['as','sp','hv','st','sn','en'],
				'レールガン' : ['en'],
			}

			return equip[cat].indexOf(this.select_branch) >= 0 ? true : false
		}

		changeClass( e ) {
			this.select_branch = e.target.value
			switch ( this.select_branch ) {
				case 'as':
				case 'sp':
					this.select_class = 'middle';			
					break
				case 'sn':
				case 'en':
					this.select_class = 'light';
					break
				case 'hv':
				case 'st':
					this.select_class = 'heavy';
					break
				case 'noselect':
					this.select_class = null;
					break;
			}
		}
		combinedWeight() {
			sum = 0
			for ( key in this.robot ) {
				if( key === 'main'  || key === 'sub' ) {
					sum += +this.robot[key].weight.min
				} else {
					sum += +this.robot[key].weight
				}
			}
			for ( key in addition ) {
				sum -= addition[key].weight
			}
			this.combined_weight = sum
			return sum
		}

		weightBonus() {
			switch (this.select_class) {
				case 'light':
					wb = Math.floor( (1500 - this.combined_weight)/50 )
					break
				case 'middle':
					wb = Math.floor( (2000 - this.combined_weight)/50 )
					break
				case 'heavy':
					wb = Math.floor( (4000 - this.combined_weight)/100 )
					break
			}
			this.weight_bonus = (wb > 10 ? 10 : (wb < -3 ? -3 : wb) )
			return this.weight_bonus
		}

		grade_map = {
			'armor'  : [ 0, 3, 3, 3, 3, 2, 2, 2, 2, 2 ],
			'heat'   : [ 0, 1, 1, 1, 1, 0, 1, 0, 1, 0 ],
			'weight' : {
				'head'	: [ 0, 5, 5, 5, 5, 0, 0, 0, 0, 0 ],
				'body'	: [ 0, 8, 7, 8, 7, 0, 0, 0, 0, 0 ],
				'arm'	: [ 0, 7, 6, 6, 6, 0, 0, 0, 0, 0 ],
				'leg'	: [ 0, 7, 6, 6, 6, 0, 0, 0, 0, 0 ],
			} 
		}
		addition = {
			head : { armor : 0, heat : 0, weight : 0 },
			body : { armor : 0, heat : 0, weight : 0 },
			arm  : { armor : 0, heat : 0, weight : 0 },
			leg  : { armor : 0, heat : 0, weight : 0 },
		}

		changeGrade( e ) {
			grade = e.target.value
			part = e.target.id

			console.log( grade_map.armor.slice(0, grade).reduce((p, c)=> p+c) )

			switch( part ) {
				case 'head_grade' :
					addition.head.armor = grade_map.armor.slice(0, grade).reduce((p, c)=> p+c)
					addition.head.heat = grade_map.heat.slice(0, grade).reduce((p, c)=> p+c)
					addition.head.weight = grade_map.weight.head.slice(0, grade).reduce((p, c)=> p+c)
					break
				case 'body_grade' :
					addition.body.armor = grade_map.armor.slice(0, grade).reduce((p, c)=> p+c)
					addition.body.heat = grade_map.heat.slice(0, grade).reduce((p, c)=> p+c)
					addition.body.weight = grade_map.weight.body.slice(0, grade).reduce((p, c)=> p+c)
					break
				case 'arm_grade' :
					addition.arm.armor = grade_map.armor.slice(0, grade).reduce((p, c)=> p+c)
					addition.arm.heat = grade_map.heat.slice(0, grade).reduce((p, c)=> p+c)
					addition.arm.weight = grade_map.weight.arm.slice(0, grade).reduce((p, c)=> p+c)
					break
				case 'leg_grade' :
					addition.leg.armor = grade_map.armor.slice(0, grade).reduce((p, c)=> p+c)
					addition.leg.heat = grade_map.heat.slice(0, grade).reduce((p, c)=> p+c)
					addition.leg.weight = grade_map.weight.leg.slice(0, grade).reduce((p, c)=> p+c)
					break
			}

			console.log( addition ) 
		}

	</script>

	<style>
		.hide {
			display: none;
		}
	</style>

</test>