<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Genetics Details</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="grid">
            <div>
                <h1 id="geneticsName">Genetics Details</h1>
            </div>
            <div style="text-align: right;">
                <a href="manage_genetics.php" class="button secondary">Back to Genetics</a>
                <button onclick="editGenetics()" class="button">Edit</button>
            </div>
        </div>

        <!-- Genetics Information -->
        <div class="grid">
            <div class="card">
                <div id="geneticsPhoto" style="text-align: center; margin-bottom: 1rem;">
                    <!-- Photo will be loaded here -->
                </div>
                
                <h3>Basic Information</h3>
                <table>
                    <tr><td><strong>Strain Name:</strong></td><td id="strainName">-</td></tr>
                    <tr><td><strong>Breeder:</strong></td><td id="breeder">-</td></tr>
                    <tr><td><strong>Genetic Lineage:</strong></td><td id="geneticLineage">-</td></tr>
                    <tr><td><strong>Type:</strong></td><td id="indicaSativa">-</td></tr>
                </table>
            </div>
            
            <div class="card">
                <h3>Cultivation Data</h3>
                <table>
                    <tr><td><strong>Flowering Time:</strong></td><td id="floweringDays">-</td></tr>
                    <tr><td><strong>THC Content:</strong></td><td id="thcPercentage">-</td></tr>
                    <tr><td><strong>CBD Content:</strong></td><td id="cbdPercentage">-</td></tr>
                    <tr><td><strong>Added Date:</strong></td><td id="createdDate">-</td></tr>
                </table>
            </div>
        </div>

        <!-- Description -->
        <div class="card">
            <h3>Description</h3>
            <p id="description">No description available.</p>
        </div>

        <!-- Plant Statistics -->
        <div class="card">
            <h3>Plant Statistics</h3>
            <div class="grid">
                <div>
                    <h4>Current Plants</h4>
                    <ul id="currentPlantStats">
                        <li>Clone: <span id="cloneCount">0</span></li>
                        <li>Veg: <span id="vegCount">0</span></li>
                        <li>Flower: <span id="flowerCount">0</span></li>
                        <li>Mother: <span id="motherCount">0</span></li>
                    </ul>
                </div>
                <div>
                    <h4>Historical Data</h4>
                    <ul id="historicalStats">
                        <li>Total Plants: <span id="totalPlants">0</span></li>
                        <li>Harvested: <span id="harvestedCount">0</span></li>
                        <li>Success Rate: <span id="successRate">0%</span></li>
                        <li>Avg Days to Harvest: <span id="avgDaysToHarvest">-</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Plants -->
        <div class="card">
            <h3>Recent Plants</h3>
            <div class="table-container">
                <table id="recentPlantsTable">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Stage</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="recentPlantsBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const geneticsId = urlParams.get('id');
        
        if (!geneticsId) {
            window.location.href = 'manage_genetics.php';
        }

        function loadGeneticsDetails() {
            fetch(`get_genetics_details.php?id=${geneticsId}`)
                .then(response => response.json())
                .then(genetics => {
                    if (genetics.error) {
                        showStatusMessage(genetics.error, 'error');
                        return;
                    }
                    
                    // Update page title and basic info
                    document.getElementById('geneticsName').textContent = genetics.name;
                    document.getElementById('strainName').textContent = genetics.name;
                    document.getElementById('breeder').textContent = genetics.breeder || 'Unknown';
                    document.getElementById('geneticLineage').textContent = genetics.genetic_lineage || 'Not specified';
                    document.getElementById('indicaSativa').textContent = genetics.indica_sativa_ratio || 'Not specified';
                    
                    // Cultivation data
                    document.getElementById('floweringDays').textContent = genetics.flowering_days ? genetics.flowering_days + ' days' : 'Not specified';
                    document.getElementById('thcPercentage').textContent = genetics.thc_percentage ? genetics.thc_percentage + '%' : 'Not tested';
                    document.getElementById('cbdPercentage').textContent = genetics.cbd_percentage ? genetics.cbd_percentage + '%' : 'Not tested';
                    document.getElementById('createdDate').textContent = genetics.created_date ? new Date(genetics.created_date).toLocaleDateString() : 'Unknown';
                    
                    // Description
                    document.getElementById('description').textContent = genetics.description || 'No description available.';
                    
                    // Photo
                    const photoDiv = document.getElementById('geneticsPhoto');
                    if (genetics.photo_url) {
                        photoDiv.innerHTML = `<img src="${genetics.photo_url}" alt="${genetics.name}" style="max-width: 300px; max-height: 300px; object-fit: cover; border-radius: 8px;">`;
                    } else {
                        photoDiv.innerHTML = '<p style="color: #666;">No photo available</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading genetics details:', error);
                    showStatusMessage('Error loading genetics details', 'error');
                });
        }

        function loadPlantStatistics() {
            fetch(`get_genetics_plant_stats.php?id=${geneticsId}`)
                .then(response => response.json())
                .then(stats => {
                    // Current plants by stage
                    document.getElementById('cloneCount').textContent = stats.current.Clone || 0;
                    document.getElementById('vegCount').textContent = stats.current.Veg || 0;
                    document.getElementById('flowerCount').textContent = stats.current.Flower || 0;
                    document.getElementById('motherCount').textContent = stats.current.Mother || 0;
                    
                    // Historical data
                    document.getElementById('totalPlants').textContent = stats.historical.total || 0;
                    document.getElementById('harvestedCount').textContent = stats.historical.harvested || 0;
                    document.getElementById('successRate').textContent = stats.historical.success_rate || '0%';
                    document.getElementById('avgDaysToHarvest').textContent = stats.historical.avg_days || '-';
                })
                .catch(error => console.error('Error loading plant statistics:', error));
        }

        function loadRecentPlants() {
            fetch(`get_genetics_recent_plants.php?id=${geneticsId}`)
                .then(response => response.json())
                .then(plants => {
                    const tbody = document.getElementById('recentPlantsBody');
                    tbody.innerHTML = '';
                    
                    plants.forEach(plant => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><strong>${plant.tracking_number}</strong></td>
                            <td><span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span></td>
                            <td>${plant.room_name || 'Unassigned'}</td>
                            <td><span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span></td>
                            <td>${new Date(plant.date_created).toLocaleDateString()}</td>
                            <td>
                                <a href="edit_plant.php?id=${plant.id}" class="button small">Edit</a>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error loading recent plants:', error));
        }

        function editGenetics() {
            window.location.href = `edit_genetics.php?id=${geneticsId}`;
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

        // Load all data on page load
        loadGeneticsDetails();
        loadPlantStatistics();
        loadRecentPlants();
    </script>
</body>
</html>