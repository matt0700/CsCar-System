<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../assets/header.css">
<link rel="stylesheet" href="./output.css">

<!-- tailwind -->
<script src="https://cdn.tailwindcss.com"></script>


<body>
<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left bg-slate-900 text-white z-10 sidebar" style="width:200px;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large " onclick="w3_close()">Close &times;</button>

  <a href="#" class="w3-bar-item w3-button mt-2">CSCar</a>

  <div class="mt-2">
    <button class= " w-screen w3-button " >
    <a href="#" class=" no-underline flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/material-outlined/24/FFFFFF/home--v2.png">
      </div>
      <div class="ml-1 text-white" >
        Dashboard
      </div>
    </a>
    </button>
  </div>
  
  <div class="mt-2">
    <a href="#" class="w3-button w3-bar flex items-center">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios/50/FFFFFF/car--v1.png">
      </div>
      <div class="ml-1" >
        Fleet Management
      </div>
    </a>
  </div>

  <div class="mt-2">
    <a href="#" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios/50/FFFFFF/planner.png">
      </div>
      <div class="ml-1.5" >
        Schedules
      </div>
    </a>
  </div>

  <div class="mt-2">
    <a href="#" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios-glyphs/30/FFFFFF/group.png">
      </div>
      <div class="ml-1" >
        Drivers
      </div>
    </a>
  </div>

  <div class="mt-2">
    <a href="#" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/material-sharp/24/FFFFFF/person-male.png">
      </div>
      <div class="ml-1" >
        Users
      </div>
    </a>
  </div>

  <div class="mt-2">
    <a href="#" class="w3-button flex items-center w3-bar">
      <div>
        <img class="w-5 h-5" src="https://img.icons8.com/ios-filled/50/FFFFFF/settings.png">
      </div>
      <div class="ml-1" >
        Settings
      </div>
    </a>
  </div>

  <div class>
    <a href="#" class="w3-button flex items-center w3-bar w3-display-bottommiddle ">
      <div>
        <img class=" w3-show w-5 h-5" src="https://img.icons8.com/material-outlined/24/FFFFFF/help.png">
      </div>
      <div class="ml-1" >
        Help & Support
      </div>
    </a>
  </div>

</div>



<script>
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
}
</script>
     
</body>
</html>
