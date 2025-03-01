<?php
include 'config.php'; // Database connection

$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$query = ($category === 'All') ? "SELECT * FROM tools" : "SELECT * FROM tools WHERE category = ?";
$stmt = $conn->prepare($query);
if ($category !== 'All') {
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$result = $stmt->get_result();
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
        <h1>Data Recovery Hub</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="tools.php">Tools</a>
        </nav>
    </header>

    <section class="tools">
        <h2>Recovery Tools <?php echo $category === 'All' ? '' : "for $category"; ?></h2>
        <div class="tool-list">
            <?php while ($tool = $result->fetch_assoc()): ?>
                <div class="tool-card">
                    <h3><?php echo $tool['name']; ?></h3>
                    <p><strong>Description:</strong> <?php echo $tool['description']; ?></p>
                    <p><strong>Features:</strong> <?php echo $tool['features']; ?></p>
                    <p><strong>Download:</strong> <a href="<?php echo $tool['download_link']; ?>" target="_blank">Get it here</a></p>
                    <?php if ($tool['video_url']): ?>
                        <iframe width="300" height="200" src="<?php echo $tool['video_url']; ?>" frameborder="0" allowfullscreen></iframe>
                    <?php endif; ?>
                    <p><strong>How to Use:</strong> <?php echo $tool['documentation']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Data Recovery Hub</p>
    </footer>

    <script src="script.js"></script>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>

<?php $stmt->close(); $conn->close(); ?>