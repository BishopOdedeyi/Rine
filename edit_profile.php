<?php
session_start();
require_once 'api/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user info
$stmt = $pdo->prepare("SELECT name, email, contact_link FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile | Giveaway Hub</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: #f0f3f7;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
.card {
  background: white;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  width: 400px;
}
h2 {
  text-align: center;
  margin-bottom: 15px;
}
label {
  font-weight: 500;
  display: block;
  margin-top: 10px;
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
  background: #007bff;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}
button:hover {
  background: #0056b3;
}
.back {
  display: block;
  margin-top: 15px;
  text-align: center;
  color: #007bff;
  text-decoration: none;
}
.notice {
  margin-top: 10px;
  text-align: center;
}
.success { color: green; }
.error { color: red; }
</style>
</head>
<body>

<div class="card">
  <h2>✏️ Edit Profile</h2>

  <form id="editProfileForm">
    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label>Email (readonly)</label>
    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

    <label>Contact Link</label>
    <input type="text" name="contact_link" placeholder="e.g. https://twitter.com/you" value="<?= htmlspecialchars($user['contact_link']) ?>">

    <button type="submit">💾 Save Changes</button>
  </form>

  <div id="message" class="notice"></div>

  <a href="profile.php" class="back">← Back to Profile</a>
</div>

<script>
document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const res = await fetch('api/update_profile.php', { method: 'POST', body: formData });
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
