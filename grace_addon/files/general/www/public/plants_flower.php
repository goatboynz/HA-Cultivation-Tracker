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
    <title>CultivationTracker - Flowering Stage Plants</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌸 Flowering Stage Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Individual flowering plant management and harvest tracking</p>
            </div>
            <button onclick="refreshData()" class="modern-btn secondary">🔄 Refresh</button>
        </div>

        <!-- Filters -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🔍 Filters</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="roomFilter">Room:</label>
                    <select id="roomFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Rooms</option>
                    </select>
                </div>
                <div>
                    <label for="geneticsFilter">Genetics:</label>
                    <select id="geneticsFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Genetics</option>
                    </select>
                </div>
                <div>
                    <label for="floweringTimeFilter">Flowering Time:</label>
                    <select id="floweringTimeFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Times</option>
                        <option value="0-30">0-30 days</option>
                        <option value="31-45">31-45 days</option>
                        <option value="46-60">46-60 days</option>
                        <option value="61-75">61-75 days</option>
                        <option value="75+">75+ days</option>
                    </select>
                </div>
                <div>
                    <label for="readyFilter">Harvest Ready:</label>
                    <select id="readyFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Plants</option>
                        <option value="ready">Ready to Harvest</option>
                        <option value="soon">Ready Soon (7 days)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Flower Plants Table -->
        <div class="modern-card">
            <h3>🌸 Your Flowering Plants</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="flowerTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Room</th>
                            <th>Started Flowering</th>
                            <th>Days in Flower</th>
                            <th>Expected Harvest</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="flowerTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Flowering Plants Found</h3>
                <p>Move some vegetative plants to flowering stage to see them here</p>
            </div>
        </div>
    </main>

    <script>
        let allFlowerPlants = [];
        let filteredFlowerPlants = [];

        function loadFlowerPlants() {
            fetch('get_individual_plants_by_stage.php?stage=Flower')
                .then(response => response.json())
                .then(plants => {
                    allFlowerPlants = plants;
                    populateFilters();
                    applyFilters();
                })
                .catch(error => console.error('Error loading flower plants:', error));
        }

        function populateFilters() {
            // Populate room filter
            const rooms = [...new Set(allFlowerPlants.map(p => p.room_name).filter(r => r))];
            const roomFilter = document.getElementById('roomFilter');
            roomFilter.innerHTML = '<option value="">All Rooms</option>';
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomFilter.appendChild(option);
            });

            // Populate genetics filter
            const genetics = [...new Set(allFlowerPlants.map(p => p.genetics_name).filter(g => g))];
            const geneticsFilter = document.getElementById('geneticsFilter');
            geneticsFilter.innerHTML = '<option value="">All Genetics</option>';
            genetics.forEach(genetic => {
                const option = document.createElement('option');
                option.value = genetic;
                option.textContent = genetic;
                geneticsFilter.appendChild(option);
            });
        }

        function applyFilters() {
            const roomFilter = document.getElementById('roomFilter').value;
            const geneticsFilter = document.getElementById('geneticsFilter').value;
            const floweringTimeFilter = document.getElementById('floweringTimeFilter').value;
            const readyFilter = document.getElementById('readyFilter').value;

            filteredFlowerPlants = allFlowerPlants.filter(plant => {
                const daysInFlower = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                
                let timeMatch = true;
                if (floweringTimeFilter) {
                    switch(floweringTimeFilter) {
                        case '0-30': timeMatch = daysInFlower <= 30; break;
                        case '31-45': timeMatch = daysInFlower >= 31 && daysInFlower <= 45; break;
                        case '46-60': timeMatch = daysInFlower >= 46 && daysInFlower <= 60; break;
                        case '61-75': timeMatch = daysInFlower >= 61 && daysInFlower <= 75; break;
                        case '75+': timeMatch = daysInFlower > 75; break;
                    }
                }
                
                let readyMatch = true;
                if (readyFilter) {
                    // Assume 63 days average flowering time if not specified
                    const expectedFloweringDays = plant.flowering_days || 63;
                    switch(readyFilter) {
                        case 'ready': readyMatch = daysInFlower >= expectedFloweringDays; break;
                        case 'soon': readyMatch = daysInFlower >= (expectedFloweringDays - 7) && daysInFlower < expectedFloweringDays; break;
                    }
                }
                
                return (!roomFilter || plant.room_name === roomFilter) &&
                       (!geneticsFilter || plant.genetics_name === geneticsFilter) &&
                       timeMatch && readyMatch;
            });

            displayFlowerPlants();
        }

        function displayFlowerPlants() {
            const tbody = document.getElementById('flowerTableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';

            if (filteredFlowerPlants.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            filteredFlowerPlants.forEach(plant => {
                const row = document.createElement('tr');
                const daysInFlower = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                const expectedFloweringDays = plant.flowering_days || 63;
                const expectedHarvestDate = new Date(plant.date_stage_changed);
                expectedHarvestDate.setDate(expectedHarvestDate.getDate() + expectedFloweringDays);
                
                const isReady = daysInFlower >= expectedFloweringDays;
                const isSoon = daysInFlower >= (expectedFloweringDays - 7) && daysInFlower < expectedFloweringDays;
                
                let harvestStatus = '';
                if (isReady) harvestStatus = '<span style="color: #22c55e; font-weight: bold;">Ready!</span>';
                else if (isSoon) harvestStatus = '<span style="color: #f59e0b; font-weight: bold;">Soon</span>';
                else harvestStatus = expectedHarvestDate.toLocaleDateString();
                
                row.innerHTML = `
                    <td><strong>${plant.tracking_number}</strong></td>
                    <td>${plant.plant_tag || '-'}</td>
                    <td>${plant.genetics_name || 'Unknown'}</td>
                    <td>${plant.room_name || 'Unassigned'}</td>
                    <td>${new Date(plant.date_stage_changed).toLocaleDateString()}</td>
                    <td>${daysInFlower}</td>
                    <td>${harvestStatus}</td>
                    <td><span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="edit_plant.php?id=${plant.id}" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">✏️ Edit</a>
                            ${isReady ? `<button onclick="harvestPlant(${plant.id})" class="modern-btn" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; background: var(--accent-success);">✂️ Harvest</button>` : ''}
                            <button onclick="destroyPlant(${plant.id})" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; color: var(--accent-error); border-color: var(--accent-error);">🗑️ Destroy</button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function harvestPlant(plantId) {
            if (confirm('Mark this plant as harvested?')) {
                fetch('harvest_plants_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `plant_ids=${plantId}`
                })
                .then(response => response.text())
                .then(() => {
                    showStatusMessage('Plant harvested successfully', 'success');
                    loadFlowerPlants();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error harvesting plant', 'error');
                });
            }
        }

        function destroyPlant(plantId) {
            if (confirm('Are you sure you want to destroy this plant? This action cannot be undone.')) {
                fetch('destroy_plants.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `plant_ids=${plantId}`
                })
                .then(response => response.text())
                .then(() => {
                    showStatusMessage('Plant destroyed successfully', 'success');
                    loadFlowerPlants();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error destroying plant', 'error');
                });
            }
        }

        function refreshData() {
            loadFlowerPlants();
        }

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

        // Event listeners for filters
        document.getElementById('roomFilter').addEventListener('change', applyFilters);
        document.getElementById('geneticsFilter').addEventListener('change', applyFilters);
        document.getElementById('floweringTimeFilter').addEventListener('change', applyFilters);
        document.getElementById('readyFilter').addEventListener('change', applyFilters);

        // Load data on page load
        loadFlowerPlants();

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