<?php

session_start();
require_once("connection.php"); 

if (isset($_POST['ruv_submit']))
{
  $pickup_point = $_POST['pickup_point'];
  $destination = $_POST['destination'];
  $trip_date = $_POST['trip_date'];
  $pref_time = $_POST['pref_time'];
  $no_passengers = $_POST['no_passengers'];
  $eta_destination = $_POST['eta_destination'];
  $req_official = $_POST['req_official'];
  $name_passengers = $_POST['name_passengers'];
  $reason = $_POST['reason'];

  $query = "INSERT INTO ruv_table (pickup_point, destination, trip_date, pref_time, no_passengers, eta_destination, req_official, name_passengers, reason) 
  VALUES ('$pickup_point', '$destination', '$trip_date', '$pref_time', '$no_passengers', '$eta_destination', '$req_official', '$name_passengers', '$reason')";
  $result = mysqli_query($connect,$query);

  if($result)
  {
    echo "RUV has been submitted";
  }else{
    echo "RUV has not been submitted";
  }

  mysqli_close($connect);
}

?>


<!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css"
  rel="stylesheet"
/>

<!-- tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="flex bg-black">
      <form class="m-auto" action="RUV.php" method="POST">
            <div class="mx-auto bg-white p-6 rounded-lg shadow-lg w-400">
                <h2 class="text-2xl font-bold mb-6">RUV</h2>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- Pick-up point input -->
                      <div class="w-full mb-4">
                          <label class="block text-sm font-medium text-gray-700" for="pickup-point">Pick-up point <span class="text-red-500">*</span></label>
                          <input type="text" id="pickup-point" name="pickup_point" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                      </div>
                      
                        <!-- Destination -->
                        <div class="w-full mb-4">
                            <label class="block text-sm font-medium text-gray-700" for="password1">Destination <span class="text-red-500">*</span></label>
                            <input type="text" id="password1" name="destination" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"required  />
                        </div>

                          <!-- Date of Trip -->
                          <div class="w-full mb-4">
                              <label class="block text-sm font-medium text-gray-700" for="date-of-trip">Date of Trip <span class="text-red-500">*</span></label>
                              <input type="date" id="date-of-trip" name="trip_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                          </div>
                            
                            <!-- Preferred Pick-up Time -->
                            <div class="w-full mb-4">
                                <label class="block text-sm font-medium text-gray-700" for="password3">Preferred Pick-up Time <span class="text-red-500">*</span></label>
                                <input type="time" id="password3" name="pref_time" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                            </div>

                              <div class="w-full mb-4">
                                <label class="block text-sm font-medium text-gray-700" for="num-passengers">No. of Passengers <span class="text-red-500">*</span></label>
                                <select id="num-passengers" name="no_passengers" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required >
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                              </div>

                                <!-- Expected Time of Arrival at Destination -->
                                <div class="w-full mb-4">
                                    <label class="block text-sm font-medium text-gray-700" for="password3">Expected Time of Arrival at Destination <span class="text-red-500">*</span></label>
                                    <input type="time" id="password3" name="eta_destination" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                                </div>

                                  <!-- Requesting Official -->
                                  <div class="w-full mb-4">
                                      <label class="block text-sm font-medium text-gray-700" for="password3">Requesting Official <span class="text-red-500">*</span> </label>
                                      <input type="text" id="password3" name="req_official" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                                  </div>

                                    <!-- Name of Passengers -->
                                    <div class="w-full mb-4">
                                        <label class="block text-sm font-medium text-gray-700" for="password3">Name of Passengers <span class="text-red-500">*</span> </label>
                                        <input type="text" id="password3" name="name_passengers" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"required  />
                                    </div>                         
                  </div>
                                        <!-- Reason of Use -->
                                        <div class="w-full mb-4">
                                            <label class="block text-sm font-medium text-gray-700" for="password3">Reason of Use <span class="text-red-500">*</span></label>
                                            <input type="text" id="form2Example1" name="reason" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required  />
                                          </div>    

                                            <!-- Submit button -->
                                            <button  type="submit" name="ruv_submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4 w-60 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">Submit</button>
                                          </div>      
        </div> 
      </form>

      
<script>
// Populate the dropdown with numbers from 1 to 100
    const numPassengersSelect = document.getElementById('num-passengers');
    for (let i = 1; i <= 100; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        numPassengersSelect.appendChild(option);
    }
    // Get today's date
    var today = new Date();

    // Add 2 days
    today.setDate(today.getDate() + 2);

    // Format date to YYYY-MM-DD (required format for <input type="date">)
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    var yyyy = today.getFullYear();

    var formattedDate = yyyy + '-' + mm + '-' + dd;

    // Set the value of the date input field
    document.getElementById('date-of-trip').value = formattedDate;

    document.getElementById('date-of-trip').min = formattedDate;

    // script for mdb
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"
</script>
</div>


