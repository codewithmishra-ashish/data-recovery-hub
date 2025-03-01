<?php
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$query = ($category === 'All') ? "SELECT * FROM tools" : "SELECT * FROM tools WHERE category = ?";
$stmt = $conn->prepare($query);
if ($category !== 'All') {
    $stmt->bind_param("s", $category);
}
if ($stmt->execute()) {
    $result = $stmt->get_result();
} else {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools - Data Recovery Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Data Recovery Hub</h1>
            </div>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="tools.php">Tools</a></li>
            </ul>
        </nav>
    </header>

    <section class="tools">
        <h2>Recovery Tools <?php echo $category === 'All' ? '' : "for $category"; ?></h2>
        <div class="tool-list">
            <?php while ($tool = $result->fetch_assoc()): ?>
                <div class="tool-card">
                    <h3><?php echo htmlspecialchars($tool['name']); ?></h3>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($tool['description']); ?></p>
                    <p><strong>Features:</strong> <?php echo htmlspecialchars($tool['features']); ?></p>
                    <p><strong>Download:</strong> <a href="<?php echo htmlspecialchars($tool['download_link']); ?>" target="_blank" class="download-btn">Get it here</a></p>
                    <?php if ($tool['video_url']): ?>
                        <iframe width="100%" height="200" src="<?php echo htmlspecialchars($tool['video_url']); ?>" frameborder="0" allowfullscreen></iframe>
                    <?php endif; ?>
                    <p><strong>How to Use:</strong> <?php echo htmlspecialchars($tool['documentation']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Data Recovery Hub</h3>
                <p>Making data recovery simple and free since 2025.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="tools.php">Tools</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: support@datarecoveryhub.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Data Recovery Hub. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>