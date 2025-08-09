<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

$pin_id = $_GET['id'];
$pin = $conn->query("SELECT pins.*, users.username FROM pins JOIN users ON pins.user_id = users.id WHERE pins.id = $pin_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $board_id = $_POST['board_id'];
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO board_pins (board_id, pin_id, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $board_id, $pin_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Pin saved to board!');</script>";
    } else {
        echo "<script>alert('Error saving pin.');</script>";
    }
}

$boards = isset($_SESSION['user_id']) ? $conn->query("SELECT * FROM boards WHERE user_id = " . $_SESSION['user_id']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pin - Pinterest Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f0f0;
        }
        .navbar {
            background-color: #e60023;
            padding: 10px;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .pin-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .pin-container img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
        }
        .pin-container h2 {
            color: #e60023;
        }
        .pin-container select, .pin-container button {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .pin-container button {
            background-color: #e60023;
            color: white;
            border: none;
            cursor: pointer;
        }
        .pin-container button:hover {
            background-color: #c1001b;
        }
        @media (max-width: 600px) {
            .pin-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="pin_create.php">Create Pin</a>
        <a href="board_create.php">Create Board</a>
        <a href="profile.php">Profile</a>
        <a href="#" onclick="logout()">Logout</a>
    </div>
    <div class="pin-container">
        <h2><?php echo htmlspecialchars($pin['title']); ?></h2>
        <p>By <?php echo htmlspecialchars($pin['username']); ?> | Category: <?php echo htmlspecialchars($pin['category']); ?></p>
        <img src="<?php echo $pin['image_url']; ?>" alt="<?php echo $pin['title']; ?>">
        <p><?php echo htmlspecialchars($pin['description']); ?></p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST">
                <select name="board_id" required>
                    <option value="">Select a Board</option>
                    <?php while ($board = $boards->fetch_assoc()): ?>
                        <option value="<?php echo $board['id']; ?>"><?php echo htmlspecialchars($board['name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Save to Board</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
