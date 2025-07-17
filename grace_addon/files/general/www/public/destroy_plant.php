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
    <title>CultivationTracker - Destroy Plant</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="page-header">
            <div>
                <h1>Destroy Plant</h1>
                <p class="subtitle">Record plant destruction with detailed reasoning</p>
            </div>
            <div class="header-actions">
                <a href="all_plants.php" class="btn btn-secondary">← Back to Plants</a>
            </div>
        </div>

        <form id="destroyForm" class="modern-form" action="handle_destroy_plant.php" method="post" enctype="multipart/form-data">
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

            <!-- Destruction Details Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Destruction Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="destructionDate">Destruction Date & Time *</label>
                            <input type="datetime-local" id="destructionDate" name="destructionDate" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="destructionReason">Reason for Destruction *</label>
                            <select id="destructionReason" name="destructionReason" class="form-control" required>
                                <option value="" disabled selected>Select Reason</option>
                                <option value="disease">Disease/Infection</option>
                                <option value="pests">Pest Infestation</option>
                                <option value="poor_growth">Poor Growth/Development</option>
                                <option value="hermaphrodite">Hermaphrodite</option>
                                <option value="overcrowding">Overcrowding</option>
                                <option value="quality_control">Quality Control</option>
                                <option value="compliance">Compliance Requirement</option>
                                <option value="contamination">Contamination</option>
                                <option value="genetic_defect">Genetic Defect</option>
                                <option value="space_management">Space Management</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="otherReasonGroup" style="display: none;">
                            <label for="otherReason">Specify Other Reason *</label>
                            <input type="text" id="otherReason" name="otherReason" class="form-control" placeholder="Please specify the reason">
                        </div>
                        
                        <div class="form-group">
                            <label for="destructionMethod">Destruction Method</label>
                            <select id="destructionMethod" name="destructionMethod" class="form-control">
                                <option value="">Select Method</option>
                                <option value="composting">Composting</option>
                                <option value="incineration">Incineration</option>
                                <option value="grinding">Grinding/Mulching</option>
                                <option value="burial">Burial</option>
                                <option value="chemical">Chemical Treatment</option>
                                <option value="other_method">Other Method</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weight Information Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Weight Information</h3>
                    <small class="text-muted">Record weight for compliance tracking</small>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="plantWeight">Plant Weight (g)</label>
                            <input type="number" id="plantWeight" name="plantWeight" class="form-control" step="0.1" min="0">
                            <small class="form-text">Total weight of plant material destroyed</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="rootWeight">Root Weight (g)</label>
                            <input type="number" id="rootWeight" name="rootWeight" class="form-control" step="0.1" min="0">
                            <small class="form-text">Weight of root system if applicable</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="soilWeight">Soil/Medium Weight (g)</label>
                            <input type="number" id="soilWeight" name="soilWeight" class="form-control" step="0.1" min="0">
                            <small class="form-text">Weight of growing medium disposed</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="totalWeight">Total Weight (g)</label>
                            <input type="number" id="totalWeight" name="totalWeight" class="form-control" step="0.1" min="0" readonly>
                            <small class="form-text">Automatically calculated total</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Photos Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Evidence Photos</h3>
                    <small class="text-muted">Document the condition and destruction process</small>
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

            <!-- Detailed Notes Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Detailed Notes</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="destructionNotes">Destruction Notes *</label>
                        <textarea id="destructionNotes" name="destructionNotes" class="form-control" rows="5" required placeholder="Provide detailed explanation of the destruction reason, condition of the plant, and any relevant observations..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="witnessName">Witness Name</label>
                        <input type="text" id="witnessName" name="witnessName" class="form-control" placeholder="Name of person witnessing destruction">
                    </div>
                    
                    <div class="form-group">
                        <label for="complianceNotes">Compliance Notes</label>
                        <textarea id="complianceNotes" name="complianceNotes" class="form-control" rows="3" placeholder="Any additional compliance or regulatory notes..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Confirmation Card -->
            <div class="form-card" style="border: 2px solid var(--danger-color);">
                <div class="card-header" style="background-color: var(--danger-color); color: white;">
                    <h3>⚠️ Destruction Confirmation</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="confirmDestruction">
                            <input type="checkbox" id="confirmDestruction" name="confirmDestruction" value="1" required>
                            I confirm that this plant will be permanently destroyed and this action cannot be undone
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmCompliance">
                            <input type="checkbox" id="confirmCompliance" name="confirmCompliance" value="1" required>
                            I confirm that this destruction complies with all applicable regulations and requirements
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-danger btn-lg">
                    🗑️ Destroy Plant
                </button>
                <a href="all_plants.php" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </main>

    <script src="js/plant-edit.js"></script>
    <script>
        // Initialize the destroy form
        const urlParams = new URLSearchParams(window.location.search);
        const plantId = urlParams.get('id');
        
        if (!plantId) {
            window.location.href = 'all_plants.php';
        }

        document.getElementById('plantId').value = plantId;
        
        // Set default destruction date to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('destructionDate').value = now.toISOString().slice(0, 16);
        
        // Load plant data
        loadPlantInfo(plantId);
        
        // Handle reason selection
        document.getElementById('destructionReason').addEventListener('change', function() {
            const otherGroup = document.getElementById('otherReasonGroup');
            if (this.value === 'other') {
                otherGroup.style.display = 'block';
                document.getElementById('otherReason').required = true;
            } else {
                otherGroup.style.display = 'none';
                document.getElementById('otherReason').required = false;
            }
        });
        
        // Weight calculation
        function calculateTotalWeight() {
            const plantWeight = parseFloat(document.getElementById('plantWeight').value) || 0;
            const rootWeight = parseFloat(document.getElementById('rootWeight').value) || 0;
            const soilWeight = parseFloat(document.getElementById('soilWeight').value) || 0;
            
            const total = plantWeight + rootWeight + soilWeight;
            document.getElementById('totalWeight').value = total.toFixed(1);
        }
        
        document.getElementById('plantWeight').addEventListener('input', calculateTotalWeight);
        document.getElementById('rootWeight').addEventListener('input', calculateTotalWeight);
        document.getElementById('soilWeight').addEventListener('input', calculateTotalWeight);

        async function loadPlantInfo(plantId) {
            try {
                const response = await fetch(`get_plant_details.php?id=${plantId}`);
                const plant = await response.json();
                
                if (plant.error) {
                    showStatusMessage(plant.error, 'error');
                    return;
                }
                
                const plantInfo = document.getElementById('plantInfo');
                const daysOld = Math.floor((new Date() - new Date(plant.date_created)) / (1000 * 60 * 60 * 24));
                
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
                            <strong>Growth Stage:</strong> ${plant.growth_stage}
                        </div>
                        <div class="info-item">
                            <strong>Days Old:</strong> ${daysOld}
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong> ${plant.status}
                        </div>
                        <div class="info-item">
                            <strong>Created:</strong> ${new Date(plant.date_created).toLocaleDateString()}
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading plant info:', error);
                showStatusMessage('Error loading plant information', 'error');
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