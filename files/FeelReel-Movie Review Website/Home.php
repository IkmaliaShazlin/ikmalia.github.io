<?php

include 'db_movie.php'; // required to fetch movies

// Fetch recently added movies (e.g., latest 6)
$newlyAddedQuery = $conn->query("SELECT * FROM movies ORDER BY id DESC LIMIT 6");

session_start();
if (isset($_SESSION["user_id"])) {
    // Get logged-in user info
    $mysqli = require __DIR__ . "/testDatabase.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
     
    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
  <style>
.search-container {
  display: flex;
  align-items: center;
  justify-content: center; /* or flex-end */
  gap: 1px; /* Removes the visible gap */
  right-width: 100%;
  max-width: 1000px;
  margin: 20px auto;
  padding: 10px 20px;
  box-sizing: border-box;
  border-radius: 6px;

  position: absolute;
  top: 20px;
  right: 20px;
  display: flex;
  align-items: center;
  z-index: 100;

}

.search-wrapper {
  position: relative;
  width: 200px;
  flex: none;
}

.search-input {
  width: 100%;
  padding: 8px 10px;
  border-radius: 6px 0 0 6px; /* Rounded left only */
  border: 1px solid #ccc;
  font-size: 14px;
  box-sizing: border-box;
}

#searchDropdown {
  list-style-type: none;
  padding: 0;
  margin: 0;
  border: 1px solid #ccc;
  max-height: 200px;
  overflow-y: auto;
  background-color: white;
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 1000;
  border-radius: 0 0 6px 6px;
  box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
  display: none;
}

#searchDropdown li a {
  padding: 10px;
  display: block;
  text-decoration: none;
  color: #000;
}

#searchDropdown li a:hover {
  background-color: #f0f0f0;
}

.dropdown {
  position: relative;
}

.dropbtn {
  background-color: #444;
  color: white;
  padding: 9px 16px;
  
  border-left: 1px solid #ccc; 
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
  height: 36px; /* Match input height */
}


.dropbtn:hover {
  background-color: #666;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #222;
  min-width: 200px;
  z-index: 1;
  top: 110%;
  right: 0;
  border-radius: 6px;
  box-shadow: 0px 4px 8px rgba(0,0,0,0.3);

  max-height: 200px; /* shows about 5 */
  overflow-y: auto;
}

.dropdown-content a {
  color: white;
  padding: 10px 15px;
  text-decoration: none;
  display: block;
  border-bottom: 1px solid #333;
}

.dropdown-content a:last-child {
  border-bottom: none;
}

.dropdown-content a:hover {
  background-color: #333;
}


</style>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Genre Slider</title>
  <link rel="stylesheet" href="Home.css"> 
