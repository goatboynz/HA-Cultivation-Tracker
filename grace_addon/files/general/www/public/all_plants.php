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
    <title>CultivationTracker - All Plants</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌿 All Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Individual plant management and tracking</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="refreshData()" class="modern-btn secondary">🔄 Refresh</button>
                <a href="receive_genetics.php" class="modern-btn">➕ Add Plants</a>
            </div>
        </div>

        <!-- Filters -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🔍 Filters</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="stageFilter">Stage:</label>
                    <select id="stageFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Stages</option>
                        <option value="Clone">🌿 Clone</option>
                        <option value="Veg">🌱 Veg</option>
                        <option value="Flower">🌸 Flower</option>
                        <option value="Mother">👑 Mother</option>
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
            </div>
        </div>

        <!-- Plants Table -->
        <div class="modern-card">
            <h3>🌿 Your Plant Collection</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="plantsTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Stage</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Days</th>
                            <th>Mother</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="plantsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Plants Found</h3>
                <p>No plants match your current filter criteria</p>
                <a href="receive_genetics.php" class="modern-btn">➕ Add Your First Plants</a>
            </div>
        </div>
    </main>

    <script>
        let allPlants = [];
        let filteredPlants = [];

        function loadAllPlants() {
            fetch('get_all_plants_detailed.php')
                .then(response => response.json())
                .then(plants => {
                    allPlants = plants;
                    populateFilters();
                    applyFilters();
                })
                .catch(error => console.error('Error loading plants:', error));
        }

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
            const genetics = [...new Set(allPlants.map(p => p.genetics_name).filter(g => g))];
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
            const stageFilter = document.getElementById('stageFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const roomFilter = document.getElementById('roomFilter').value;
            const geneticsFilter = document.getElementById('geneticsFilter').value;

            filteredPlants = allPlants.filter(plant => {
                return (!stageFilter || plant.growth_stage === stageFilter) &&
                       (!statusFilter || plant.status === statusFilter) &&
                       (!roomFilter || plant.room_name === roomFilter) &&
                       (!geneticsFilter || plant.genetics_name === geneticsFilter);
            });

            displayPlants();
        }

        function displayPlants() {
            const tbody = document.getElementById('plantsTableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';

            if (filteredPlants.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            filteredPlants.forEach(plant => {
                const row = document.createElement('tr');
                const daysOld = Math.floor((new Date() - new Date(plant.date_created)) / (1000 * 60 * 60 * 24));
                const motherInfo = plant.mother_plant_tag || (plant.mother_id ? `ID: ${plant.mother_id}` : 'N/A');
                
                row.innerHTML = `
                    <td><strong>${plant.tracking_number || 'N/A'}</strong></td>
                    <td>${plant.plant_tag || '-'}</td>
                    <td>${plant.genetics_name || 'Unknown'}</td>
                    <td><span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span></td>
                    <td>${plant.room_name || 'Unassigned'}</td>
                    <td><span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span></td>
                    <td>${new Date(plant.date_created).toLocaleDateString()}</td>
                    <td>${daysOld}</td>
                    <td>${motherInfo}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="plant_summary.php?id=${plant.id}" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">👁️ View</a>
                            <a href="edit_plant.php?id=${plant.id}" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">✏️ Edit</a>
                            ${plant.status === 'Growing' ? `
                                <button onclick="quickMove(${plant.id})" class="modern-btn" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">🔄 Move</button>
                                <button onclick="harvestPlant(${plant.id})" class="modern-btn" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; background: var(--accent-success);">✂️ Harvest</button>
                            ` : ''}
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function quickMove(plantId) {
            const plant = allPlants.find(p => p.id === plantId);
            if (!plant) return;

            let nextStage = '';
            switch(plant.growth_stage) {
                case 'Clone': nextStage = 'Veg'; break;
                case 'Veg': nextStage = 'Flower'; break;
                default: 
                    alert('Cannot auto-advance this plant stage');
                    return;
            }

            if (confirm(`Move plant to ${nextStage} stage?`)) {
                // Get available rooms for the target stage
                fetch('get_rooms.php')
                    .then(response => response.json())
                    .then(rooms => {
                        const targetRooms = rooms.filter(room => room.room_type === nextStage || room.room_type === 'General');
                        
                        if (targetRooms.length === 0) {
                            showStatusMessage(`No ${nextStage} rooms available. Please create a ${nextStage} room first.`, 'error');
                            return;
                        }
                        
                        // Use the first available room
                        const targetRoom = targetRooms[0].id;
                        
                        const formData = new FormData();
                        formData.append('plants', JSON.stringify([plant.id]));
                        formData.append('target_stage', nextStage);
                        formData.append('target_room', targetRoom);
                        formData.append('current_stage', plant.growth_stage);
                        
                        fetch('move_plants.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showStatusMessage(`Plant moved to ${nextStage} stage successfully`, 'success');
                                loadAllPlants();
                            } else {
                                showStatusMessage('Error: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showStatusMessage('Error moving plant', 'error');
                        });
                    })
                    .catch(error => {
                        console.error('Error loading rooms:', error);
                        showStatusMessage('Error loading rooms', 'error');
                    });
            }
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
                    loadAllPlants();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error harvesting plant', 'error');
                });
            }
        }

        function refreshData() {
            loadAllPlants();
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
        document.getElementById('stageFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('roomFilter').addEventListener('change', applyFilters);
        document.getElementById('geneticsFilter').addEventListener('change', applyFilters);

        // Load data on page load
        loadAllPlants();

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