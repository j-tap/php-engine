<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="mb-30"></div>
			<h2 class="h2 text-center mb-30">Авторизация</h2>
			<form action="" method="post">
				<div class="form-group">
					<input class="form-control" placeholder="E-mail" value="<?php echo $aData['email']; ?>" name="email" type="text">
				</div>
				<div class="form-group">
					<input class="form-control" placeholder="Пароль" value="<?php echo $aData['password']; ?>" name="password" type="password">
				</div>
				<input class="btn btn-default btn-block" value="Войти" name="submit" type="submit" />
			</form>
			<p class="text-info"><?php echo $aRes['msg']; ?></p>
			<?php if ($err != false): ?>
				<ul class="list-unstyled">
					<?php foreach ($err as $error): ?>
						<li><span class="text-warning"><?php echo $error; ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="mb-30"></div>
			<p class="text-center">
				<a class="" href="/register/">Зарегистрироваться</a>
			</p>
		</div>
	</div>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>