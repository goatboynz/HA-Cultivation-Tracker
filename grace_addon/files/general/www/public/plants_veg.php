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
    <title>CultivationTracker - Vegetative Stage Plants</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌱 Vegetative Stage Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Individual vegetative plant management and tracking</p>
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
                    <label for="ageFilter">Age Range:</label>
                    <select id="ageFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Ages</option>
                        <option value="0-14">0-14 days</option>
                        <option value="15-30">15-30 days</option>
                        <option value="31-60">31-60 days</option>
                        <option value="60+">60+ days</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Veg Plants Table -->
        <div class="modern-card">
            <h3>🌱 Your Vegetative Plants</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="vegTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Room</th>
                            <th>Moved to Veg</th>
                            <th>Days in Veg</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vegTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Vegetative Plants Found</h3>
                <p>Move some clones to vegetative stage to see them here</p>
            </div>
        </div>
    </main>

    <script>
        let allVegPlants = [];
        let filteredVegPlants = [];

        function loadVegPlants() {
            fetch('get_individual_plants_by_stage.php?stage=Veg')
                .then(response => response.json())
                .then(plants => {
                    allVegPlants = plants;
                    populateFilters();
                    applyFilters();
                })
                .catch(error => console.error('Error loading veg plants:', error));
        }

        function populateFilters() {
            // Populate room filter
            const rooms = [...new Set(allVegPlants.map(p => p.room_name).filter(r => r))];
            const roomFilter = document.getElementById('roomFilter');
            roomFilter.innerHTML = '<option value="">All Rooms</option>';
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomFilter.appendChild(option);
            });

            // Populate genetics filter
            const genetics = [...new Set(allVegPlants.map(p => p.genetics_name).filter(g => g))];
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
            const ageFilter = document.getElementById('ageFilter').value;

            filteredVegPlants = allVegPlants.filter(plant => {
                const daysInVeg = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                let ageMatch = true;
                
                if (ageFilter) {
                    switch(ageFilter) {
                        case '0-14': ageMatch = daysInVeg <= 14; break;
                        case '15-30': ageMatch = daysInVeg >= 15 && daysInVeg <= 30; break;
                        case '31-60': ageMatch = daysInVeg >= 31 && daysInVeg <= 60; break;
                        case '60+': ageMatch = daysInVeg > 60; break;
                    }
                }
                
                return (!roomFilter || plant.room_name === roomFilter) &&
                       (!geneticsFilter || plant.genetics_name === geneticsFilter) &&
                       ageMatch;
            });

            displayVegPlants();
        }

        function displayVegPlants() {
            const tbody = document.getElementById('vegTableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';

            if (filteredVegPlants.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            filteredVegPlants.forEach(plant => {
                const row = document.createElement('tr');
                const daysInVeg = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                
                row.innerHTML = `
                    <td><strong>${plant.tracking_number}</strong></td>
                    <td>${plant.plant_tag || '-'}</td>
                    <td>${plant.genetics_name || 'Unknown'}</td>
                    <td>${plant.room_name || 'Unassigned'}</td>
                    <td>${new Date(plant.date_stage_changed).toLocaleDateString()}</td>
                    <td>${daysInVeg}</td>
                    <td><span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="edit_plant.php?id=${plant.id}" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">✏️ Edit</a>
                            <button onclick="moveToFlower(${plant.id})" class="modern-btn" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">🌸 → Flower</button>
                            <button onclick="destroyPlant(${plant.id})" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; color: var(--accent-error); border-color: var(--accent-error);">🗑️ Destroy</button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function moveToFlower(plantId) {
            if (confirm('Move this plant to flowering stage?')) {
                window.location.href = `move_plants.php?plant_id=${plantId}&stage=Flower`;
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
                    loadVegPlants();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error destroying plant', 'error');
                });
            }
        }

        function refreshData() {
            loadVegPlants();
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
        document.getElementById('ageFilter').addEventListener('change', applyFilters);

        // Load data on page load
        loadVegPlants();

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