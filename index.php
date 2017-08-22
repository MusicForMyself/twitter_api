<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Twitter search | John Falcon</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="assets/css/style.css">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="assets/js/functions.js"  type="text/javascript" ></script>
	</head>
	<body>
		<div class="container twitter_search">
			<h2>Introduce un hashtag para comenzar la búsqueda</h2>
			<form action="">
				<label><input onkeyup="searchProjects();" type="text" placeholder="Búsqueda por hashtag" name="hashtag" id="hashtag"></label>
				<!-- <select name="city" id="city_select">
					<option value="">Seleccionar ciudad</option>
				</select> -->
			</form>
			<a href="#" id="downloadCSV" data-search="" class="buttonlike">Descargar</a>
			<div class="clearfix"></div>
			<section id="twitContainer" class="twit_container">
				<p>No hay resultados que mostrar</p>
			</section>
		</div><!-- container -->
	</body>
</html>