<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>mysql-voting-system</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>mysql-voting-system</h1>
	<p>A PHP voting system using MySQL.</p>

	<hr>

	<?php
	include('php/db.php');

	$STH = $DBH->query('SELECT count(*) AS items FROM votingSystem_items');
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$items = $STH->fetch();

	for($i = 1; $i <= $items->items; $i++):
		$STH = $DBH->query("SELECT SUM(vote_up) AS vote_up, SUM(vote_down) AS vote_down FROM votingSystem_votes WHERE item_id = $i");
		$STH->setFetchMode(PDO::FETCH_OBJ);
		$votes = $STH->fetch();
	?>
		<div class="item" id="item-<?= $i; ?>">
			<span class="item-title">Item <?= $i; ?></span>

			<div class="vote-up">vote up</div>
			<div class="vote-down">vote down</div>

			<div class="vote-bar">
				<div class="vote-bar-up"><?= $votes->vote_up; ?></div>
				<div class="vote-bar-down"><?= $votes->vote_down; ?></div>
			</div>
		</div>
	<?php endfor; ?>

	<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>

	<script>
		$(document).ready(function() {
			$('.item').each(function() {
				var $voteBarUp     = $(this).find('.vote-bar-up');
				var $voteBarDown   = $(this).find('.vote-bar-down');
				var vote_up        = +$voteBarUp.text();
				var vote_down      = +$voteBarDown.text();
				var vote_total     = vote_up + vote_down;
				var vote_up_perc   = vote_up / vote_total * 100;
				var vote_down_perc = vote_down / vote_total * 100;
				$voteBarUp.width(vote_up_perc + '%');
				$voteBarDown.width(vote_down_perc + '%');
			});
		});

		$('.item').click(function(e) {
			var vote = e.target.className;

			if(vote == 'vote-up' || vote == 'vote-down') {
				$.ajax({
					type: 'GET',
					url: 'php/vote.php',
					data: 'item=' + e.currentTarget.id.match(/\d+$/)[0] + "&vote=" + vote,
					success: function() {
						alert('Vote successful. Please refresh.');
					}
				});
			}
		});
	</script>
</body>
</html>