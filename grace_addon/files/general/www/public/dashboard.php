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
    <title>CultivationTracker - Dashboard</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h1>🌿 Cultivation Dashboard</h1>
                <p style="color: #94a3b8; margin: 0;">Welcome back! Here's your cultivation overview</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0; color: #94a3b8; font-size: 0.9rem;">Last updated</p>
                <p id="lastUpdated" style="margin: 0; font-weight: 600;">Loading...</p>
            </div>
        </div>

        <!-- Plant Overview Cards -->
        <section>
            <h2>🌱 Plant Overview</h2>
            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>🌿 Clone Stage</h3>
                    <div class="stat-number" id="cloneCount">-</div>
                    <div class="stat-label">Active Clones</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="cloneProgress" style="width: 0%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>🌱 Vegetative Stage</h3>
                    <div class="stat-number" id="vegCount">-</div>
                    <div class="stat-label">Veg Plants</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="vegProgress" style="width: 0%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>🌸 Flowering Stage</h3>
                    <div class="stat-number" id="flowerCount">-</div>
                    <div class="stat-label">Flowering Plants</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="flowerProgress" style="width: 0%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>👑 Mother Plants</h3>
                    <div class="stat-number" id="motherCount">-</div>
                    <div class="stat-label">Active Mothers</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="motherProgress" style="width: 0%"></div>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-success), #047857);">
                    <h3>📊 Total Active</h3>
                    <div class="stat-number" id="totalCount" style="color: white;">-</div>
                    <div class="stat-label" style="color: #d1fae5;">All Growing Plants</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));">
                    <h3>🏆 This Month</h3>
                    <div class="stat-number" id="harvestedCount" style="color: white;">-</div>
                    <div class="stat-label" style="color: #ddd6fe;">Plants Harvested</div>
                </div>
            </div>
        </section>

        <!-- Room Utilization -->
        <section>
            <h2>🏠 Room Utilization</h2>
            <div id="roomCards" class="dashboard-grid">
                <!-- Room cards will be populated by JavaScript -->
            </div>
        </section>

        <!-- Ready to Harvest -->
        <section>
            <h2>⏰ Plants Ready Soon</h2>
            <div class="modern-card">
                <table id="readyToHarvestTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Genetics</th>
                            <th>Room</th>
                            <th>Days in Flower</th>
                            <th>Count</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <p id="noReadyPlantsMessage" style="display: none; text-align: center; font-style: italic; color: var(--text-secondary);">No plants approaching harvest</p>
            </div>
        </section>

        <!-- Genetics Overview -->
        <section>
            <h2>🧬 Genetics Overview</h2>
            <div id="geneticsOverview" class="dashboard-grid">
                <!-- Genetics cards will be populated by JavaScript -->
            </div>
        </section>

        <!-- Quick Actions -->
        <section>
            <h2>⚡ Quick Actions</h2>
            <div class="quick-actions">
                <a href="receive_genetics.php" class="quick-action">
                    <span class="quick-action-icon">➕</span>
                    <div class="quick-action-content">
                        <h3>Add New Plants</h3>
                        <p>Start new plants from seeds or clones</p>
                    </div>
                </a>
                <a href="plants_clone.php" class="quick-action">
                    <span class="quick-action-icon">🌿</span>
                    <div class="quick-action-content">
                        <h3>Manage Clones</h3>
                        <p>View and manage clone stage plants</p>
                    </div>
                </a>
                <a href="plants_veg.php" class="quick-action">
                    <span class="quick-action-icon">🌱</span>
                    <div class="quick-action-content">
                        <h3>Manage Veg</h3>
                        <p>Handle vegetative stage plants</p>
                    </div>
                </a>
                <a href="plants_flower.php" class="quick-action">
                    <span class="quick-action-icon">🌸</span>
                    <div class="quick-action-content">
                        <h3>Manage Flower</h3>
                        <p>Monitor flowering plants</p>
                    </div>
                </a>
                <a href="harvest_plants.php" class="quick-action">
                    <span class="quick-action-icon">✂️</span>
                    <div class="quick-action-content">
                        <h3>Harvest Plants</h3>
                        <p>Process ready plants</p>
                    </div>
                </a>
                <a href="take_clones.php" class="quick-action">
                    <span class="quick-action-icon">🔄</span>
                    <div class="quick-action-content">
                        <h3>Take Clones</h3>
                        <p>Create new clones from mothers</p>
                    </div>
                </a>
                <a href="move_plants.php" class="quick-action">
                    <span class="quick-action-icon">📦</span>
                    <div class="quick-action-content">
                        <h3>Move Plants</h3>
                        <p>Transfer plants between rooms</p>
                    </div>
                </a>
                <a href="reports.php" class="quick-action">
                    <span class="quick-action-icon">📊</span>
                    <div class="quick-action-content">
                        <h3>View Reports</h3>
                        <p>Generate cultivation reports</p>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <script src="js/growcart.js"></script>
    <script>
        // Load dashboard data
        function loadDashboard() {
            document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
            
            let totalPlants = 0;
            const stageCounts = {};
            
            // Load plant counts by stage
            ['Clone', 'Veg', 'Flower', 'Mother'].forEach(stage => {
                fetch(`get_plants_by_stage.php?stage=${stage}`)
                    .then(response => response.json())
                    .then(plants => {
                        const total = plants.reduce((sum, plant) => sum + parseInt(plant.count), 0);
                        stageCounts[stage] = total;
                        document.getElementById(stage.toLowerCase() + 'Count').textContent = total;
                        totalPlants += total;
                        updateTotalAndProgress();
                    })
                    .catch(error => console.error(`Error loading ${stage} count:`, error));
            });

            // Load harvested plants this month
            loadHarvestedCount();
            
            // Load room utilization
            loadRoomUtilization();

            // Load plants ready to harvest
            loadReadyToHarvest();

            // Load genetics overview
            loadGeneticsOverview();
        }

        function updateTotalAndProgress() {
            const clone = parseInt(document.getElementById('cloneCount').textContent) || 0;
            const veg = parseInt(document.getElementById('vegCount').textContent) || 0;
            const flower = parseInt(document.getElementById('flowerCount').textContent) || 0;
            const mother = parseInt(document.getElementById('motherCount').textContent) || 0;
            const total = clone + veg + flower + mother;
            
            document.getElementById('totalCount').textContent = total;
            
            // Update progress bars
            if (total > 0) {
                document.getElementById('cloneProgress').style.width = `${(clone / total) * 100}%`;
                document.getElementById('vegProgress').style.width = `${(veg / total) * 100}%`;
                document.getElementById('flowerProgress').style.width = `${(flower / total) * 100}%`;
                document.getElementById('motherProgress').style.width = `${(mother / total) * 100}%`;
            }
        }

        function loadHarvestedCount() {
            const currentMonth = new Date().toISOString().slice(0, 7); // YYYY-MM format
            fetch(`get_all_plants_detailed.php`)
                .then(response => response.json())
                .then(plants => {
                    const harvestedThisMonth = plants.filter(plant => 
                        plant.status === 'Harvested' && 
                        plant.date_harvested && 
                        plant.date_harvested.startsWith(currentMonth)
                    ).length;
                    document.getElementById('harvestedCount').textContent = harvestedThisMonth;
                })
                .catch(error => console.error('Error loading harvested count:', error));
        }

        function loadRoomUtilization() {
            fetch('get_all_rooms.php')
                .then(response => response.json())
                .then(rooms => {
                    const roomCards = document.getElementById('roomCards');
                    roomCards.innerHTML = '';

                    // Get plant counts for each room
                    Promise.all(['Clone', 'Veg', 'Flower', 'Mother'].map(stage => 
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
                            card.className = 'stat-card';
                            card.innerHTML = `
                                <h3>${getRoomIcon(room.room_type)} ${room.name}</h3>
                                <div class="stat-number">${count}</div>
                                <div class="stat-label">${room.room_type} Room</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: ${Math.min((count / 20) * 100, 100)}%"></div>
                                </div>
                            `;
                            roomCards.appendChild(card);
                        });
                    });
                })
                .catch(error => console.error('Error loading room utilization:', error));
        }

        function getRoomIcon(roomType) {
            const icons = {
                'Clone': '🌿',
                'Veg': '🌱',
                'Flower': '🌸',
                'Mother': '👑',
                'Dry': '🏺',
                'Storage': '📦'
            };
            return icons[roomType] || '🏠';
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
                        
                        // Show plants with 45+ days in flower as "ready soon"
                        if (daysInFlower >= 45) {
                            readyCount++;
                            const row = readyTable.insertRow();
                            const status = daysInFlower >= 65 ? '🔴 Ready Now' : daysInFlower >= 55 ? '🟡 Soon' : '🟢 Monitor';
                            row.innerHTML = `
                                <td>${plant.genetics_name}</td>
                                <td>${plant.room_name || 'Unassigned'}</td>
                                <td>${daysInFlower}</td>
                                <td>${plant.count}</td>
                                <td>${status}</td>
                            `;
                        }
                    });

                    noReadyMessage.style.display = readyCount === 0 ? 'block' : 'none';
                })
                .catch(error => console.error('Error loading ready to harvest plants:', error));
        }

        function loadGeneticsOverview() {
            fetch('get_all_genetics.php')
                .then(response => response.json())
                .then(genetics => {
                    const geneticsContainer = document.getElementById('geneticsOverview');
                    geneticsContainer.innerHTML = '';
                    
                    // Get plant counts for each genetics
                    fetch('get_all_plants_detailed.php')
                        .then(response => response.json())
                        .then(plants => {
                            const geneticsCounts = {};
                            plants.filter(p => p.status === 'Growing').forEach(plant => {
                                const geneticsName = plant.genetics_name || 'Unknown';
                                geneticsCounts[geneticsName] = (geneticsCounts[geneticsName] || 0) + 1;
                            });
                            
                            genetics.slice(0, 6).forEach(genetic => {
                                const count = geneticsCounts[genetic.name] || 0;
                                const card = document.createElement('div');
                                card.className = 'stat-card';
                                card.innerHTML = `
                                    <h3>🧬 ${genetic.name}</h3>
                                    <div class="stat-number">${count}</div>
                                    <div class="stat-label">Active Plants</div>
                                    <small style="color: var(--text-secondary);">${genetic.indica_sativa_ratio || 'Unknown ratio'}</small>
                                `;
                                geneticsContainer.appendChild(card);
                            });
                        });
                })
                .catch(error => console.error('Error loading genetics overview:', error));
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