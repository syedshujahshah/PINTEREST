<?php
session_start();
include 'db.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT pins.*, users.username FROM pins JOIN users ON pins.user_id = users.id WHERE 1=1";
if ($search) {
    $query .= " AND pins.title LIKE '%$search%'";
}
if ($category) {
    $query .= " AND pins.category = '$category'";
}
$pins = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Pinterest Clone</title>
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
        .search-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .search-container form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-container input, .search-container select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px;
            background-color: #e60023;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #c1001b;
        }
        .pin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .pin-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .pin-card img {
            width: 100%;
            height: 150px;
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
            .search-container form {
                flex-direction: column;
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
    <div class="search-container">
        <form method="GET">
            <input type="text" name="q" placeholder="Search pins..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="category">
                <option value="">All Categories</option>
                <option value="Fashion" <?php if ($category == 'Fashion') echo 'selected'; ?>>Fashion</option>
                <option value="Art" <?php if ($category == 'Art') echo 'selected'; ?>>Art</option>
                <option value="Food" <?php if ($category == 'Food') echo 'selected'; ?>>Food</option>
                <option value="Travel" <?php if ($category == 'Travel') echo 'selected'; ?>>Travel</option>
                <option value="DIY" <?php if ($category == 'DIY') echo 'selected'; ?>>DIY</option>
            </select>
            <button type="submit">Search</button>
        </form>
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
    </div>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
