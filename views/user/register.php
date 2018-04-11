<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="mb-30"></div>
			<?php if ($aRes['status']): ?>
				<h1 class="h2 text-center mb-30">Вы зарегистрированы</h1>
			<?php else: ?>
				<h1 class="h2 text-center mb-30">Регистрация на сайте</h1>
				<form class="mb-10" action="" method="post">
					<div class="form-group">
						<input class="form-control" placeholder="Имя" value="<?php echo $aData['name']; ?>" type="text" name="name">
					</div>
					<div class="form-group">
						<input class="form-control" placeholder="E-mail" value="<?php echo $aData['email']; ?>" type="text" name="email">
					</div>
					<div class="form-group">
						<input class="form-control" placeholder="Пароль" value="<?php echo $aData['password']; ?>" type="password" name="password">
					</div>
					<input class="btn btn-default btn-block" value="Регистрация" type="submit"  name="submit">
				</form>
				<p class="text-info"><?php echo $aRes['msg']; ?></p>
				<?php if ($err != false): ?>
					<ul class="list-unstyled">
						<?php foreach ($err as $error): ?>
							<li><span class="text-warning"><?php echo $error; ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			<?php endif; ?>
			<div class="mb-30"></div>
			<p class="text-center">
				<a class="" href="/login/">Авторизоваться</a>
			</p>
		</div>
	</div>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>