<?php
session_start();
require_once 'api/db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT id, name, email, contact_link, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user's giveaways
$giveaways_stmt = $pdo->prepare("
    SELECT id, title, total_prize, number_of_winners, status, created_at, end_at
    FROM giveaways
    WHERE host_user_id = ?
    ORDER BY created_at DESC
");
$giveaways_stmt->execute([$user_id]);
$giveaways = $giveaways_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($user['name']) ?> - Profile | Giveaway Hub</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: #f0f3f7;
  margin: 0;
  padding: 0;
}
.header {
  background: #007bff;
  color: white;
  padding: 15px 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.header h1 {
  font-size: 22px;
}
.header a {
  color: white;
  text-decoration: none;
  background: rgba(255,255,255,0.2);
  padding: 6px 12px;
  border-radius: 6px;
  transition: 0.3s;
}
.header a:hover {
  background: rgba(255,255,255,0.4);
}
.container {
  max-width: 1000px;
  margin: 40px auto;
  background: white;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
h2 {
  margin-top: 0;
}
.profile-info {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 30px;
}
.info-card {
  background: #f8f9fb;
  border-radius: 12px;
  padding: 15px;
}
.giveaway-list {
  margin-top: 20px;
}
.giveaway-card {
  border: 1px solid #eee;
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 15px;
  transition: 0.3s;
}
.giveaway-card:hover {
  background: #f9fbff;
}
.status {
  font-weight: 500;
  border-radius: 6px;
  padding: 4px 8px;
}
.status.active {
  background: #d4f8d4;
  color: #046604;
}
.status.closed {
  background: #ffe3e3;
  color: #a30d0d;
}
.status.archived {
  background: #e9ecef;
  color: #495057;
}
.btn-row {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}
.btn {
  text-decoration: none;
  background: #007bff;
  color: white;
  padding: 10px 14px;
  border-radius: 8px;
  transition: 0.3s;
}
.btn:hover {
  background: #0056b3;
}
.btn-secondary {
  background: #6c757d;
}
.btn-secondary:hover {
  background: #565e64;
}
</style>
</head>
<body>

<div class="header">
  <h1>🎉 Giveaway Hub</h1>
  <div>
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="container">
  <h2>👤 Welcome, <?= htmlspecialchars($user['name']) ?></h2>

  <div class="profile-info">
    <div class="info-card">
      <strong>Email:</strong><br>
      <?= htmlspecialchars($user['email']) ?><br><br>
      <strong>Contact Link:</strong><br>
      <?= $user['contact_link'] ? "<a href='".htmlspecialchars($user['contact_link'])."' target='_blank'>".htmlspecialchars($user['contact_link'])."</a>" : "<em>Not provided</em>" ?><br><br>
      <strong>Joined:</strong><br>
      <?= date("F j, Y", strtotime($user['created_at'])) ?>
    </div>

    <div class="info-card">
      <strong>Total Giveaways Hosted:</strong><br>
      <?= count($giveaways) ?><br><br>
      <strong>Active Giveaways:</strong><br>
      <?= count(array_filter($giveaways, fn($g) => $g['status'] === 'active')) ?>
    </div>
  </div>

  <div class="btn-row">
    <a href="edit_profile.php" class="btn-secondary btn">✏️ Edit Profile</a>
    <a href="create_giveaway.php" class="btn">➕ New Giveaway</a>
  </div>

  <div class="giveaway-list">
    <h3>📦 Your Giveaways</h3>
    <?php if (empty($giveaways)): ?>
      <p><em>You haven’t created any giveaways yet.</em></p>
    <?php else: ?>
      <?php foreach ($giveaways as $g): ?>
        <div class="giveaway-card">
          <h4><?= htmlspecialchars($g['title']) ?></h4>
          <p>💰 Total Prize: ₦<?= number_format($g['total_prize'], 2) ?></p>
          <p>🏆 Winners: <?= $g['number_of_winners'] ?></p>
          <p>🕒 Ends: <?= date("M j, Y H:i", strtotime($g['end_at'])) ?></p>
          <p>Status: <span class="status <?= htmlspecialchars($g['status']) ?>"><?= htmlspecialchars($g['status']) ?></span></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
