<?php
// view_giveaway.php
header('Content-Type: application/json');
require_once 'db.php';

// Get query params: giveaway_id and (optional) user_id
$giveaway_id = isset($_GET['giveaway_id']) ? intval($_GET['giveaway_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($giveaway_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid giveaway_id']);
    exit;
}

try {
    // Get giveaway info
    $stmt = $pdo->prepare("SELECT * FROM giveaways WHERE id = ?");
    $stmt->execute([$giveaway_id]);
    $giveaway = $stmt->fetch();
    if (!$giveaway) {
        http_response_code(404);
        echo json_encode(['error' => 'Giveaway not found']);
        exit;
    }

    // Get tasks
    $tasksStmt = $pdo->prepare("SELECT id, title, description FROM tasks WHERE giveaway_id = ?");
    $tasksStmt->execute([$giveaway_id]);
    $tasks = $tasksStmt->fetchAll();

    $participant = null;
    $progress = null;

    if ($user_id > 0) {
        // Check if user joined
        $pStmt = $pdo->prepare("SELECT * FROM participants WHERE giveaway_id = ? AND user_id = ?");
        $pStmt->execute([$giveaway_id, $user_id]);
        $participant = $pStmt->fetch();

        if ($participant) {
            // Get participant tasks and progress
            $ptStmt = $pdo->prepare("
                SELECT t.id, t.title, pt.done
                FROM participant_tasks pt
                JOIN tasks t ON pt.task_id = t.id
                WHERE pt.participant_id = ?
            ");
            $ptStmt->execute([$participant['id']]);
            $userTasks = $ptStmt->fetchAll();

            $total = count($userTasks);
            $done = 0;
            foreach ($userTasks as $t) {
                if ($t['done']) $done++;
            }

            $progress = [
                'total_tasks' => $total,
                'tasks_done' => $done,
                'percent' => $total ? round(($done / $total) * 100, 2) : 0
            ];
        }
    }

    echo json_encode([
        'giveaway' => $giveaway,
        'tasks' => $tasks,
        'participant' => $participant,
        'progress' => $progress
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
