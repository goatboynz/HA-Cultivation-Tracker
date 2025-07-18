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
    <title>CultivationTracker - Plant Summary</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌿 Plant Summary</h1>
                <p style="color: var(--text-secondary); margin: 0;">Detailed plant information and history</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="all_plants.php" class="modern-btn secondary">← Back to Plants</a>
                <button id="editPlantBtn" class="modern-btn">✏️ Edit Plant</button>
            </div>
        </div>

        <!-- Plant Overview Card -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>📋 Plant Overview</h3>
            <div id="plantOverview" style="margin-top: 1rem;">
                <!-- Plant overview will be loaded here -->
            </div>
        </div>

        <!-- Plant Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <!-- Basic Information -->
            <div class="modern-card">
                <h3>ℹ️ Basic Information</h3>
                <div id="basicInfo" class="info-grid">
                    <!-- Basic info will be loaded here -->
                </div>
            </div>

            <!-- Growth Information -->
            <div class="modern-card">
                <h3>🌱 Growth Information</h3>
                <div id="growthInfo" class="info-grid">
                    <!-- Growth info will be loaded here -->
                </div>
            </div>

            <!-- Source Information -->
            <div class="modern-card">
                <h3>🔗 Source Information</h3>
                <div id="sourceInfo" class="info-grid">
                    <!-- Source info will be loaded here -->
                </div>
            </div>

            <!-- Timeline -->
            <div class="modern-card">
                <h3>📅 Timeline</h3>
                <div id="timeline" class="timeline-container">
                    <!-- Timeline will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Harvest Information (if applicable) -->
        <div id="harvestCard" class="modern-card" style="margin-bottom: 2rem; display: none;">
            <h3>✂️ Harvest Information</h3>
            <div id="harvestInfo" class="info-grid">
                <!-- Harvest info will be loaded here -->
            </div>
        </div>

        <!-- Plant Photos -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>📸 Plant Photos</h3>
            <div id="plantPhotos" class="photo-gallery">
                <!-- Photos will be loaded here -->
            </div>
        </div>

        <!-- Plant History -->
        <div class="modern-card">
            <h3>📜 Plant History</h3>
            <div id="plantHistory" class="history-container">
                <!-- History will be loaded here -->
            </div>
        </div>
    </main>

    <style>
        .info-grid {
            display: grid;
            gap: 1rem;
            margin-top: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--bg-tertiary);
            border-radius: 8px;
            border-left: 4px solid var(--accent-primary);
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        .timeline-container {
            margin-top: 1rem;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-left: 3px solid var(--accent-primary);
            margin-left: 1rem;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 1.5rem;
            width: 12px;
            height: 12px;
            background: var(--accent-primary);
            border-radius: 50%;
        }

        .timeline-date {
            font-size: 0.9rem;
            color: var(--text-secondary);
            min-width: 120px;
        }

        .timeline-event {
            color: var(--text-primary);
        }

        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: var(--bg-secondary);
        }

        .photo-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .photo-info {
            padding: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .history-container {
            margin-top: 1rem;
        }

        .history-item {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .history-action {
            font-weight: 600;
            color: var(--accent-primary);
        }

        .history-date {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .history-details {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .stage-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stage-badge.clone { background: var(--accent-info); color: white; }
        .stage-badge.veg { background: var(--accent-success); color: white; }
        .stage-badge.flower { background: var(--accent-warning); color: white; }
        .stage-badge.mother { background: var(--accent-primary); color: white; }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.growing { background: var(--accent-success); color: white; }
        .status-badge.harvested { background: var(--accent-warning); color: white; }
        .status-badge.destroyed { background: var(--accent-danger); color: white; }
        .status-badge.sent { background: var(--accent-info); color: white; }
    </style>

    <script>
        let currentPlant = null;

        function loadPlantSummary() {
            const urlParams = new URLSearchParams(window.location.search);
            const plantId = urlParams.get('id');
            
            if (!plantId) {
                window.location.href = 'all_plants.php';
                return;
            }

            // Set edit button link
            document.getElementById('editPlantBtn').onclick = () => {
                window.location.href = `edit_plant.php?id=${plantId}`;
            };

            fetch(`get_plant_details.php?id=${plantId}`)
                .then(response => response.json())
                .then(plant => {
                    if (!plant || plant.error) {
                        showStatusMessage('Plant not found', 'error');
                        return;
                    }
                    
                    currentPlant = plant;
                    displayPlantOverview(plant);
                    displayBasicInfo(plant);
                    displayGrowthInfo(plant);
                    displaySourceInfo(plant);
                    displayTimeline(plant);
                    displayHarvestInfo(plant);
                    displayPlantPhotos(plant);
                    displayPlantHistory(plant);
                })
                .catch(error => {
                    console.error('Error loading plant:', error);
                    showStatusMessage('Error loading plant details', 'error');
                });
        }

        function displayPlantOverview(plant) {
            const daysOld = Math.floor((new Date() - new Date(plant.date_created)) / (1000 * 60 * 60 * 24));
            
            document.getElementById('plantOverview').innerHTML = `
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="info-item">
                        <span class="info-label">Tracking Number</span>
                        <span class="info-value"><strong>${plant.tracking_number || 'N/A'}</strong></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Plant Tag</span>
                        <span class="info-value">${plant.plant_tag || '-'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Genetics</span>
                        <span class="info-value">${plant.genetics_name || 'Unknown'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Stage</span>
                        <span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Age</span>
                        <span class="info-value">${daysOld} days</span>
                    </div>
                </div>
            `;
        }

        function displayBasicInfo(plant) {
            document.getElementById('basicInfo').innerHTML = `
                <div class="info-item">
                    <span class="info-label">Room</span>
                    <span class="info-value">${plant.room_name || 'Unassigned'}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date Created</span>
                    <span class="info-value">${new Date(plant.date_created).toLocaleString()}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Stage Changed</span>
                    <span class="info-value">${plant.date_stage_changed ? new Date(plant.date_stage_changed).toLocaleString() : 'N/A'}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Is Mother</span>
                    <span class="info-value">${plant.is_mother ? 'Yes' : 'No'}</span>
                </div>
            `;
        }

        function displayGrowthInfo(plant) {
            const stageInfo = getStageInfo(plant.growth_stage);
            document.getElementById('growthInfo').innerHTML = `
                <div class="info-item">
                    <span class="info-label">Current Stage</span>
                    <span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Days in Stage</span>
                    <span class="info-value">${plant.date_stage_changed ? Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24)) : 'N/A'} days</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Stage Description</span>
                    <span class="info-value">${stageInfo}</span>
                </div>
            `;
        }

        function displaySourceInfo(plant) {
            const motherInfo = plant.mother_plant_tag || (plant.mother_id ? `ID: ${plant.mother_id}` : 'N/A');
            document.getElementById('sourceInfo').innerHTML = `
                <div class="info-item">
                    <span class="info-label">Source Type</span>
                    <span class="info-value">${plant.source_type || 'Unknown'}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mother Plant</span>
                    <span class="info-value">${motherInfo}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Seed Stock</span>
                    <span class="info-value">${plant.seed_stock_name || 'N/A'}</span>
                </div>
            `;
        }

        function displayTimeline(plant) {
            const timeline = [];
            
            timeline.push({
                date: plant.date_created,
                event: `Plant created in ${plant.growth_stage} stage`
            });
            
            if (plant.date_stage_changed && plant.date_stage_changed !== plant.date_created) {
                timeline.push({
                    date: plant.date_stage_changed,
                    event: `Moved to ${plant.growth_stage} stage`
                });
            }
            
            if (plant.date_harvested) {
                timeline.push({
                    date: plant.date_harvested,
                    event: 'Plant harvested'
                });
            }
            
            timeline.sort((a, b) => new Date(b.date) - new Date(a.date));
            
            document.getElementById('timeline').innerHTML = timeline.map(item => `
                <div class="timeline-item">
                    <div class="timeline-date">${new Date(item.date).toLocaleDateString()}</div>
                    <div class="timeline-event">${item.event}</div>
                </div>
            `).join('');
        }

        function displayHarvestInfo(plant) {
            const harvestCard = document.getElementById('harvestCard');
            
            if (plant.status === 'Harvested' && (plant.wet_weight || plant.dry_weight || plant.flower_weight || plant.trim_weight)) {
                harvestCard.style.display = 'block';
                document.getElementById('harvestInfo').innerHTML = `
                    <div class="info-item">
                        <span class="info-label">Wet Weight</span>
                        <span class="info-value">${plant.wet_weight || 0}g</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dry Weight</span>
                        <span class="info-value">${plant.dry_weight || 0}g</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Flower Weight</span>
                        <span class="info-value">${plant.flower_weight || 0}g</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Trim Weight</span>
                        <span class="info-value">${plant.trim_weight || 0}g</span>
                    </div>
                `;
            } else {
                harvestCard.style.display = 'none';
            }
        }

        function displayPlantPhotos(plant) {
            // This would load photos from the database
            // For now, show placeholder
            document.getElementById('plantPhotos').innerHTML = `
                <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                    <p>📷 No photos available</p>
                    <p>Photos can be added when editing the plant</p>
                </div>
            `;
        }

        function displayPlantHistory(plant) {
            // This would load history from the database
            // For now, show basic history
            document.getElementById('plantHistory').innerHTML = `
                <div class="history-item">
                    <div class="history-header">
                        <span class="history-action">Plant Created</span>
                        <span class="history-date">${new Date(plant.date_created).toLocaleString()}</span>
                    </div>
                    <div class="history-details">Plant added to system in ${plant.growth_stage} stage</div>
                </div>
                ${plant.date_stage_changed && plant.date_stage_changed !== plant.date_created ? `
                <div class="history-item">
                    <div class="history-header">
                        <span class="history-action">Stage Changed</span>
                        <span class="history-date">${new Date(plant.date_stage_changed).toLocaleString()}</span>
                    </div>
                    <div class="history-details">Plant moved to ${plant.growth_stage} stage</div>
                </div>
                ` : ''}
            `;
        }

        function getStageInfo(stage) {
            const stageDescriptions = {
                'Clone': 'Young cutting or clone developing roots',
                'Veg': 'Vegetative growth phase with leaf and stem development',
                'Flower': 'Flowering phase producing buds',
                'Mother': 'Mature plant maintained for clone production'
            };
            return stageDescriptions[stage] || 'Unknown stage';
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

        // Load plant summary on page load
        loadPlantSummary();

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