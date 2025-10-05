<?php
session_start();
require_once 'api/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details for contact verification
$stmt = $pdo->prepare("SELECT name, contact_link FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Giveaway | Giveaway Hub</title>
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
  max-width: 700px;
  margin: 40px auto;
  background: white;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
h2 {
  text-align: center;
}
form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
label {
  font-weight: 500;
}
input, textarea, select {
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  width: 100%;
}
button {
  background: #007bff;
  border: none;
  color: white;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}
button:hover {
  background: #0056b3;
}
.notice {
  background: #f8f9fb;
  border-left: 4px solid #007bff;
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 8px;
}
.success {
  background: #d4f8d4;
  border-left-color: #2e7d32;
}
.error {
  background: #ffe3e3;
  border-left-color: #a30d0d;
}
</style>
</head>
<body>

<div class="header">
  <h1>🎁 Giveaway Hub</h1>
  <div>
    <a href="profile.php">⬅ Back to Profile</a>
  </div>
</div>

<div class="container">
  <h2>➕ Create a New Giveaway</h2>

  <?php if (!$user['contact_link']): ?>
  <div class="notice error">
    ⚠️ You haven’t added a contact link yet! Please update your <a href="edit_profile.php">profile</a> so winners can reach you.
  </div>
  <?php endif; ?>

  <form id="giveawayForm">
    <label>Title</label>
    <input type="text" name="title" required placeholder="E.g., Free T-shirt Giveaway">

    <label>Description</label>
    <textarea name="description" rows="4" placeholder="Describe your giveaway..." required></textarea>

    <label>Total Prize (₦)</label>
    <input type="number" step="0.01" name="total_prize" required>

    <label>Number of Winners</label>
    <input type="number" name="number_of_winners" value="1" min="1" required>

    <label>Distribution Type</label>
    <select name="distribution" required>
      <option value="equal">Equal</option>
      <option value="custom">Custom</option>
    </select>

    <label>End Date</label>
    <input type="datetime-local" name="end_at" required>

    <button type="submit">Create Giveaway</button>
  </form>

  <div id="message"></div>
</div>

<script>
document.getElementById("giveawayForm").addEventListener("submit", async function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const response = await fetch("./api/create_giveaway.php", {
    method: "POST",
    body: formData
  });
  
  const data = await response.json();
  const messageBox = document.getElementById("message");

  if (data.success) {
    messageBox.innerHTML = `<div class='notice success'>✅ Giveaway created successfully!</div>`;
    this.reset();
  } else {
    messageBox.innerHTML = `<div class='notice error'>❌ ${data.error}</div>`;
  }
});
</script>

</body>
</html>
