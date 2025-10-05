<?php
// run_draw.php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if(!$data || empty($data['giveaway_id'])){
  http_response_code(400);
  echo json_encode(['error'=>'Missing giveaway_id']);
  exit;
}

try {
  $pdo->beginTransaction();

  $g = $pdo->prepare("SELECT * FROM giveaways WHERE id = ?");
  $g->execute([$data['giveaway_id']]);
  $giveaway = $g->fetch();
  if(!$giveaway){ throw new Exception("Giveaway not found"); }

  $q = $pdo->prepare("
    SELECT p.id AS participant_id, u.name
    FROM participants p
    JOIN users u ON u.id = p.user_id
    WHERE p.giveaway_id = ? AND p.eligible = 1
  ");
  $q->execute([$data['giveaway_id']]);
  $eligible = $q->fetchAll();

  if(!$eligible){
    echo json_encode(['error'=>'No eligible participants']);
    exit;
  }

  $numWinners = min($giveaway['number_of_winners'], count($eligible));
  shuffle($eligible);
  $winners = array_slice($eligible, 0, $numWinners);

  $amountEach = $numWinners > 0 ? round($giveaway['total_prize'] / $numWinners, 2) : 0;

  $ins = $pdo->prepare("INSERT INTO winners (giveaway_id, participant_id, amount) VALUES (?, ?, ?)");
  foreach($winners as $w){
    $ins->execute([$data['giveaway_id'], $w['participant_id'], $amountEach]);
  }

  $pdo->prepare("UPDATE giveaways SET drawn_at = NOW() WHERE id = ?")->execute([$data['giveaway_id']]);

  $pdo->commit();
  echo json_encode(['success'=>true, 'winners'=>$winners, 'amount_each'=>$amountEach]);

} catch(Exception $e){
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['error'=>$e->getMessage()]);
}
