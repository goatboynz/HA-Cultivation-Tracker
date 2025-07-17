<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Dashboard</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>CultivationTracker Dashboard</h1>

        <section>
            <h2>Plant Overview</h2>
            <div class="grid">
                <div class="card">
                    <h3>Clone Stage</h3>
                    <p id="cloneCount" class="stat-number">-</p>
                    <small>Total clones</small>
                </div>
                <div class="card">
                    <h3>Vegetative Stage</h3>
                    <p id="vegCount" class="stat-number">-</p>
                    <small>Total veg plants</small>
                </div>
                <div class="card">
                    <h3>Flowering Stage</h3>
                    <p id="flowerCount" class="stat-number">-</p>
                    <small>Total flowering plants</small>
                </div>
                <div class="card">
                    <h3>Mother Plants</h3>
                    <p id="motherCount" class="stat-number">-</p>
                    <small>Active mothers</small>
                </div>
                <div class="card">
                    <h3>Total Active</h3>
                    <p id="totalCount" class="stat-number">-</p>
                    <small>All growing plants</small>
                </div>
            </div>
        </section>

        <section>
            <h2>Room Utilization</h2>
            <div id="roomCards" class="grid">
                <!-- Room cards will be populated by JavaScript -->
            </div>
        </section>

        <section>
            <h2>Flowering Plants Ready Soon</h2>
            <table id="readyToHarvestTable" class="table">
                <thead>
                    <tr>
                        <th>Genetics</th>
                        <th>Room</th>
                        <th>Days in Flower</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p id="noReadyPlantsMessage" style="display: none; text-align: center; font-style: italic;">No plants approaching harvest</p>
        </section>

        <section>
            <h2>Recent Activity</h2>
            <div id="recentActivity">
                <p><em>Loading recent activity...</em></p>
            </div>
        </section>

        <section>
            <h2>Quick Actions</h2>
            <div class="grid">
                <a href="receive_genetics.php" class="button">Add New Plants</a>
                <a href="plants_clone.php" class="button secondary">Manage Clones</a>
                <a href="plants_veg.php" class="button secondary">Manage Veg</a>
                <a href="plants_flower.php" class="button secondary">Manage Flower</a>
            </div>
            <div class="grid">
                <a href="manage_rooms.php" class="button outline">Manage Rooms</a>
                <a href="GETTING_STARTED_V12.md" class="button outline" target="_blank">Setup Guide</a>
            </div>
        </section>
    </main>

    <script src="js/growcart.js"></script>
    <script>
        // Load dashboard data
        function loadDashboard() {
            // Load plant counts by stage
            ['Clone', 'Veg', 'Flower', 'Mother'].forEach(stage => {
                fetch(`get_plants_by_stage.php?stage=${stage}`)
                    .then(response => response.json())
                    .then(plants => {
                        const total = plants.reduce((sum, plant) => sum + parseInt(plant.count), 0);
                        document.getElementById(stage.toLowerCase() + 'Count').textContent = total;
                        updateTotalCount();
                    })
                    .catch(error => console.error(`Error loading ${stage} count:`, error));
            });

            // Load room utilization
            loadRoomUtilization();

            // Load plants ready to harvest (60+ days in flower)
            loadReadyToHarvest();

            // Load recent activity
            loadRecentActivity();
        }

        function updateTotalCount() {
            const clone = parseInt(document.getElementById('cloneCount').textContent) || 0;
            const veg = parseInt(document.getElementById('vegCount').textContent) || 0;
            const flower = parseInt(document.getElementById('flowerCount').textContent) || 0;
            const mother = parseInt(document.getElementById('motherCount').textContent) || 0;
            document.getElementById('totalCount').textContent = clone + veg + flower + mother;
        }

        function loadRoomUtilization() {
            fetch('get_all_rooms.php')
                .then(response => response.json())
                .then(rooms => {
                    const roomCards = document.getElementById('roomCards');
                    roomCards.innerHTML = '';

                    // Get plant counts for each room
                    Promise.all(['Clone', 'Veg', 'Flower'].map(stage => 
                        fetch(`get_plants_by_stage.php?stage=${stage}`).then(r => r.json())
                    )).then(stageData => {
                        const roomCounts = {};
                        
                        stageData.flat().forEach(plant => {
                            const roomName = plant.room_name || 'Unassigned';
                            roomCounts[roomName] = (roomCounts[roomName] || 0) + parseInt(plant.count);
                        });

                        rooms.forEach(room => {
                            const count = roomCounts[room.name] || 0;
                            const card = document.createElement('div');
                            card.className = 'card';
                            card.innerHTML = `
                                <h4>${room.name}</h4>
                                <p class="stat-number">${count}</p>
                                <small>${room.room_type} Room</small>
                            `;
                            roomCards.appendChild(card);
                        });
                    });
                })
                .catch(error => console.error('Error loading room utilization:', error));
        }

        function loadReadyToHarvest() {
            fetch('get_plants_by_stage.php?stage=Flower')
                .then(response => response.json())
                .then(plants => {
                    const readyTable = document.getElementById('readyToHarvestTable').getElementsByTagName('tbody')[0];
                    const noReadyMessage = document.getElementById('noReadyPlantsMessage');
                    
                    readyTable.innerHTML = '';
                    let readyCount = 0;

                    plants.forEach(plant => {
                        const daysInFlower = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                        
                        // Show plants with 50+ days in flower as "ready soon"
                        if (daysInFlower >= 50) {
                            readyCount++;
                            const row = readyTable.insertRow();
                            row.innerHTML = `
                                <td>${plant.genetics_name}</td>
                                <td>${plant.room_name || 'Unassigned'}</td>
                                <td>${daysInFlower}</td>
                                <td>${plant.count}</td>
                            `;
                        }
                    });

                    noReadyMessage.style.display = readyCount === 0 ? 'block' : 'none';
                })
                .catch(error => console.error('Error loading ready to harvest plants:', error));
        }

        function loadRecentActivity() {
            // This would ideally connect to an activity log table
            // For now, show a simple message
            document.getElementById('recentActivity').innerHTML = `
                <p><small>Recent activity tracking coming soon. This will show plant movements, harvests, and other important events.</small></p>
            `;
        }

        // Load dashboard on page load
        loadDashboard();

        // Refresh dashboard every 5 minutes
        setInterval(loadDashboard, 300000);
    </script>

    <style>
        .card {
            background: var(--pico-card-background-color);
            border: var(--pico-border-width) solid var(--pico-border-color);
            border-radius: var(--pico-border-radius);
            padding: 1rem;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin: 0.5rem 0;
            color: var(--pico-primary-color);
        }
        
        .card h3, .card h4 {
            margin-bottom: 0.5rem;
        }
        
        .card small {
            color: var(--pico-muted-color);
        }
    </style>
</body>
</html>