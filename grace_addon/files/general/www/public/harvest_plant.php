<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css">
    <link rel="stylesheet" href="css/modern-responsive.css">
    <title>CultivationTracker - Harvest Plant</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="page-header">
            <div>
                <h1>Harvest Plant</h1>
                <p class="subtitle">Record harvest details and weights</p>
            </div>
            <div class="header-actions">
                <a href="plants_flower.php" class="btn btn-secondary">← Back to Flowering</a>
            </div>
        </div>

        <form id="harvestForm" class="modern-form" action="handle_harvest_plant.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="plantId" name="plantId" value="">
            
            <!-- Plant Information Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Plant Information</h3>
                </div>
                <div class="card-body">
                    <div id="plantInfo" class="plant-info-display">
                        <!-- Plant info will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Harvest Details Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Harvest Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="harvestDate">Harvest Date & Time *</label>
                            <input type="datetime-local" id="harvestDate" name="harvestDate" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dryRoom">Dry Room *</label>
                            <select id="dryRoom" name="dryRoom" class="form-control" required>
                                <option value="" disabled selected>Select Dry Room</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weight Measurements Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Weight Measurements</h3>
                    <small class="text-muted">All weights in grams</small>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="wetWeight">Wet Weight (g) *</label>
                            <input type="number" id="wetWeight" name="wetWeight" class="form-control" step="0.1" min="0" required>
                            <small class="form-text">Total weight immediately after harvest</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="dryWeight">Dry Weight (g)</label>
                            <input type="number" id="dryWeight" name="dryWeight" class="form-control" step="0.1" min="0">
                            <small class="form-text">Weight after drying (can be updated later)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="flowerWeight">Flower Weight (g)</label>
                            <input type="number" id="flowerWeight" name="flowerWeight" class="form-control" step="0.1" min="0">
                            <small class="form-text">Usable flower weight after trimming</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="trimWeight">Trim Weight (g)</label>
                            <input type="number" id="trimWeight" name="trimWeight" class="form-control" step="0.1" min="0">
                            <small class="form-text">Weight of trim/shake material</small>
                        </div>
                    </div>
                    
                    <div class="weight-summary" id="weightSummary" style="display: none;">
                        <h4>Weight Summary</h4>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <span class="label">Total Processed:</span>
                                <span class="value" id="totalProcessed">0g</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Yield Percentage:</span>
                                <span class="value" id="yieldPercentage">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quality Assessment Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Quality Assessment</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="quality">Overall Quality</label>
                            <select id="quality" name="quality" class="form-control">
                                <option value="">Select Quality</option>
                                <option value="Premium">Premium</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="trichomeColor">Trichome Color</label>
                            <select id="trichomeColor" name="trichomeColor" class="form-control">
                                <option value="">Select Color</option>
                                <option value="Clear">Clear</option>
                                <option value="Cloudy">Cloudy</option>
                                <option value="Amber">Amber</option>
                                <option value="Mixed">Mixed</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="aroma">Aroma Profile</label>
                            <input type="text" id="aroma" name="aroma" class="form-control" placeholder="e.g., Citrus, Earthy, Sweet">
                        </div>
                        
                        <div class="form-group">
                            <label for="density">Bud Density</label>
                            <select id="density" name="density" class="form-control">
                                <option value="">Select Density</option>
                                <option value="Very Dense">Very Dense</option>
                                <option value="Dense">Dense</option>
                                <option value="Medium">Medium</option>
                                <option value="Loose">Loose</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Harvest Photos Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Harvest Photos</h3>
                </div>
                <div class="card-body">
                    <div class="photo-section">
                        <div class="upload-options">
                            <button type="button" class="btn btn-primary" onclick="triggerFileUpload()">
                                📁 Upload Photos
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="openCamera()" id="cameraBtn">
                                📷 Take Photos
                            </button>
                        </div>
                        
                        <input type="file" id="photoUpload" name="photos[]" multiple accept="image/*" style="display: none;">
                        
                        <div class="camera-section" id="cameraSection" style="display: none;">
                            <video id="cameraVideo" autoplay playsinline></video>
                            <canvas id="cameraCanvas" style="display: none;"></canvas>
                            <div class="camera-controls">
                                <button type="button" class="btn btn-primary" onclick="capturePhoto()">Capture</button>
                                <button type="button" class="btn btn-secondary" onclick="closeCamera()">Cancel</button>
                            </div>
                        </div>
                        
                        <div class="photo-preview" id="photoPreview"></div>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Harvest Notes</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="harvestNotes">Notes</label>
                        <textarea id="harvestNotes" name="harvestNotes" class="form-control" rows="4" placeholder="Record any observations, issues, or special notes about this harvest..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    🌾 Complete Harvest
                </button>
                <a href="plants_flower.php" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </main>

    <script src="js/plant-edit.js"></script>
    <script>
        // Initialize the harvest form
        const urlParams = new URLSearchParams(window.location.search);
        const plantId = urlParams.get('id');
        
        if (!plantId) {
            window.location.href = 'plants_flower.php';
        }

        document.getElementById('plantId').value = plantId;
        
        // Set default harvest date to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('harvestDate').value = now.toISOString().slice(0, 16);
        
        // Load plant data and form data
        loadPlantInfo(plantId);
        loadDryRooms();
        
        // Weight calculation listeners
        document.getElementById('wetWeight').addEventListener('input', calculateYield);
        document.getElementById('flowerWeight').addEventListener('input', calculateYield);
        document.getElementById('trimWeight').addEventListener('input', calculateYield);

        async function loadPlantInfo(plantId) {
            try {
                const response = await fetch(`get_plant_details.php?id=${plantId}`);
                const plant = await response.json();
                
                if (plant.error) {
                    showStatusMessage(plant.error, 'error');
                    return;
                }
                
                const plantInfo = document.getElementById('plantInfo');
                const daysInFlower = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                
                plantInfo.innerHTML = `
                    <div class="plant-info-grid">
                        <div class="info-item">
                            <strong>Tracking Number:</strong> ${plant.tracking_number}
                        </div>
                        <div class="info-item">
                            <strong>Plant Tag:</strong> ${plant.plant_tag || 'N/A'}
                        </div>
                        <div class="info-item">
                            <strong>Genetics:</strong> ${plant.genetics_name}
                        </div>
                        <div class="info-item">
                            <strong>Current Room:</strong> ${plant.room_name}
                        </div>
                        <div class="info-item">
                            <strong>Days in Flower:</strong> ${daysInFlower}
                        </div>
                        <div class="info-item">
                            <strong>Started Flowering:</strong> ${new Date(plant.date_stage_changed).toLocaleDateString()}
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading plant info:', error);
                showStatusMessage('Error loading plant information', 'error');
            }
        }

        async function loadDryRooms() {
            try {
                const response = await fetch('get_rooms_by_type.php?type=Dry');
                const rooms = await response.json();
                const select = document.getElementById('dryRoom');
                
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading dry rooms:', error);
            }
        }

        function calculateYield() {
            const wetWeight = parseFloat(document.getElementById('wetWeight').value) || 0;
            const flowerWeight = parseFloat(document.getElementById('flowerWeight').value) || 0;
            const trimWeight = parseFloat(document.getElementById('trimWeight').value) || 0;
            
            const totalProcessed = flowerWeight + trimWeight;
            const yieldPercentage = wetWeight > 0 ? ((totalProcessed / wetWeight) * 100).toFixed(1) : 0;
            
            if (totalProcessed > 0) {
                document.getElementById('weightSummary').style.display = 'block';
                document.getElementById('totalProcessed').textContent = totalProcessed.toFixed(1) + 'g';
                document.getElementById('yieldPercentage').textContent = yieldPercentage + '%';
            } else {
                document.getElementById('weightSummary').style.display = 'none';
            }
        }

        function showStatusMessage(message, type) {
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = message;
            statusMessage.className = `status-message ${type}`;
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>