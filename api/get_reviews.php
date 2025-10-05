<?php
header('Content-Type: application/json');
require_once 'db.php';

$giveaway_id = intval($_GET['giveaway_id'] ?? 0);

if ($giveaway_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid giveaway ID']);
    exit;
}

$stmt = $pdo->prepare("SELECT rating, review_text, created_at FROM giveaway_reviews WHERE giveaway_id = ? ORDER BY created_at DESC");
$stmt->execute([$giveaway_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'count' => count($reviews),
    'reviews' => $reviews
]);
