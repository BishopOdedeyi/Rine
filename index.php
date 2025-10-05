<?php
// public/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giveaway POC</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container">
    <header>
      <h1>🎁 Giveaways</h1>
      <div class="top-buttons">
        <button id="makeGiveawayBtn">➕ Make a Giveaway</button>
      </div>
    </header>

    <div id="giveaways-list" class="giveaway-grid">
      <p class="loading">Loading giveaways...</p>
    </div>
  </div>

  <!-- Modal for creating giveaway -->
  <div id="createModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeModal">&times;</span>
      <h2>Create a Giveaway</h2>
      <p>We’ll build this next 😎</p>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
