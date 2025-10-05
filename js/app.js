// app.js

const baseURL = "./api";

// Load all giveaways (homepage)
async function loadGiveaways() {
  const container = document.getElementById("giveaways-list");
  if (!container) return; // skip if not on homepage

  try {
    const res = await fetch(`${baseURL}/get_all_giveaways.php`);
    const data = await res.json();

    container.innerHTML = "";

    if (!data.success || data.count === 0) {
      container.innerHTML = "<p>No active giveaways at the moment 😔</p>";
      return;
    }

    data.giveaways.forEach(g => {
      const card = document.createElement("div");
      card.className = "giveaway-card";
      card.innerHTML = `
        <h3>${g.title}</h3>
        <p>${g.description.substring(0, 100)}...</p>
        <div class="meta">
          🎯 ${g.number_of_winners} Winners<br>
          💰 ₦${g.total_prize}<br>
          👤 Hosted by ${g.host_name || "Anonymous"}<br>
          🕒 ${new Date(g.created_at).toLocaleString()}
        </div>
      `;
      card.addEventListener("click", () => {
        window.location.href = `giveaway.php?id=${g.id}`;
      });
      container.appendChild(card);
    });
  } catch (err) {
    container.innerHTML = `<p class="error">Error loading giveaways: ${err.message}</p>`;
  }
}

// Modal Logic
const makeBtn = document.getElementById("makeGiveawayBtn");
const modal = document.getElementById("createModal");
const closeModal = document.getElementById("closeModal");

if (makeBtn) {
  makeBtn.addEventListener("click", () => modal.style.display = "flex");
}
if (closeModal) {
  closeModal.addEventListener("click", () => modal.style.display = "none");
}
window.addEventListener("click", e => {
  if (e.target === modal) modal.style.display = "none";
});

// Run homepage loader
loadGiveaways();
