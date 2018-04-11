<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="container">
	<div class="panel">
		<div class="panel-body">
			<? if (count($newsList)) : ?>
				<div class="v-timeline vertical-container">
					<? foreach ($newsList as $newsItem): ?>
						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon">
								<i class="fa fa-calendar"></i>
							</div>
							<div class="vertical-timeline-content">
								<div class="p-sm">
									<? $date = ExtraMethods::getDateFormat($newsItem['date']); ?>
									<time class="vertical-date pull-right"><?=$date['d'];?><br><small><?=$date['t'];?></small></time>
									<h2><?=$newsItem['title']?></h2>
									<p><?=$newsItem['preview']?></p>
								</div>
								<div class="panel-footer"><a href="/news/<?=$newsItem['id']?>"><?=$GLOBALS['locale']['more']?></a></div>
							</div>
						</div>
						<?/*<h2 class="h4"><?=$newsItem['title']?></h2>
						<p><time><?=$sDate?></time></p>
						<a href="/news/<?=$newsItem['id']?>">Узнать больше</a>*/?>
					<? endforeach; ?>
				</div>
			<? else: ?>
				<p><?=$GLOBALS['locale']['notNews']?></p>
			<? endif; ?>
		</div>
	</div>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>