<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Recovery Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
</head>
<body>
    <div class="navbar" id="navbar">
        <img src="logo.png" alt="Logo">
        <h1>Data Recovery Hub</h1>
        <div class="search-container" id="searchContainer">
            <input type="text" class="search-bar" id="searchBar" placeholder="Search tools..." onkeyup="searchTools(this.value)">
            <button class="back-btn" id="backBtn" onclick="closeModal()">Back</button>
            <div class="search-results" id="searchResults"></div>
        </div>
    </div>

    <section class="hero">
        <div class="hero-content">
            <h2>Recover Your Lost Data</h2>
            <p class="hero-subtitle">Accidentally deleted a file? Don’t panic! Explore our free, open-source tools for all your devices.</p>
            <p class="hero-why"><strong>Why it matters:</strong> Lost data can disrupt your life—photos, documents, or critical files. We make recovery simple and accessible.</p>
            <a href="#tools" class="get-started-btn">Get Started</a>
        </div>
    </section>

    <div class="tools-section" id="tools">
        <h2>Featured Tools</h2>
        <div class="tools-list" id="toolsList">
            <?php
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=data_recovery", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->query("SELECT id, name, platforms, features, link, video, ss, description AS `desc`, steps FROM tools");
                $tools = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($tools as &$tool) {
                    $tool['platforms'] = explode(",", $tool['platforms']);
                }

                foreach ($tools as $tool) {
                    echo "<div class='tool-card' data-tool-id='{$tool['id']}'>";
                    echo "<h3>" . htmlspecialchars($tool['name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($tool['features']) . "</p>";
                    echo "</div>";
                }
            } catch (PDOException $e) {
                echo "<p>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
                $tools = [];
            }
            ?>
        </div>
    </div>

    <div class="devices-section" id="devices">
        <h2>Supported Devices</h2>
        <?php
        $devices = ["Windows", "Android", "Hardisk", "Apple"];
        foreach ($devices as $device) {
            echo "<div class='device-item' data-device='$device'>";
            echo "<div class='device-header'>";
            echo "<span>$device</span>";
            echo "<span class='toggle-btn'>+</span>";
            echo "</div>";
            echo "<div class='device-tools'>";
            foreach ($tools as $tool) {
                if (in_array($device, $tool['platforms'])) {
                    echo "<p class='tool-item' data-tool-id='{$tool['id']}'>" . htmlspecialchars($tool['name']) . "</p>";
                }
            }
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <div class="modal" id="toolModal">
        <div class="modal-content" id="modalContent"></div>
    </div>

    <div class="add-tool-form">
        <h2>Add a New Tool</h2>
        <form method="POST" action="">
            <input type="text" name="toolName" id="toolName" placeholder="Tool Name" required>
            <input type="text" name="toolPlatform" id="toolPlatform" placeholder="Platform (e.g., Windows, Android)" required>
            <input type="text" name="toolLink" id="toolLink" placeholder="Download Link" required>
            <textarea name="toolFeatures" id="toolFeatures" placeholder="Features" required></textarea>
            <button type="submit">Submit</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newTool = [
                "name" => $_POST['toolName'],
                "platforms" => $_POST['toolPlatform'],
                "features" => $_POST['toolFeatures'],
                "link" => $_POST['toolLink'],
                "video" => "https://www.youtube.com/embed/placeholder",
                "ss" => "https://via.placeholder.com/300x200",
                "desc" => "User-added tool",
                "steps" => "User-defined steps"
            ];
            $stmt = $pdo->prepare("INSERT INTO tools (name, platforms, features, link, video, ss, description, steps) VALUES (:name, :platforms, :features, :link, :video, :ss, :desc, :steps)");
            $stmt->execute($newTool);
            echo "<script>alert('Tool added successfully!'); window.location.reload();</script>";
        }
        ?>
    </div>

    <div class="footer">
        <img src="logo.png" alt="Logo">
        <p>Data Recovery Hub</p>
        <p>
            <a href="#devices" class="footer-link" data-device="Windows">Windows</a> | 
            <a href="#devices" class="footer-link" data-device="Android">Android</a> | 
            <a href="#devices" class="footer-link" data-device="Hardisk">Hardisk</a> | 
            <a href="#devices" class="footer-link" data-device="Apple">Apple</a>
        </p>
        <p>All Rights Reserved © 2025</p>
    </div>

    <script>
        const tools = <?php echo json_encode($tools); ?>;
        const modal = document.getElementById("toolModal");
        const modalContent = document.getElementById("modalContent");
        const searchBar = document.getElementById("searchBar");
        const backBtn = document.getElementById("backBtn");

        function showToolModal(id) {
            console.log("Tool clicked, ID:", id);
            const tool = tools.find(t => t.id == id);
            if (!tool) {
                console.error("Tool not found with ID:", id);
                return;
            }

            modalContent.innerHTML = `
                <h2>${tool.name}</h2>
                <p><strong>Platforms:</strong> ${tool.platforms.join(", ")}</p>
                <p><strong>Features:</strong> ${tool.features}</p>
                <p><strong>Description:</strong> ${tool.desc}</p>
                <p><strong>Steps:</strong> ${tool.steps.replace(/\n/g, "<br>")}</p>
                <iframe width="560" height="315" src="${tool.video}" frameborder="0" allowfullscreen></iframe>
                <p><img src="${tool.ss}" alt="Screenshot"></p>
                <a href="${tool.link}" target="_blank">Download Here</a>
            `;

            console.log("Showing modal for:", tool.name);
            modal.style.display = "block";
            searchBar.style.display = "none";
            backBtn.style.display = "inline-block";
        }

        function closeModal() {
            console.log("Closing modal");
            modal.style.display = "none";
            searchBar.style.display = "inline-block";
            backBtn.style.display = "none";
            document.getElementById("searchResults").style.display = "none";
        }

        function searchTools(query) {
            const results = document.getElementById("searchResults");
            if (!results || backBtn.style.display === "inline-block") return;
            results.style.display = query ? "block" : "none";
            results.innerHTML = "";
            tools.forEach(tool => {
                if (tool.name.toLowerCase().includes(query.toLowerCase())) {
                    const div = document.createElement("div");
                    div.innerHTML = tool.name;
                    div.addEventListener("click", () => showToolModal(tool.id));
                    results.appendChild(div);
                }
            });
        }

        function toggleDeviceTools(header) {
            const toolsDiv = header.nextElementSibling;
            const toggleBtn = header.querySelector('.toggle-btn');
            const allToolsDivs = document.querySelectorAll('.device-tools');
            const allToggleBtns = document.querySelectorAll('.toggle-btn');

            allToolsDivs.forEach(div => {
                if (div !== toolsDiv) div.style.display = "none";
            });
            allToggleBtns.forEach(btn => {
                if (btn !== toggleBtn) {
                    btn.textContent = "+";
                    btn.classList.remove('active');
                }
            });

            if (toolsDiv.style.display === "block") {
                toolsDiv.style.display = "none";
                toggleBtn.textContent = "+";
                toggleBtn.classList.remove('active');
            } else {
                toolsDiv.style.display = "block";
                toggleBtn.textContent = "-";
                toggleBtn.classList.add('active');
            }
        }

        function openDeviceDropdown(deviceName) {
            const deviceItem = document.querySelector(`.device-item[data-device="${deviceName}"]`);
            if (deviceItem) {
                const header = deviceItem.querySelector('.device-header');
                const toolsDiv = deviceItem.querySelector('.device-tools');
                const toggleBtn = header.querySelector('.toggle-btn');
                
                const allToolsDivs = document.querySelectorAll('.device-tools');
                const allToggleBtns = document.querySelectorAll('.toggle-btn');
                allToolsDivs.forEach(div => {
                    if (div !== toolsDiv) div.style.display = "none";
                });
                allToggleBtns.forEach(btn => {
                    if (btn !== toggleBtn) {
                        btn.textContent = "+";
                        btn.classList.remove('active');
                    }
                });

                toolsDiv.style.display = "block";
                toggleBtn.textContent = "-";
                toggleBtn.classList.add('active');
                document.getElementById('devices').scrollIntoView({ behavior: 'smooth' });
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.tool-card').forEach(card => {
                card.addEventListener("click", () => {
                    const id = card.getAttribute('data-tool-id');
                    showToolModal(id);
                });
            });

            document.querySelectorAll('.device-tools p').forEach(item => {
                item.addEventListener("click", (e) => {
                    e.stopPropagation();
                    const id = item.getAttribute('data-tool-id');
                    showToolModal(id);
                });
            });

            document.querySelectorAll('.device-header').forEach(header => {
                header.addEventListener("click", () => toggleDeviceTools(header));
            });

            document.querySelectorAll('.footer-link').forEach(link => {
                link.addEventListener("click", (e) => {
                    e.preventDefault();
                    const device = link.getAttribute('data-device');
                    openDeviceDropdown(device);
                });
            });
        });
    </script>
</body>
</html>