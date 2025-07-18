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

        <!-- Filters -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🔍 Filters</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="stageFilter">Growth Stage:</label>
                    <select id="stageFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Stages</option>
                        <option value="Clone">🌿 Clone</option>
                        <option value="Veg">🌱 Veg</option>
                        <option value="Flower">🌸 Flower</option>
                        <option value="Mother">👑 Mother</option>
                    </select>
                </div>
                <div>
                    <label for="roomFilter">Room:</label>
                    <select id="roomFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Rooms</option>
                    </select>
                </div>
                <div>
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Status</option>
                        <option value="Growing">🌱 Growing</option>
                        <option value="Harvested">✂️ Harvested</option>
                        <option value="Destroyed">🗑️ Destroyed</option>
                        <option value="Sent">📦 Sent</option>
                    </select>
                </div>
                <div>
                    <label for="geneticsFilter">Genetics:</label>
                    <select id="geneticsFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Genetics</option>
                    </select>
                </div>
                <div>
                    <label for="ageFilter">Age Range:</label>
                    <select id="ageFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Ages</option>
                        <option value="0-30">0-30 days</option>
                        <option value="31-60">31-60 days</option>
                        <option value="61-90">61-90 days</option>
                        <option value="90+">90+ days</option>
                    </select>
                </div>
                <div>
                    <label for="motherFilter">Mother Plants:</label>
                    <select id="motherFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Plants</option>
                        <option value="1">👑 Mother Plants Only</option>
                        <option value="0">🌱 Regular Plants Only</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button onclick="clearFilters()" class="modern-btn secondary">🔄 Clear Filters</button>
                <span id="filterCount" style="padding: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;"></span>
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
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Stage</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Age</th>
                            <th>Days in Stage</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody id="plantsTableBody">
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Plants Found</h3>
                <p>No plants match your current filter criteria</p>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="button" class="modern-btn" id="processSelectedButton">
                    <span id="processButtonText">Process Selected</span>
                </button>
                <div style="margin-top: 1rem;">
                    <span id="selectionCount" style="color: var(--text-secondary); font-size: 0.9rem;">0 plants selected</span>
                </div>
            </div>
        </div>
    </main>

    <script src="js/growcart.js"></script>
    <script>
        let allPlants = [];
        let filteredPlants = [];
        
        const plantsTableBody = document.getElementById('plantsTableBody');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const processSelectedButton = document.getElementById('processSelectedButton');
        const actionDropdown = document.getElementById('action');
        const companySelection = document.getElementById('companySelection');
        const companyDropdown = document.getElementById('companyId');

        // Load all plants data
        function loadPlants() {
            fetch('get_plants_for_harvest.php')
                .then(response => response.json())
                .then(plantsData => {
                    allPlants = plantsData;
                    populateFilters();
                    applyFilters();
                })
                .catch(error => {
                    console.error('Error fetching plant data:', error);
                    showStatusMessage('Error loading plants', 'error');
                });
        }

        // Populate filter dropdowns
        function populateFilters() {
            // Populate room filter
            const rooms = [...new Set(allPlants.map(p => p.room_name).filter(r => r))];
            const roomFilter = document.getElementById('roomFilter');
            roomFilter.innerHTML = '<option value="">All Rooms</option>';
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomFilter.appendChild(option);
            });

            // Populate genetics filter
            const genetics = [...new Set(allPlants.map(p => p.geneticsName).filter(g => g))];
            const geneticsFilter = document.getElementById('geneticsFilter');
            geneticsFilter.innerHTML = '<option value="">All Genetics</option>';
            genetics.forEach(genetic => {
                const option = document.createElement('option');
                option.value = genetic;
                option.textContent = genetic;
                geneticsFilter.appendChild(option);
            });
        }

        // Apply filters to plant list
        function applyFilters() {
            const stageFilter = document.getElementById('stageFilter').value;
            const roomFilter = document.getElementById('roomFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const geneticsFilter = document.getElementById('geneticsFilter').value;
            const ageFilter = document.getElementById('ageFilter').value;
            const motherFilter = document.getElementById('motherFilter').value;

            filteredPlants = allPlants.filter(plant => {
                let ageMatch = true;
                if (ageFilter) {
                    const age = parseInt(plant.age);
                    switch(ageFilter) {
                        case '0-30': ageMatch = age <= 30; break;
                        case '31-60': ageMatch = age >= 31 && age <= 60; break;
                        case '61-90': ageMatch = age >= 61 && age <= 90; break;
                        case '90+': ageMatch = age > 90; break;
                    }
                }

                return (!stageFilter || plant.growth_stage === stageFilter) &&
                       (!roomFilter || plant.room_name === roomFilter) &&
                       (!statusFilter || plant.status === statusFilter) &&
                       (!geneticsFilter || plant.geneticsName === geneticsFilter) &&
                       (!motherFilter || plant.is_mother == motherFilter) &&
                       ageMatch;
            });

            displayPlants();
            updateFilterCount();
        }

        // Display filtered plants in table
        function displayPlants() {
            const tbody = plantsTableBody;
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';
            selectAllCheckbox.checked = false;

            if (filteredPlants.length === 0) {
                noDataMessage.style.display = 'block';
                updateSelectionCount();
                return;
            }

            noDataMessage.style.display = 'none';

            filteredPlants.forEach(plant => {
                const row = tbody.insertRow();

                // Checkbox
                const checkboxCell = row.insertCell();
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'selectedPlants[]';
                checkbox.value = plant.id;
                checkbox.addEventListener('change', updateSelectionCount);
                checkboxCell.appendChild(checkbox);

                // Plant data
                row.insertCell().innerHTML = `<strong>${plant.tracking_number || 'N/A'}</strong>`;
                row.insertCell().textContent = plant.plant_tag || '-';
                row.insertCell().textContent = plant.geneticsName || 'Unknown';
                row.insertCell().innerHTML = `<span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span>`;
                row.insertCell().textContent = plant.room_name || 'Unassigned';
                row.insertCell().innerHTML = `<span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span>`;
                row.insertCell().textContent = `${plant.age} days`;
                row.insertCell().textContent = `${plant.days_in_stage} days`;
                row.insertCell().innerHTML = plant.is_mother == 1 ? '<span style="color: var(--accent-primary);">👑 Mother</span>' : '🌱 Regular';
            });

            updateSelectionCount();
        }

        // Update filter count display
        function updateFilterCount() {
            const filterCount = document.getElementById('filterCount');
            filterCount.textContent = `Showing ${filteredPlants.length} of ${allPlants.length} plants`;
        }

        // Update selection count
        function updateSelectionCount() {
            const selectedCheckboxes = plantsTableBody.querySelectorAll('input[type="checkbox"]:checked');
            const selectionCount = document.getElementById('selectionCount');
            const count = selectedCheckboxes.length;
            selectionCount.textContent = `${count} plant${count !== 1 ? 's' : ''} selected`;
            
            // Enable/disable process button
            processSelectedButton.disabled = count === 0;
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('stageFilter').value = '';
            document.getElementById('roomFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('geneticsFilter').value = '';
            document.getElementById('ageFilter').value = '';
            document.getElementById('motherFilter').value = '';
            applyFilters();
        }

        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', () => {
            const checkboxes = plantsTableBody.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
            updateSelectionCount();
        });

        // Handle "Process Selected" button click
        processSelectedButton.addEventListener('click', () => {
            const selectedCheckboxes = plantsTableBody.querySelectorAll('input[type="checkbox"]:checked');
            const selectedPlantIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            const selectedAction = actionDropdown.value;

            if (selectedPlantIds.length === 0) {
                showStatusMessage('Please select at least one plant to process.', 'error');
                return;
            }

            if (selectedAction === 'send' && !companyDropdown.value) {
                showStatusMessage('Please select a company for external sending.', 'error');
                return;
            }

            // Confirmation dialog
            const actionText = selectedAction === 'harvest' ? 'harvest' : 
                              selectedAction === 'destroy' ? 'destroy' : 'send';
            const confirmMessage = `Are you sure you want to ${actionText} ${selectedPlantIds.length} plant${selectedPlantIds.length !== 1 ? 's' : ''}?`;
            
            if (!confirm(confirmMessage)) {
                return;
            }

            showStatusMessage(`Processing ${selectedPlantIds.length} plants...`, 'info');

            // Send selected plant IDs, action, and company (if applicable) to the server
            fetch('handle_harvest_plants.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    selectedPlants: selectedPlantIds, 
                    action: selectedAction, 
                    companyId: companyDropdown.value 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusMessage(data.message, 'success');
                    setTimeout(() => {
                        loadPlants(); // Reload the plant data
                    }, 1500);
                } else {
                    console.error('Error from server:', data.message);
                    showStatusMessage('An error occurred: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error during fetch or processing response:', error);
                showStatusMessage('An error occurred. Please check the console for details.', 'error');
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

        // Event listeners for filters
        document.getElementById('stageFilter').addEventListener('change', applyFilters);
        document.getElementById('roomFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('geneticsFilter').addEventListener('change', applyFilters);
        document.getElementById('ageFilter').addEventListener('change', applyFilters);
        document.getElementById('motherFilter').addEventListener('change', applyFilters);

        // Initialize page
        loadPlants();

        // Check for success/error messages
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage) {
            showStatusMessage(successMessage, 'success');
        } else if (errorMessage) {
            showStatusMessage(errorMessage, 'error');
        }
    </script>
</body>
</html>
