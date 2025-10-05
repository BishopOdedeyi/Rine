<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Giveaway Hub</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: #e7ebf3;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  width: 350px;
}
h2 { text-align:center; }
input {
  width:100%;
  padding:10px;
  margin-top:10px;
  border:1px solid #ccc;
  border-radius:8px;
}
button {
  width:100%;
  margin-top:15px;
  background:#007bff;
  color:white;
  border:none;
  border-radius:8px;
  padding:10px;
  cursor:pointer;
  transition:0.3s;
}
button:hover { background:#0056b3; }
.error { color:red; text-align:center; margin-top:10px; }
a { display:block; text-align:center; margin-top:10px; color:#007bff; text-decoration:none; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="card">
  <h2>🔑 Login</h2>
  <div id="message"></div>
  <form id="loginForm">
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Login</button>
  </form>
  <a href="signup.php">Don't have an account? Sign up</a>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/auth_login.php', { method: 'POST', body: formData });
  const data = await res.json();

  if(data.success) location.href = 'profile.php';
  else document.getElementById('message').innerHTML = '<div class="error">'+data.error+'</div>';
});
</script>

</body>
</html>