</head>
<body>


  <br>
  <div id="main"> 
    <button class="openbtn" onclick="toggleNav()" style="background: none;"> 
        <img src="image/Logo.png" alt="logo" style="height: 70px; width: 70px; position: fixed; top: 10px; left: 10px;"> 
    </button>
  </div>
  <!--Search Bar-->
  <div class="search-container">
    
      <div class="search-wrapper">
      <input type="text" id="searchInput" placeholder="Search..." class="search-input" onkeyup="liveSearch()">
      <ul id="searchDropdown"></ul>
      </div>
      <div class="navbar">
      <div class="dropdown">
        <!--Dropdown for categories (genres and languages)-->
        <button onclick="toggleDropdown()" class="dropbtn" type="button">Categories ▾</button>
        <div id="dropdownContent" class="dropdown-content">
          <a href="#" onclick="filterByGenre('action')">Action</a>
          <a href="#" onclick="filterByGenre('musical')">Musical</a>
          <a href="#" onclick="filterByGenre('romance')">Romance</a>
          <a href="#" onclick="filterByGenre('horror')">Horror</a>
          <a href="#" onclick="filterByGenre('')">All Genres</a>
          <a href="#" onclick="filterByLanguage('english')">English</a>
          <a href="#" onclick="filterByLanguage('malay')">Malay</a>
          <a href="#" onclick="filterByLanguage('tamil')">Tamil</a>
          <a href="#" onclick="filterByLanguage('chinese')">Chinese</a>
          <a href="#" onclick="filterByLanguage('indonesian')">Indonesian</a>
          <a href="#" onclick="filterByLanguage('')">All Languages</a>
        </div>
      </div>
    </div>

  </div> <br> <br> <br> <br>
  <script>

    const filterButton = document.getElementById("filterButton");
    const filterMenu = document.getElementById("filterMenu");

    filterButton.addEventListener("click", () => {
        
      if (filterMenu.style.display === "flex") {
        filterMenu.style.display = "none";
      } else {
        filterMenu.style.display = "flex";
      }
    });
  </script>

  <header class="header"> 
    <h1>FeelReel</h1>
    <?php if (isset($user)): ?>   
        <p>Hello <?= htmlspecialchars($user["username"]) ?></p> 
    <?php endif; ?>
    <p>~ Feel movies that resonates with your soul ~</p>
    <h2>G E N R E</h2>
  </header>

  <main>
 
    <!-- Sidebar menu -->
    <div id="mySidebar" class="sidebar"> 
      <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">
          <img src="image/Logo.png" alt="logo" style="height: 80px; width: 80px; position: absolute; top: 10px; left: 10px;">
      </a> 
      <a href="Home.php">Home</a> 
      <a href="Profile.php">Profile</a> 
      <a href="Contact.html">Contact Us</a>
      <a href="ManageUserProfile.php">Settings</a>
      <a href="UserLogout.php">Log Out</a>
    </div>

  <!-- Newest movies slider -->
  <div class="genre-section">
  <h2 style="color: wheat; text-align: center; font-family: 'Times New Roman';">NEWLY ADDED</h2>
  <div class="movie-slider">
    <?php while($movie = $newlyAddedQuery->fetch_assoc()): ?>
      <div class="movie-card">
        <a href="Synopsis.php?id=<?= $movie['id'] ?>" target="_blank" style="text-decoration: none;">
          <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
          <p><?= htmlspecialchars($movie['title']) ?></p>
        </a>
      </div>
    <?php endwhile; ?>
  </div>

    <!-- Genre sections -->
    <div class="genre-section">
      <h2 style="color: wheat; text-align: center; font-family: 'Times New Roman';" >MUSICAL</h2>
      <div class="movie-slider">
      <?php
        $musicalQuery = $conn->query("SELECT * FROM movies WHERE genre LIKE '%musical%' ORDER BY id ASC");
        while ($movie = $musicalQuery->fetch_assoc()):
      ?>
        <div class="movie-card" data-genre="musical" data-language="<?= htmlspecialchars($movie['language']) ?>">
          <a href="Synopsis.php?id=<?= $movie['id'] ?>" target="_blank" style="text-decoration: none;">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <p><?= htmlspecialchars($movie['title']) ?></p>
          </a>
        </div>
      <?php endwhile; ?>
      </div>
    </div>

    <div class="genre-section">
      <h2 style="color: wheat; text-align: center; font-family: 'Times New Roman';">ROMANCE</h2>
      <div class="movie-slider">
      <?php
        $musicalQuery = $conn->query("SELECT * FROM movies WHERE genre LIKE '%romance%' ORDER BY id ASC");
        while ($movie = $musicalQuery->fetch_assoc()):
      ?>
        <div class="movie-card" data-genre="romance" data-language="<?= htmlspecialchars($movie['language']) ?>">
          <a href="Synopsis.php?id=<?= $movie['id'] ?>" target="_blank" style="text-decoration: none;">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <p><?= htmlspecialchars($movie['title']) ?></p>
          </a>
        </div>
      <?php endwhile; ?>
      </div>
    </div>

    <div class="genre-section">
      <h2 style="color: wheat; text-align: center; font-family: 'Times New Roman';">HORROR</h2>
      <div class="movie-slider">
      <?php
        $musicalQuery = $conn->query("SELECT * FROM movies WHERE genre LIKE '%horror%' ORDER BY id ASC");
        while ($movie = $musicalQuery->fetch_assoc()):
      ?>
        <div class="movie-card" data-genre="horror" data-language="<?= htmlspecialchars($movie['language']) ?>">
          <a href="Synopsis.php?id=<?= $movie['id'] ?>" target="_blank" style="text-decoration: none;">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <p><?= htmlspecialchars($movie['title']) ?></p>
          </a>
        </div>
      <?php endwhile; ?>
      </div>
    </div>

    <div class="genre-section">
      <h2 style="color: wheat; text-align: center; font-family: 'Times New Roman';">FANTASY</h2>
      <div class="movie-slider">
      <?php
        $musicalQuery = $conn->query("SELECT * FROM movies WHERE genre LIKE '%fantasy%' ORDER BY id ASC");
        while ($movie = $musicalQuery->fetch_assoc()):
      ?>
        <div class="movie-card" data-genre="fantasay" data-language="<?= htmlspecialchars($movie['language']) ?>">
          <a href="Synopsis.php?id=<?= $movie['id'] ?>" target="_blank" style="text-decoration: none;">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <p><?= htmlspecialchars($movie['title']) ?></p>
          </a>
        </div>
      <?php endwhile; ?>
      </div>
    </div>

    <div class="genre-section">
      <h2 style="color: wheat; text-align: center; font-family: 'Times New Roman';">ACTION</h2>
      <div class="movie-slider">
      <?php
        $musicalQuery = $conn->query("SELECT * FROM movies WHERE genre LIKE '%action%' ORDER BY id ASC");
        while ($movie = $musicalQuery->fetch_assoc()):
      ?>
        <div class="movie-card" data-genre="action" data-language="<?= htmlspecialchars($movie['language']) ?>">
          <a href="Synopsis.php?id=<?= $movie['id'] ?>" target="_blank" style="text-decoration: none;">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <p><?= htmlspecialchars($movie['title']) ?></p>
          </a>
        </div>
      <?php endwhile; ?>
      </div>
    </div>
  </main>

  <footer class="footer">
    <p>&copy; 2024 FeelReel. All Rights Reserved.</p>
  </footer>
  
