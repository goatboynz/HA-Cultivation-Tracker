<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css">
    <link rel="stylesheet" href="css/modern-theme.css">
    <title>CultivationTracker - Harvest/Destroy/Send Plants</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>✂️ Harvest/Destroy/Send Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Process plants for harvest, destruction, or external transfer</p>
            </div>
        </div>

        <!-- Action Selection -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🎯 Select Action</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="action">Action:</label>
                    <select id="action" name="action" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" required>
                        <option value="harvest">✂️ Harvest</option>
                        <option value="destroy">🗑️ Destroy</option>
                        <option value="send">📦 Send External</option>
                    </select>
                </div>
                
                <div id="companySelection" style="display: none;">
                    <label for="companyId">Company:</label>
                    <select id="companyId" name="companyId" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="" disabled selected>Select Company</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Plants Table -->
        <div class="modern-card">
            <h3>🌿 Available Plants</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="plantsTable" class="modern-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllCheckbox"></th>
                            <th>Genetics Name</th>
                            <th>Age (Days)</th>
                            <th>Status</th>
                            <th>Room</th>
                            <th>Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="button" class="modern-btn" id="processSelectedButton">
                    <span id="processButtonText">Process Selected</span>
                </button>
            </div>
        </div>
    </main>

    <script src="js/growcart.js"></script>
    <script>
        const plantsTable = document.getElementById('plantsTable').getElementsByTagName('tbody')[0];
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const processSelectedButton = document.getElementById('processSelectedButton');
        const actionDropdown = document.getElementById('action');
        const companySelection = document.getElementById('companySelection');
        const companyDropdown = document.getElementById('companyId');

        // Fetch plant data from the server
        fetch('get_plants_for_harvest.php')
            .then(response => response.json())
            .then(plantsData => {
                plantsData.forEach(plant => {
                    const row = plantsTable.insertRow();

                    const checkboxCell = row.insertCell();
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'selectedPlants[]';
                    checkbox.value = plant.id;
                    checkboxCell.appendChild(checkbox);

                    const nameCell = row.insertCell();
                    const ageCell = row.insertCell();
                    const statusCell = row.insertCell();
                    const roomCell = row.insertCell();
                    const stageCell = row.insertCell();

                    nameCell.textContent = plant.geneticsName;
                    ageCell.textContent = plant.age;
                    statusCell.innerHTML = `<span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span>`;
                    roomCell.textContent = plant.room_name || 'Unassigned';
                    stageCell.innerHTML = `<span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span>`;
                });
            })
            .catch(error => console.error('Error fetching plant data:', error));

        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', () => {
            const checkboxes = plantsTable.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
        });

        // Handle "Process Selected" button click
        processSelectedButton.addEventListener('click', () => {
            const selectedCheckboxes = plantsTable.querySelectorAll('input[type="checkbox"]:checked');
            const selectedPlantIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            const selectedAction = actionDropdown.value;

            if (selectedPlantIds.length === 0) {
                alert('Please select at least one plant to process.');
                return;
            }

            if (selectedAction === 'send' && !companyDropdown.value) {
                alert('Please select a company for external sending.');
                return;
            }

            // Send selected plant IDs, action, and company (if applicable) to the server
            fetch('handle_harvest_plants.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ selectedPlants: selectedPlantIds, action: selectedAction, companyId: companyDropdown.value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    console.error('Error from server:', data.message);
                    alert('An error occurred: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error during fetch or processing response:', error);
                alert('An error occurred. Please check the console for details.');
            });
        });

        // Show/Hide company selection based on action
        actionDropdown.addEventListener('change', () => {
            companySelection.style.display = actionDropdown.value === 'send' ? 'block' : 'none';
            
            // Update button text based on action
            const buttonText = document.getElementById('processButtonText');
            switch(actionDropdown.value) {
                case 'harvest':
                    buttonText.textContent = '✂️ Harvest Selected';
                    break;
                case 'destroy':
                    buttonText.textContent = '🗑️ Destroy Selected';
                    break;
                case 'send':
                    buttonText.textContent = '📦 Send Selected';
                    break;
                default:
                    buttonText.textContent = 'Process Selected';
            }
        });

        function showStatusMessage(message, type) {
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = message;
            statusMessage.classList.add(type);
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
                statusMessage.classList.remove(type);
            }, 5000);
        }

        // Fetch and populate company dropdown
        fetch('get_companies.php')
            .then(response => response.json())
            .then(companies => {
                companies.forEach(company => {
                    const option = document.createElement('option');
                    option.value = company.id;
                    option.textContent = company.name;
                    companyDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching companies:', error));
    </script>
</body>
</html>
