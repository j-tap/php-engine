<?php include ROOT.'/views/layouts/header.php'; ?>

<section id="form">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<?php if ($result): ?>
					<p>Данные отредактированы</p>
				<?php else: ?>
					<?php if (isset($errors) && is_array($errors)): ?>
						<ul>
							<?php foreach ($errors as $errors): ?>
								<li> - <?php echo $errors; ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<div class="login-form"><!--login form-->
						<h2>Редактирование</h2>
						<form action="#" method="post">
							<input placeholder="Имя" value="<?php echo $name; ?>" name="name" type="text" />
							<input placeholder="Пароль" value="<?php echo $password; ?>" name="password" type="password" />
							<input class="btn btn-default" value="Сохранить" name="submit" type="submit" />
						</form>
					</div><!--/login form-->
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php include ROOT.'/views/layouts/footer.php'; ?>