<script>
  function toggleDropdown() {
  const content = document.getElementById("dropdownContent");
  content.style.display = content.style.display === "block" ? "none" : "block";
 }

// Optional: close dropdown when clicking outside
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    const dropdowns = document.getElementsByClassName("dropdown-content");
    for (let i = 0; i < dropdowns.length; i++) {
      dropdowns[i].style.display = "none";
    }
  }
}

//Filter movies by genre
function filterByGenre(genre) {
  const movieCards = document.querySelectorAll(".movie-card");
  movieCards.forEach(card => {
    const cardGenre = (card.dataset.genre || "").toLowerCase();
    const matchGenre = !genre || cardGenre.includes(genre.toLowerCase());
    card.style.display = matchGenre ? "block" : "none";
  });
}

//Filter movies by language
function filterByLanguage(language) {
  const movieCards = document.querySelectorAll(".movie-card");
  movieCards.forEach(card => {
    const cardLang = (card.dataset.language || "").toLowerCase();
    const matchLang = !language || cardLang.includes(language.toLowerCase());
    card.style.display = matchLang ? "block" : "none";
  });
}

//Search for specific movies and its dropdown list
function liveSearch() {
  const input = document.getElementById("searchInput");
  const filter = input.value.toLowerCase();
  const dropdown = document.getElementById("searchDropdown");

  dropdown.innerHTML = "";

  if (filter === "") {
    dropdown.style.display = "none";
    return;
  }

  const movieCards = document.querySelectorAll(".movie-card");
  const seenTitles = new Set(); // to avoid duplicate titles in dropdown
  let found = false; //to check if any match is found

  movieCards.forEach(card => {
    const title = card.querySelector("p").textContent.toLowerCase(); // get movie title
    const link = card.querySelector("a").getAttribute("href"); // get link to movie page

    // if title matches search filter and not already added
    if (title.includes(filter) && !seenTitles.has(title)) {
      seenTitles.add(title);

      const li = document.createElement("li"); // create dropdown list item
      li.innerHTML = `<a href="${link}" target="_blank">${title}</a>`; // add link
      dropdown.appendChild(li); // add to dropdown list
      found = true;
    }
  });

  dropdown.style.display = found ? "block" : "none";
}


</script>
<script src="Home.js" ></script>

</body>
</html> 



