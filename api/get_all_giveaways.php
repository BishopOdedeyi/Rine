<?php
header('Content-Type: application/json');
require_once 'db.php'; // adjust path if needed

try {
    // Query giveaways with optional host name
    $stmt = $pdo->query("
        SELECT 
            g.id, 
            g.title, 
            g.description,
            g.total_prize,
            g.number_of_winners,
            g.created_at,
            g.status,
            u.name AS host_name
        FROM giveaways g
        LEFT JOIN users u ON g.host_user_id = u.id
        WHERE g.status = 'active'
        ORDER BY g.created_at DESC
    ");
    
    $giveaways = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'count' => count($giveaways),
        'giveaways' => $giveaways
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
