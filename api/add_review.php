<?php
header('Content-Type: application/json');
require_once 'db.php';

// Anonymous allowed, but require a valid giveaway_id
$giveaway_id = intval($_POST['giveaway_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$review_text = trim($_POST['review_text'] ?? '');

if ($giveaway_id <= 0 || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'error' => 'Invalid review data.']);
    exit;
}

// Check giveaway exists and is closed/archived
$stmt = $pdo->prepare("SELECT id FROM giveaways WHERE id = ? AND status IN ('closed','archived')");
$stmt->execute([$giveaway_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'error' => 'Giveaway not found or still active.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO giveaway_reviews (giveaway_id, rating, review_text) VALUES (?, ?, ?)");
    $stmt->execute([$giveaway_id, $rating, $review_text]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
