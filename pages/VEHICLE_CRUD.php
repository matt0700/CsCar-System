<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        $plate_no = $_POST['plate_no'];
        $model = $_POST['model'];
        $type = $_POST['type'];
        $make_series_type = $_POST['make_series_type'];
        $seater = $_POST['seater'];
        $mileage = $_POST['mileage'];
        $car_status = $_POST['car_status'];

        $sql = "INSERT INTO vehicle_data (plate_no, model, type, make_series_type, seater, mileage, car_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sssssis", $plate_no, $model, $type, $make_series_type, $seater, $mileage, $car_status);
        
        if ($stmt->execute()) {
            $message = "New vehicle record created successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        $plate_no = $_POST['plate_no'];
        $model = $_POST['model'];
        $type = $_POST['type'];
        $make_series_type = $_POST['make_series_type'];
        $seater = $_POST['seater'];
        $mileage = $_POST['mileage'];

        $fields = [];
        $params = [];

        if (!empty($model)) {
            $fields[] = "model=?";
            $params[] = $model;
        }
        if (!empty($type)) {
            $fields[] = "type=?";
            $params[] = $type;
        }
        if (!empty($make_series_type)) {
            $fields[] = "make_series_type=?";
            $params[] = $make_series_type;
        }
        if (!empty($seater)) {
            $fields[] = "seater=?";
            $params[] = $seater;
        }
        if (!empty($mileage)) {
            $fields[] = "mileage=?";
            $params[] = $mileage;
        }

        if (!empty($fields)) {
            $params[] = $plate_no; // Add plate_no at the end for the WHERE clause
            $sql = "UPDATE vehicle_data SET " . implode(", ", $fields) . " WHERE plate_no=?";
            $stmt = $connect->prepare($sql);

            $stmt->bind_param(str_repeat("s", count($fields)) . "s", ...$params);
            
            if ($stmt->execute()) {
                $message = "Record updated successfully";
            } else {
                $message = "Error updating record: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "No fields to update";
        }
    } elseif (isset($_POST['delete'])) {
        $plate_no = $_POST['plate_no'];

        $sql = "DELETE FROM vehicle_data WHERE plate_no=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $plate_no);
        
        if ($stmt->execute()) {
            $message = "Record deleted successfully";
        } else {
            $message = "Error deleting record: " . $stmt->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM vehicle_data";
$result = $connect->query($sql);

$vehicles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}

$connect->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vehicle CRUD Operations</title>
<?php include '../snippets/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../assets/global.css">
<link rel="stylesheet" href="../assets/home.css">
<style>
.modal-body p {
    margin-bottom: 5px;
}

.table thead th {
    background-color: #343a40;
    color: #fff;
}

.table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

@media (max-width: 992px) {
    .overflow-x-auto {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 0.5rem;
    }
    
    * {
        margin: 0px !important;
    }
}
</style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <header class="bg-dark text-white py-3 ">
        <div class="container">
            <h1 class="h3 ml-56">Vehicle CRUD Operations</h1>
        </div>
    </header>

    <div class="container">
        <?php if (isset($message)): ?>
        <div class="alert alert-success ml-56" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs ml-56" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="create-tab" data-bs-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="true">Create</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="update-tab" data-bs-toggle="tab" href="#update" role="tab" aria-controls="update" aria-selected="false">Update</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="delete-tab" data-bs-toggle="tab" href="#delete" role="tab" aria-controls="delete" aria-selected="false">Delete</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-4" id="myTabContent">
            <div class="tab-pane fade show active ml-56" id="create" role="tabpanel" aria-labelledby="create-tab">
                <h2 class="h4">Create New Vehicle</h2>
                <form method="post" action="" class="bg-white p-4 rounded shadow-sm">
                    <div class="mb-3">
                        <label for="plate_no" class="form-label">Plate No</label>
                        <input type="text" name="plate_no" id="plate_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" name="model" id="model" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" name="type" id="type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="make_series_type" class="form-label">Make Series Type</label>
                        <input type="text" name="make_series_type" id="make_series_type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="seater" class="form-label">Seater</label>
                        <input type="number" name="seater" id="seater" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mileage" class="form-label">Mileage</label>
                        <input type="number" name="mileage" id="mileage" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="car_status" class="form-label">Car Status</label>
                        <select name="car_status" id="car_status" class="form-select" required>
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                    <button type="submit" name="create" class="btn btn-success">Create</button>
                </form>
            </div>

            <div class="tab-pane fade ml-56" id="update" role="tabpanel" aria-labelledby="update-tab">
                <h2 class="h4">Update Vehicle</h2>
                <form method="post" action="" class="bg-white p-4 rounded shadow-sm">
                    <div class="mb-3">
                        <label for="plate_no_update" class="form-label">Plate No (leave blank if no change)</label>
                        <input type="text" name="plate_no" id="plate_no_update" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="model_update" class="form-label">Model (leave blank if no change) </label>
                        <input type="text" name="model" id="model_update" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="type_update" class="form-label">Type (leave blank if no change)</label>
                        <input type="text" name="type" id="type_update" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="make_series_type_update" class="form-label">Make Series Type (leave blank if no change)</label>
                        <input type="text" name="make_series_type" id="make_series_type_update" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="seater_update" class="form-label">Seater (leave blank if no change)</label>
                        <input type="number" name="seater" id="seater_update" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="mileage_update" class="form-label">Mileage (leave blank if no change)</label>
                        <input type="number" name="mileage" id="mileage_update" class="form-control">
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>

            <div class="tab-pane fade ml-56" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                <h2 class="h4">Delete Vehicle</h2>
                <form method="post" action="" class="bg-white p-4 rounded shadow-sm">
                    <div class="mb-3">
                        <label for="plate_no_delete" class="form-label">Plate No</label>
                        <input type="text" name="plate_no" id="plate_no_delete" class="form-control" required>
                    </div>
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>

        <h2 class="h4 mt-5 ml-56">All Vehicles</h2>
        <div class="overflow-x-auto ml-56">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Plate No</th>
                        <th>Model</th>
                        <th>Type</th>
                        <th>Make Series Type</th>
                        <th>Seater</th>
                        <th>Mileage</th>
                        <th>Car Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vehicle['plate_no']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['model']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['type']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['make_series_type']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['seater']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['mileage']); ?></td>
                        <td><?php echo htmlspecialchars($vehicle['car_status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
