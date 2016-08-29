<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">

</head>
<body>

	<div class="container">
		<div class="row">
			<test></test>
		</div>
	</div>
	
	<script type="riot/tag" src="test.tag"></script>
	<!-- include riot.js -->
	<script src="https://cdn.jsdelivr.net/riot/2.5/riot+compiler.min.js"></script>
	<!-- mount the tag -->
	<script>riot.mount('*')</script>
</body>
</html>