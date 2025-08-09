<?php
session_start();
include 'db.php';

$pins = $conn->query("SELECT pins.*, users.username FROM pins JOIN users ON pins.user_id = users.id ORDER BY pins.created_at DESC LIMIT 12");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinterest Clone - Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f0f0;
        }
        .navbar {
            background-color: #e60023;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .pin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .pin-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .pin-card:hover {
            transform: scale(1.05);
        }
        .pin-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .pin-card h3 {
            margin: 10px;
            font-size: 16px;
        }
        .pin-card p {
            margin: 0 10px 10px;
            color: #555;
        }
        @media (max-width: 600px) {
            .pin-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            .pin-card img {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="index.php">Pinterest Clone</a>
            <a href="search.php">Search</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="pin_create.php">Create Pin</a>
                <a href="board_create.php">Create Board</a>
                <a href="profile.php">Profile</a>
                <a href="#" onclick="logout()">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="pin-grid">
        <?php while ($pin = $pins->fetch_assoc()): ?>
            <div class="pin-card">
                <a href="pin_view.php?id=<?php echo $pin['id']; ?>">
                    <img src="<?php echo $pin['image_url']; ?>" alt="<?php echo $pin['title']; ?>">
                    <h3><?php echo htmlspecialchars($pin['title']); ?></h3>
                    <p>By <?php echo htmlspecialchars($pin['username']); ?></p>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
