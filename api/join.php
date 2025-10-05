<?php
// join.php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if(!$data || empty($data['giveaway_id']) || empty($data['user_id'])){
  http_response_code(400);
  echo json_encode(['error'=>'Missing giveaway_id or user_id']);
  exit;
}

try {
  $pdo->beginTransaction();

  // Check if already joined
  $chk = $pdo->prepare("SELECT id FROM participants WHERE giveaway_id = ? AND user_id = ?");
  $chk->execute([$data['giveaway_id'], $data['user_id']]);
  if($chk->fetch()){
    echo json_encode(['message'=>'Already joined']);
    exit;
  }

  // Add participant
  $p = $pdo->prepare("INSERT INTO participants (giveaway_id, user_id) VALUES (?, ?)");
  $p->execute([$data['giveaway_id'], $data['user_id']]);
  $participant_id = $pdo->lastInsertId();

  // Assign tasks to participant
  $tasks = $pdo->prepare("SELECT id FROM tasks WHERE giveaway_id = ?");
  $tasks->execute([$data['giveaway_id']]);
  $rows = $tasks->fetchAll();
  $pt = $pdo->prepare("INSERT INTO participant_tasks (participant_id, task_id) VALUES (?, ?)");
  foreach($rows as $t){
    $pt->execute([$participant_id, $t['id']]);
  }

  $pdo->commit();
  echo json_encode(['success'=>true, 'participant_id'=>$participant_id]);

} catch(Exception $e){
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['error'=>$e->getMessage()]);
}
