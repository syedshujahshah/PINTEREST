<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO boards (user_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $name);
    if ($stmt->execute()) {
        echo "<script>alert('Board created!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error creating board.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Board - Pinterest Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            color: #e60023;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #e60023;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #c1001b;
        }
        @media (max-width: 600px) {
            .form-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create a Board</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Board Name" required>
            <button type="submit">Create Board</button>
        </form>
    </div>
</body>
</html>
