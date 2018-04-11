<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-md-4 col-sm-6">
			<div class="panel panel-filled">
				<div class="panel-body">
					<h4 class="m-b-xs text-muted"><i class="pe pe-7s-id m-r-xs"></i> <?=$GLOBALS['locale']['editUserData']?></h4>
					<hr>
					<form role="form" action="" method="post" id="formSettingUser">
						<input type="hidden" name="changesetting">
						<?/*<div class="form-group">
							<label for="inputEmail" class="control-label">E-mail</label>
							<input type="text" class="form-control" id="inputEmail" name="email"  value="<?=$user['email']?>">
						</div>*/?>
						<div class="form-group">
							<label for="inputPassword" class="control-label">Новый пароль</label>
							<input type="password" class="form-control" id="inputPassword" name="password"  value="">
						</div>
						<div class="form-group">
							<label for="inputPhoto" class="control-label">Новый аватар</label>
							<div class="form-control form-control-file">
								<button type="button" class="btn btn-default">Выбрать</button>
								<span></span>
								<input type="file" class="" id="inputPhoto" name="file">
							</div>
						</div>
						<div class="text-right">
							<button type="submit" class="btn btn-primary"><?=$GLOBALS['locale']['save']?></button>
						</div>
					</form>
				</div>
				<div class="panel-footer"><?=$GLOBALS['locale']['personalUserData']?></div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6">
			<div class="panel panel-filled">
				<div class="panel-body">
					<h4 class="<?=($user['active'])?'m-b-xs':'m-b-md';?> text-muted"> <i class="pe pe-7s-graph1 m-r-xs"></i> <?=$GLOBALS['locale']['stat']?></h4>
					<?
						$date = ExtraMethods::getDateFormat($user['date']);

						$inviteActive = array();
						
						if ($user['active']) :
							if (count($inviteList)) {
								foreach ($inviteList as $item) {
									if ($item['user_invited']) 
										array_push($inviteActive, array_shift($inviteList));
									else continue;
								}
							}	
						?>
						<hr class="">
						<div class="row">
							<div class="col-sm-4 col-xs-6">
								<div class="panel panel-filled">
									<div class="panel-body">
										<h2 class="m-b-none m-t-none">10</h2>
										<div class="slight m-t-sm"><i class="fa fa-angle-up"></i> Уровень</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-6">
								<div class="panel panel-filled">
									<div class="panel-body">
										<h2 class="m-b-none m-t-none">1237</h2>
										<div class="slight m-t-sm"><i class="fa fa-star-o"></i> Рейтинг</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-6">
								<div class="panel panel-filled">
									<div class="panel-body">
										<h2 class="m-b-none m-t-none">206</h2>
										<div class="slight m-t-sm"><i class="fa fa-clock-o"></i> в игре</div>
									</div>
								</div>
							</div>
						</div>
					<? endif; ?>
					<table class="table table-condensed">
						<tbody>
							<tr>
								<td>Никнейм</td>
								<td class="text-right"><?=$user['name'];?></td>
							</tr>
							<tr>
								<td>Зарегистрирован</td>
								<td class="text-right"><?=$date['d'].' '.$date['t'];?></td>
							</tr>
							<tr>
								<td>Аккаунт</td>
								<td class="text-right"><?=($user['active'])?'активирован':'<span class="text-danger">не активирован</span>';?></td>
							</tr>
							<tr>
								<td>Приглашено</td>
								<td class="text-right"><?=count($inviteActive);?></td>
							</tr>
							<tr>
								<td>Баланс</td>
								<td class="text-right">0</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="panel-footer"><?=$GLOBALS['locale']['statUser']?></div>
			</div>
		</div>
		<? if ($user['active']) :?>
			<div class="col-md-4 col-sm-6">
				<div class="panel panel-filled">
					<div class="panel-body" id="generateInvite">
						<h4 class="m-b-xs text-muted"> <i class="pe pe-7s-link m-r-xs"></i> <?=$GLOBALS['locale']['inviteFriend']?></h4>
						<hr>
						<div class="form-group text-right">
							<button class="btn btn-success"><?=$GLOBALS['locale']['generate']?></button>
						</div>
						<? if (count($inviteList)):?>
							<? foreach ($inviteList as $item):?>
								<div class="form-group">
									<input class="form-control" type="text" readonly  placeholder="секретный ключ" value="<?=HOST.'/register/?invite='.$item['code'];?>">
								</div>
							<? endforeach; ?>
						<? else: ?>
							<div class="form-group">
								<input class="form-control" type="text" readonly placeholder="секретный ключ" value="">
							</div>
						<? endif; ?>
					</div>
					<div class="panel-footer"><?=$GLOBALS['locale']['generatePrivateLink']?></div>
				</div>
			</div>
		<? endif; ?>
	</div>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>