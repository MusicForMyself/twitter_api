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
		<div class="container">
			<form action="">
				<label>#<input onkeyup="searchProjects();" type="text" placeholder="Buscar en Twitter" name="hashtag" id="hashtag"></label>
				<!-- <select name="city" id="city_select">
					<option value="">Seleccionar ciudad</option>
				</select> -->
			</form>
			<a href="#" id="downloadCSV" class="download_csv">Descargar</a>
			<section id="twitContainer" class="twit_container">
				
			</section>
		</div><!-- container -->
	</body>
</html>