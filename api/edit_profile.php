<?php
session_start();
require_once 'api/db.php';

// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT id, name, email, contact_link FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Giveaway Hub</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #f3f5f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 30px;
      width: 400px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      display: block;
      margin-top: 10px;
      font-weight: 500;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    button {
      width: 100%;
      margin-top: 20px;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #007bff;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #0056b3;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #007bff;
      text-decoration: none;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .success, .error {
      text-align: center;
      margin-bottom: 10px;
      font-weight: 500;
    }
    .success {
      color: green;
    }
    .error {
      color: red;
    }
  </style>
</head>
<body>

<div class="card">
  <h2>✏️ Edit Profile</h2>

  <div id="message"></div>

  <form id="editForm">
    <label for="name">Display Name</label>
    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name']) ?>">

    <label for="email">Email (read-only)</label>
    <input type="email" id="email" name="email" readonly value="<?= htmlspecialchars($user['email']) ?>">

    <label for="contact_link">Contact Link</label>
    <input type="text" id="contact_link" name="contact_link" placeholder="e.g. https://twitter.com/you" value="<?= htmlspecialchars($user['contact_link']) ?>">

    <button type="submit">💾 Save Changes</button>
  </form>

  <a href="profile.php" class="back-link">← Back to Profile</a>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(e.target);
  const res = await fetch('api/update_profile.php', {
    method: 'POST',
    body: formData
  });
  const data = await res.json();

  const msg = document.getElementById('message');
  if (data.success) {
    msg.innerHTML = '<div class="success">✅ Profile updated successfully!</div>';
  } else {
    msg.innerHTML = '<div class="error">❌ ' + data.error + '</div>';
  }
});
</script>

</body>
</html>
