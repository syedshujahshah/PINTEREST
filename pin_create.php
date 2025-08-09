<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO pins (user_id, title, description, category, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $description, $category, $target_file);
        if ($stmt->execute()) {
            echo "<script>alert('Pin created!'); window.location.href = 'profile.php';</script>";
        } else {
            echo "<script>alert('Error creating pin.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Pin - Pinterest Clone</title>
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
        .form-container input, .form-container select, .form-container textarea {
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
        <h2>Create a Pin</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <select name="category" required>
                <option value="Fashion">Fashion</option>
                <option value="Art">Art</option>
                <option value="Food">Food</option>
                <option value="Travel">Travel</option>
                <option value="DIY">DIY</option>
            </select>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Create Pin</button>
        </form>
    </div>
</body>
</html>
