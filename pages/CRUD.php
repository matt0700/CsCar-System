<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $special_password = password_hash($_POST['special_password'], PASSWORD_DEFAULT);
        $driver_name = $_POST['driver_name'];
        $driver_cellno = $_POST['driver_cellno'];
        $email = $_POST['email'];
        $driver_status = $_POST['driver_status'];

        $sql = "INSERT INTO drivers (username, password, special_password, driver_name, driver_cellno, email, driver_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sssssss", $username, $password, $special_password, $driver_name, $driver_cellno, $email, $driver_status);
        
        if ($stmt->execute()) {
            $message = "New record created successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        $driver_id = $_POST['driver_id'];
        $username = $_POST['username'];
        $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $special_password = isset($_POST['special_password']) ? password_hash($_POST['special_password'], PASSWORD_DEFAULT) : null;
        $driver_name = $_POST['driver_name'];
        $driver_cellno = $_POST['driver_cellno'];
        $email = $_POST['email'];

        $sql = "UPDATE drivers SET ";
        $params = [];
        $types = "";

        if (!empty($username)) {
            $sql .= "username=?, ";
            $params[] = $username;
            $types .= "s";
        }
        if (!empty($password)) {
            $sql .= "password=?, ";
            $params[] = $password;
            $types .= "s";
        }
        if (!empty($special_password)) {
            $sql .= "special_password=?, ";
            $params[] = $special_password;
            $types .= "s";
        }
        if (!empty($driver_name)) {
            $sql .= "driver_name=?, ";
            $params[] = $driver_name;
            $types .= "s";
        }
        if (!empty($driver_cellno)) {
            $sql .= "driver_cellno=?, ";
            $params[] = $driver_cellno;
            $types .= "s";
        }
        if (!empty($email)) {
            $sql .= "email=?, ";
            $params[] = $email;
            $types .= "s";
        }

        $sql = rtrim($sql, ", ") . " WHERE driver_id=?";
        $params[] = $driver_id;
        $types .= "i";
        
        $stmt = $connect->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $message = "Record updated successfully";
        } else {
            $message = "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $driver_id = $_POST['driver_id'];

        $sql = "DELETE FROM drivers WHERE driver_id=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $driver_id);
        
        if ($stmt->execute()) {
            $message = "Record deleted successfully";
        } else {
            $message = "Error deleting record: " . $stmt->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM drivers";
$result = $connect->query($sql);

$drivers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $drivers[] = $row;
    }
}

$connect->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver CRUD Operations</title>
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
                <h1 class="h3 ml-56">Driver CRUD Operations</h1>
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
                    <h2 class="h4">Create New Driver</h2>
                    <form method="post" action="" class="bg-white p-4 rounded shadow-sm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="special_password" class="form-label">Special Password</label>
                            <input type="password" name="special_password" id="special_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="driver_name" class="form-label">Driver Name</label>
                            <input type="text" name="driver_name" id="driver_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="driver_cellno" class="form-label">Cell No</label>
                            <input type="text" name="driver_cellno" id="driver_cellno" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label for="driver_status" class="form-label">Status</label>
                        <select name="driver_status" id="driver_status" class="form-select">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                        <button type="submit" name="create" class="btn btn-primary">Create</button>
                    </form>
                </div>
                <div class="tab-pane fade ml-56" id="update" role="tabpanel" aria-labelledby="update-tab">
                    <h2 class="h4">Update Driver</h2>
                    <form method="post" action="" class="bg-white p-4 rounded shadow-sm">
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Driver ID</label>
                            <input type="text" name="driver_id" id="driver_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username (leave blank if no change)</label>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (leave blank if no change)</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="special_password" class="form-label">Special Password (leave blank if no change)</label>
                            <input type="password" name="special_password" id="special_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="driver_name" class="form-label">Driver Name (leave blank if no change)</label>
                            <input type="text" name="driver_name" id="driver_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="driver_cellno" class="form-label">Cell No (leave blank if no change)</label>
                            <input type="text" name="driver_cellno" id="driver_cellno" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (leave blank if no change)</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <div class="tab-pane fade ml-56" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                    <h2 class="h4">Delete Driver</h2>
                    <form method="post" action="" class="bg-white p-4 rounded shadow-sm">
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Driver ID</label>
                            <input type="text" name="driver_id" id="driver_id" class="form-control" required>
                        </div>
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>

            <h2 class="h4 mt-4 ml-56">Driver List</h2>
            <div class="table-responsive overflow-x-auto ml-56">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Driver ID</th>
                            <th>Username</th>
                            <th>Driver Name</th>
                            <th>Cell No</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drivers as $driver): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($driver['driver_id']); ?></td>
                                <td><?php echo htmlspecialchars($driver['username']); ?></td>
                                <td><?php echo htmlspecialchars($driver['driver_name']); ?></td>
                                <td><?php echo htmlspecialchars($driver['driver_cellno']); ?></td>
                                <td><?php echo htmlspecialchars($driver['email']); ?></td>
                                <td><?php echo htmlspecialchars($driver['driver_status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
