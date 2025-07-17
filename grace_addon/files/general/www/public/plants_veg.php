<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Vegetative Stage Plants</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="grid">
            <div>
                <h1>Vegetative Stage Plants</h1>
                <p><small>Individual vegetative plant management and tracking</small></p>
            </div>
            <div style="text-align: right;">
                <button onclick="refreshData()" class="button secondary">Refresh</button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <h3>Filters</h3>
            <div class="grid">
                <div>
                    <label for="roomFilter">Room:</label>
                    <select id="roomFilter" class="input">
                        <option value="">All Rooms</option>
                    </select>
                </div>
                <div>
                    <label for="geneticsFilter">Genetics:</label>
                    <select id="geneticsFilter" class="input">
                        <option value="">All Genetics</option>
                    </select>
                </div>
                <div>
                    <label for="ageFilter">Age Range:</label>
                    <select id="ageFilter" class="input">
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
        <div class="table-container">
            <table id="vegTable">
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

        <div id="noDataMessage" style="display: none;">
            <p>No vegetative stage plants found.</p>
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
                        <a href="edit_plant.php?id=${plant.id}" class="button small">Edit</a>
                        <button onclick="moveToFlower(${plant.id})" class="button small">→ Flower</button>
                        <button onclick="destroyPlant(${plant.id})" class="button small secondary">Destroy</button>
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