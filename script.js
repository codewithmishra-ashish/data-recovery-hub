
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