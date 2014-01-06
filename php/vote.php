<?php
include('db.php');

$item     = $_GET['item'];
$vote     = $_GET['vote'];
$voteUp   = ($vote == 'vote-up')   ? 1 : 0;
$voteDown = ($vote == 'vote-down') ? 1 : 0;

$STH = $DBH->prepare("INSERT INTO votingSystem_votes (item_id, vote_up, vote_down) value (:item_id, :vote_up, :vote_down)");
$STH->bindParam(':item_id', $item);
$STH->bindParam(':vote_up', $voteUp);
$STH->bindParam(':vote_down', $voteDown);
$STH->execute();