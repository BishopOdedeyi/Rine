# 🎁 Rine — A Decentralized Giveaway Experience

**Rine** is a transparent, community-driven giveaway platform that lets creators, brands, and individuals host fair giveaways — without holding prizes hostage.
Winners connect directly with hosts, reviews are open and anonymous, and everything is logged transparently for trust and accountability.

---

## 🚀 Features

### 🧩 For Everyone

* 🎉 Browse all **active giveaways** in real time
* 📝 Join by completing simple eligibility tasks
* 💬 Leave **anonymous reviews** after giveaways end
* 🔍 View giveaway history, host details, and prize info

### 👤 For Hosts

* ⚙️ Create and manage giveaways with start and end dates
* 📅 Set prize amount, number of winners, and distribution style
* 📞 Add contact information for transparency
* 🧾 Automatically moves ended giveaways to “archived”
* 💭 Receive public feedback from participants

### 🌍 Transparency by Design

* Reviews are **anonymous** and can’t be deleted
* Each giveaway’s history stays visible forever
* Winners contact hosts directly — no middleman or hidden claim system

---

## 🏗️ Project Structure

```
Rine/
│
├── api/                     # All backend endpoints
│   ├── db.php               # Database connection
│   ├── get_all_giveaways.php
│   ├── add_review.php
│   ├── get_reviews.php
│   └── (more endpoints)
│
├── css/
│   └── style.css            # Core styling
│
├── index.php                # Homepage listing active giveaways
├── giveaway.php             # Individual giveaway page with join/review system
├── profile.php              # User/host profile and management area
├── login.php                # Login page
├── signup.php               # Registration page
└── README.md
```

---

## 🧰 Tech Stack

| Layer    | Technology                                        |
| :------- | :------------------------------------------------ |
| Frontend | HTML5, CSS3, JavaScript (Vanilla)                 |
| Backend  | PHP 8+                                            |
| Database | MySQL / MariaDB                                   |
| Hosting  | Compatible with XAMPP, LAMP, or remote VPS setups |

---

## ⚙️ Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/BishopOdedeyi/Rine.git
   cd Rine
   ```

2. **Import the database**

   * Open `phpMyAdmin`
   * Create a new database (e.g., `giveaway_poc`)
   * Import the included SQL file (`giveaway_poc.sql`)

3. **Set up environment**

   * Edit `/api/db.php` and configure your database credentials:

     ```php
     $host = 'localhost';
     $dbname = 'giveaway_poc';
     $username = 'root';
     $password = '';
     ```

4. **Run locally**

   * Place the project inside your XAMPP `htdocs` folder
   * Start **Apache** and **MySQL** in XAMPP
   * Visit [http://localhost/Rine](http://localhost/Rine)

---

## 💡 Future Roadmap

* 🔒 Authentication tokens for secure sessions
* 📱 Mobile-responsive UI overhaul
* 🎯 Task verification API (e.g., social follow proof)
* 📢 Host profiles with follower system
* 🌐 Blockchain-based transparency ledger for completed giveaways

---

## 🫱🏽‍🫲🏽 Contributing

We’re open to contributions!
Fork the repo, make your changes, and submit a **pull request**.

Whether you’re fixing a bug, improving the design, or adding features — your input helps make **Rine** more fair, open, and community-friendly.

---

## 🧑‍💻 Author

**Bishop Odedeyi**
🔗 [GitHub Profile](https://github.com/BishopOdedeyi)
💬 *“Building trust into the giveaway ecosystem — one open project at a time.”*

---

## 🪪 License

This project is open-source under the **MIT License**.
Feel free to use, modify, and share it with proper attribution.