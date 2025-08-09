<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$pins = $conn->query("SELECT * FROM pins WHERE user_id = $user_id");
$boards = $conn->query("SELECT * FROM boards WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Pinterest Clone</title>
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
        .profile-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-container h2 {
            color: #e60023;
        }
        .pin-grid, .board-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .pin-card, .board-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }
        .pin-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        @media (max-width: 600px) {
            .pin-grid, .board-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="pin_create.php">Create Pin</a>
        <a href="board_create.php">Create Board</a>
        <a href="#" onclick="logout()">Logout</a>
    </div>
    <div class="profile-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
        <h3>Your Pins</h3>
        <div class="pin-grid">
            <?php while ($pin = $pins->fetch_assoc()): ?>
                <div class="pin-card">
                    <a href="pin_view.php?id=<?php echo $pin['id']; ?>">
                        <img src="<?php echo $pin['image_url']; ?>" alt="<?php echo $pin['title']; ?>">
                        <p><?php echo htmlspecialchars($pin['title']); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        <h3>Your Boards</h3>
        <div class="board-grid">
            <?php while ($board = $boards->fetch_assoc()): ?>
                <div class="board-card">
                    <a href="board_view.php?id=<?php echo $board['id']; ?>">
                        <p><?php echo htmlspecialchars($board['name']); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
