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
    <title>CultivationTracker - Clone Stage Plants</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌿 Clone Stage Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Individual clone management and tracking</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="refreshData()" class="modern-btn secondary">🔄 Refresh</button>
                <a href="receive_genetics.php" class="modern-btn">➕ Add Clones</a>
            </div>
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
                    <label for="motherFilter">Mother Plant:</label>
                    <select id="motherFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Sources</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Clone Plants Table -->
        <div class="modern-card">
            <h3>🌿 Your Clone Plants</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="clonesTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Room</th>
                            <th>Mother Plant</th>
                            <th>Created</th>
                            <th>Days Old</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clonesTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Clone Plants Found</h3>
                <p>Start by adding some clones to your cultivation system</p>
                <a href="receive_genetics.php" class="modern-btn">➕ Add Your First Clones</a>
            </div>
        </div>
    </main>

    <script>
        let allClones = [];
        let filteredClones = [];

        function loadClones() {
            fetch('get_individual_plants_by_stage.php?stage=Clone')
                .then(response => response.json())
                .then(plants => {
                    allClones = plants;
                    populateFilters();
                    applyFilters();
                })
                .catch(error => console.error('Error loading clones:', error));
        }

        function populateFilters() {
            // Populate room filter
            const rooms = [...new Set(allClones.map(p => p.room_name).filter(r => r))];
            const roomFilter = document.getElementById('roomFilter');
            roomFilter.innerHTML = '<option value="">All Rooms</option>';
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomFilter.appendChild(option);
            });

            // Populate genetics filter
            const genetics = [...new Set(allClones.map(p => p.genetics_name).filter(g => g))];
            const geneticsFilter = document.getElementById('geneticsFilter');
            geneticsFilter.innerHTML = '<option value="">All Genetics</option>';
            genetics.forEach(genetic => {
                const option = document.createElement('option');
                option.value = genetic;
                option.textContent = genetic;
                geneticsFilter.appendChild(option);
            });

            // Populate mother filter
            const mothers = [...new Set(allClones.map(p => p.mother_plant_tag || (p.mother_id ? `ID: ${p.mother_id}` : null)).filter(m => m))];
            const motherFilter = document.getElementById('motherFilter');
            motherFilter.innerHTML = '<option value="">All Sources</option>';
            mothers.forEach(mother => {
                const option = document.createElement('option');
                option.value = mother;
                option.textContent = mother;
                motherFilter.appendChild(option);
            });
        }

        function applyFilters() {
            const roomFilter = document.getElementById('roomFilter').value;
            const geneticsFilter = document.getElementById('geneticsFilter').value;
            const motherFilter = document.getElementById('motherFilter').value;

            filteredClones = allClones.filter(plant => {
                const motherInfo = plant.mother_plant_tag || (plant.mother_id ? `ID: ${plant.mother_id}` : null);
                return (!roomFilter || plant.room_name === roomFilter) &&
                       (!geneticsFilter || plant.genetics_name === geneticsFilter) &&
                       (!motherFilter || motherInfo === motherFilter);
            });

            displayClones();
        }

        function displayClones() {
            const tbody = document.getElementById('clonesTableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';

            if (filteredClones.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            filteredClones.forEach(plant => {
                const row = document.createElement('tr');
                const daysOld = Math.floor((new Date() - new Date(plant.date_created)) / (1000 * 60 * 60 * 24));
                const motherInfo = plant.mother_plant_tag || (plant.mother_id ? `ID: ${plant.mother_id}` : 'Direct');
                
                row.innerHTML = `
                    <td><strong>${plant.tracking_number}</strong></td>
                    <td>${plant.plant_tag || '-'}</td>
                    <td>${plant.genetics_name || 'Unknown'}</td>
                    <td>${plant.room_name || 'Unassigned'}</td>
                    <td>${motherInfo}</td>
                    <td>${new Date(plant.date_created).toLocaleDateString()}</td>
                    <td>${daysOld}</td>
                    <td><span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="edit_plant.php?id=${plant.id}" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">✏️ Edit</a>
                            <button onclick="moveToVeg(${plant.id})" class="modern-btn" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">🌱 → Veg</button>
                            <button onclick="destroyPlant(${plant.id})" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; color: var(--accent-error); border-color: var(--accent-error);">🗑️ Destroy</button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function moveToVeg(plantId) {
            if (confirm('Move this clone to vegetative stage?')) {
                window.location.href = `move_plants.php?plant_id=${plantId}&stage=Veg`;
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
                    loadClones();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error destroying plant', 'error');
                });
            }
        }

        function refreshData() {
            loadClones();
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
        document.getElementById('motherFilter').addEventListener('change', applyFilters);

        // Load data on page load
        loadClones();

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