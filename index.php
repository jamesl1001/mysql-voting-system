<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>mysql-voting-system</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
</head>
<body>
	<h1>mysql-voting-system</h1>
	<p>A PHP voting system using MySQL.</p>

	<hr>

	<?php
		include("db.php");

		$STH = $DBH->query('SELECT id, item_id, vote_up, vote_down FROM votingSystem_votes');
		$STH->setFetchMode(PDO::FETCH_OBJ);

		while($row = $STH->fetch()): ?>
			<p><?= $row->id; ?> | <?= $row->item_id; ?> | <?= $row->vote_up; ?> | <?= $row->vote_down; ?></p>
	<?php endwhile;
		// Get number of items
		$STH = $DBH->query('SELECT count(*) AS items FROM votingSystem_items');
		$STH->setFetchMode(PDO::FETCH_OBJ);
		$items = $STH->fetch();

		echo "Items: " . $items->items . "<br><br>";

		// $STH2 = $DBH->query('SELECT votingSystem_votes.vote from votingSystem_votes INNER JOIN votingSystem_items ON votingSystem_votes.item_id = votingSystem_items.id');
		
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
			console.log(e.currentTarget);
		});
	</script>
</body>
</html>