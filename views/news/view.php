<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="container">
	<? $date = ExtraMethods::getDateFormat($newsItem['date']); ?>
	<article class="article">
		<header>
			<h1 class="h3"><?=$newsItem['title']?></h1>
			<time><?=$date['d'];?></time>
		</header>
		<p><?=$newsItem['text']?></p>
		<aside>
			<a href="/news/">Другие новости</a>
		</aside>
	</article>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>