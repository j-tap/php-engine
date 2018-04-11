<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="container">
	<div class="panel panel-filled">
		<div class="panel-body">
			<? if (count($allUsers)) : ?>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th><?=$GLOBALS['locale']['name']?></th>
								<th><?=$GLOBALS['locale']['date']?></th>
								<th><?=$GLOBALS['locale']['authorized']?></th>
								<th><?=$GLOBALS['locale']['confirmed']?></th>
								<th><?=$GLOBALS['locale']['online']?></th>
							</tr>
						</thead>
						<tbody>
							<? foreach ($allUsers as $item) {

								if ($item['active']) $active = $GLOBALS['locale']['yes'];
								else $active = '<span class="text-danger">'.$GLOBALS['locale']['no'].'</span>';

								if ($item['auth']) $auth = $GLOBALS['locale']['yes'];
								else $auth = $GLOBALS['locale']['no'];

								$date = ExtraMethods::getDateFormat($item['date']);

								$visit = strtotime($item['visit']);
								$now = time();
								$diff = $now - $visit;
								$time = 60 * 5;
								if ($diff < $time) $online = ' text-success';
								else $online = '';
							?>
								<tr>
									<td><b><?=$item['name'];?></b></td>
									<td><?=$date['d'].' '.$date['t'];?></td>
									<td><?=$auth;?></td>
									<td><?=$active;?></td>
									<td><i class="fa fa-plug<?=$online?>" aria-hidden="true"></i></td>
								</tr>
							<? }; ?>
						</tbody>
					</table>
				</div>
			<? else: ?>
				<p>Пользователей нет</p>
			<? endif; ?>
		</div>
	</div>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>