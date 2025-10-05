<?php
require_once './api/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid giveaway ID");
}

$id = intval($_GET['id']);

// Fetch giveaway info
$stmt = $pdo->prepare("
    SELECT 
        g.*,
        COALESCE(u.name, 'Anonymous') AS host_name
    FROM giveaways g
    LEFT JOIN users u ON g.host_user_id = u.id
    WHERE g.id = ?
");
$stmt->execute([$id]);
$giveaway = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$giveaway) {
    die("Giveaway not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($giveaway['title']) ?></title>
  <link rel="stylesheet" href="css/style.css">

  <style>
    .giveaway-container {
      max-width: 800px;
      margin: 3rem auto;
      background: rgba(255,255,255,0.05);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 0 20px rgba(0,255,255,0.2);
    }
    .giveaway-container h1 {
      color: #00c6ff;
    }
    .host {
      color: #ccc;
      font-size: 0.9rem;
    }
    .info {
      margin-top: 1rem;
      background: rgba(0,0,0,0.3);
      padding: 1rem;
      border-radius: 10px;
      color: #aaa;
    }
    .tasks {
      margin-top: 1.5rem;
    }
    .task {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    .task input {
      margin-right: 0.5rem;
    }
    #joinBtn {
      display: block;
      margin: 2rem auto;
      padding: 1rem 2rem;
      background: #00c6ff;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 1.2rem;
      cursor: pointer;
      transition: 0.3s ease;
    }
    #joinBtn:hover {
      background: #0091cc;
    }
    .eligible {
      animation: glow 1s ease-in-out infinite alternate;
    }
    @keyframes glow {
      from {
        box-shadow: 0 0 10px #00c6ff;
      }
      to {
        box-shadow: 0 0 25px #00ffae;
      }
    }

    #reviews-section {
    background: #f8f9fb; /* light gray instead of pure white */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    color: #222; /* dark text for readability */
    }

    .review-card {
    margin-bottom: 10px;
    background: #fff;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    textarea,
    input[type="number"] {
    width: 100%;
    margin-bottom: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    padding: 8px;
    background: #fafafa;
    color: #222;
    }

    button {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 10px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s ease-in-out;
    }

    button:hover {
    background: #005fcc;
    }

    #reviewsList p,
    #reviewsList small {
    color: #333; /* ensure all review text is visible */
    }

  </style>
</head>

<body>
  <div class="giveaway-container">
    <h1><?= htmlspecialchars($giveaway['title']) ?></h1>
    <p class="host">Hosted by <?= htmlspecialchars($giveaway['host_name']) ?></p>

    <div class="info">
      💰 <strong>Total Prize:</strong> ₦<?= number_format($giveaway['total_prize'], 2) ?><br>
      🎯 <strong>Winners:</strong> <?= $giveaway['number_of_winners'] ?><br>
      🕒 <strong>Ends:</strong> <?= date("F j, Y, g:i a", strtotime($giveaway['end_at'])) ?><br>
      ⚙️ <strong>Status:</strong> <?= ucfirst($giveaway['status']) ?>
    </div>

    <h3 style="margin-top:1.5rem;">📋 Eligibility Tasks</h3>
    <div class="tasks">
      <div class="task"><input type="checkbox" class="taskChk">Follow host on Instagram</div>
      <div class="task"><input type="checkbox" class="taskChk">Share the post on your story</div>
      <div class="task"><input type="checkbox" class="taskChk">Tag 2 friends</div>
    </div>

    <button id="joinBtn">Join Giveaway</button>
  </div>

  <div id="reviews-section" style="margin-top:40px;">
    <h3>💬 Community Reviews</h3>

    <div id="review-form">
      <h4>Leave a Review (Anonymous)</h4>
      <form id="reviewForm">
        <input type="hidden" name="giveaway_id" value="<?= $giveaway['id'] ?>">
        <label>Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required>
        <label>Your thoughts:</label>
        <textarea name="review_text" rows="3" placeholder="Write your honest review..." required></textarea>
        <button type="submit">📝 Submit Review</button>
      </form>
      <div id="reviewMsg"></div>
    </div>

    <hr>
    <h4>What Others Said</h4>
    <div id="reviewsList"></div>
  </div>

  <script>
    const tasks = document.querySelectorAll('.taskChk');
    const joinBtn = document.getElementById('joinBtn');

    tasks.forEach(chk => chk.addEventListener('change', checkEligibility));

    function checkEligibility() {
      const allDone = Array.from(tasks).every(t => t.checked);
      if (allDone) {
        joinBtn.classList.add('eligible');
        joinBtn.textContent = "✅ You're Eligible! Click to Join";
      } else {
        joinBtn.classList.remove('eligible');
        joinBtn.textContent = "Join Giveaway";
      }
    }

    joinBtn.addEventListener('click', () => {
      const allDone = Array.from(tasks).every(t => t.checked);
      if (!allDone) {
        alert("Please complete all eligibility tasks first!");
        return;
      }
      alert("🎉 You've successfully joined this giveaway!");
      // (Later, we’ll record this via an API call)
    });

    async function loadReviews() {
      const res = await fetch('api/get_reviews.php?giveaway_id=<?= $giveaway['id'] ?>');
      const data = await res.json();

      const container = document.getElementById('reviewsList');
      container.innerHTML = '';

      if (!data.success || data.count === 0) {
        container.innerHTML = '<p>No reviews yet.</p>';
        return;
      }

      data.reviews.forEach(r => {
        const div = document.createElement('div');
        div.classList.add('review-card');
        div.innerHTML = `
          <p>⭐ ${r.rating}/5</p>
          <p>${r.review_text}</p>
          <small>${r.created_at}</small>
          <hr>
        `;
        container.appendChild(div);
      });
    }

    document.getElementById('reviewForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);
      const res = await fetch('api/add_review.php', { method: 'POST', body: formData });
      const data = await res.json();

      const msg = document.getElementById('reviewMsg');
      if (data.success) {
        msg.innerHTML = '<span style="color:green;">✅ Review submitted!</span>';
        e.target.reset();
        loadReviews();
      } else {
        msg.innerHTML = '<span style="color:red;">❌ ' + data.error + '</span>';
      }
    });

    loadReviews();
  </script>
</body>
</html>
