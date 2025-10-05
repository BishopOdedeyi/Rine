<?php
header('Content-Type: application/json');
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Collect and validate input
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$total_prize = floatval($_POST['total_prize'] ?? 0);
$number_of_winners = intval($_POST['number_of_winners'] ?? 1);
$distribution = $_POST['distribution'] ?? 'equal';
$end_at = $_POST['end_at'] ?? '';

if (!$title || !$description || !$total_prize || !$end_at) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO giveaways (host_user_id, title, description, total_prize, number_of_winners, distribution, start_at, end_at, status)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, 'active')
    ");
    $stmt->execute([$user_id, $title, $description, $total_prize, $number_of_winners, $distribution, $end_at]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
