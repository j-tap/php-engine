<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Engine</title>
	<link href="/favicon.ico" rel="icon" type="image/x-icon">
	<link href="/favicon.ico" rel="shortcut icon">
	
	<link rel="stylesheet" href="/template/css/bootstrap-cosmo.css">
	<link rel="stylesheet" href="/template/css/main.css"> 
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]--> 
</head>
<body>
<?php if (User::getAuth()) : ?>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="">CallCenter</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/">Главная</a></li>
				</ul>
				<ul class="nav navbar-nav  navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user['name']; ?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="/profile/">Кабинет</a></li>
							<li><a href="/users/">Пользователи</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="/logout/">Выйти</a></li>
						</ul>
					</li>
				</ul>
				<div class="navbar-right">
					<div class="navbar-text">
						<span class="text-muted"><?php echo $user['group']['name']; ?></span>
					</div>
				</div>
			</div>
		</div>
	</nav>
<?php endif; ?>
	