<?php include ROOT.'/views/layouts/header.php'; ?>

<section id="form">
	<div class="container">
		<h1>Кабинет пользователя</h1>
		<h3>Привет, <?php echo $user['name']; ?>!</h3>
		<nav>
			<ul>
				<li><a href="/cabinet/edit/">Редактирование данных</a></li>
				<li><a href="#">Список покупок</a></li>
			</ul>
		</nav>
	</div>
</section>

<?php include ROOT.'/views/layouts/footer.php'; ?>