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

<style>
.w3-grey{
  color: white  !important;
  background: none !important;
}


  </style>

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

  <div class="mt-2">
    <a href="driversched.php" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios/50/FFFFFF/planner.png" alt="Planner Icon">
      </div>
      <div class="ml-1.5">
        Schedules
      </div>
    </a>
  </div>  

  <div class="mt-2">
    <a href="drivermap.php" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios/50/FFFFFF/planner.png" alt="Planner Icon">
      </div>
      <div class="ml-1.5">
        Map
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

  function w3_open() {
            document.getElementById("mySidebar").style.display = "block";
        }

        function w3_close() {
            document.getElementById("mySidebar").style.display = "none";
        }
    </script>
</head>


</script>

</body>
</html>
