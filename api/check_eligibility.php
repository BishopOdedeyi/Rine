<?php
// check_eligibility.php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if(!$data || empty($data['participant_id'])){
  http_response_code(400);
  echo json_encode(['error'=>'Missing participant_id']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    UPDATE participants p
    SET eligible = (
      SELECT CASE WHEN COUNT(*) = SUM(done) THEN 1 ELSE 0 END
      FROM participant_tasks pt
      WHERE pt.participant_id = p.id
    )
    WHERE p.id = ?
  ");
  $stmt->execute([$data['participant_id']]);

  echo json_encode(['success'=>true]);

} catch(Exception $e){
  http_response_code(500);
  echo json_encode(['error'=>$e->getMessage()]);
}
