<!DOCTYPE html>
<html>
<head>
  <title>W3.CSS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="../assets/header.css">
  <link rel="stylesheet" href="./output.css">
  <!-- tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>


<div class="w3-sidebar w3-bar-fixed w3-collapse w3-card w3-animate-left bg-slate-900 text-white z-10 sidebar overflow-x-hidden border-none" style="width:200px;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="w3_close()">Close &times;</button>

  <div class="mt-4">
    <button class="w3-button flex items-center w3-bar">
      <a href="index.php" class="no-underline flex items-center w3-bar">
        <div>
      <img class="h-auto max-w-full" src='logo.png'>
        </div>
        <div class="ml-1 text-white text-xl">
          CsCar
        </div>
      </a>
    </button>
  </div>

  <div class="mt-4">
    <button class="w3-button flex items-center w3-bar">
      <a href="index.php" class="no-underline flex items-center w3-bar">
        <div>
          <img class="w-5 h-5" src="https://img.icons8.com/material-outlined/24/FFFFFF/home--v2.png" alt="Home Icon">
        </div>
        <div class="ml-1 text-white">
          Dashboard
        </div>
      </a>
    </button>
  </div>
  
  <div class="relative inline-block text-left">
    <div>
      <button class="w3-button flex items-center w3-bar" id="menu-button" aria-expanded="false" aria-haspopup="true">
        <img class="w-5 h-5" src="https://img.icons8.com/?size=100&id=12666&format=png&color=FFFFFF" alt="Car Icon">
        <span class="ml-1">Fleet Management</span>
        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
      <div class="py-1" role="none">
        <a href="map.php" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Map View</a>
        <a href="vehicle.php" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-1">Vehicle Information</a>
      </div>
    </div>
  </div>

  <div class="mt-2">
    <a href="schedule.php" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios/50/FFFFFF/planner.png" alt="Planner Icon">
      </div>
      <div class="ml-1.5">
        Schedules
      </div>
    </a>
  </div>

  <div class="mt-2">
    <a href="driver.php" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios-glyphs/30/FFFFFF/group.png" alt="Group Icon">
      </div>
      <div class="ml-1">
        Drivers
      </div>
    </a>
  </div>

  <div class="mt-2">
    <a href="../logout.php" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios/50/FFFFFF/exit--v1.png" alt="Exit Icon">
      </div>
      <div class="ml-1">
        Logout
      </div>
    </a>
  </div>
  

  <div>
    <a href="#" class="w3-button flex items-center w3-bar w3-display-bottommiddle">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/material-outlined/24/FFFFFF/help.png" alt="Help Icon">
      </div>
      <div class="ml-1">
        Help & Support
      </div>
    </a>
  </div>

</div>

<script>
  // Toggle dropdown menu
  document.getElementById('menu-button').addEventListener('click', function() {
    const dropdown = document.querySelector('.origin-top-right');
    dropdown.classList.toggle('hidden');
  });

  // Close dropdown when clicking outside
  window.addEventListener('click', function(e) {
    const button = document.getElementById('menu-button');
    const dropdown = document.querySelector('.origin-top-right');
    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>

</body>
</html>
