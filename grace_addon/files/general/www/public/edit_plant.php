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
    <title>CultivationTracker - Edit Plant</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>✏️ Edit Plant</h1>
                <p style="color: var(--text-secondary); margin: 0;">Comprehensive plant information management and tracking</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="all_plants.php" class="modern-btn secondary">← Back to Plants</a>
                <button type="button" onclick="viewPlantSummary()" class="modern-btn secondary" id="viewSummaryBtn" style="display: none;">👁️ View Summary</button>
            </div>
        </div>

        <div class="edit-plant-form">
            <form id="editPlantForm" action="handle_edit_plant.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="plantId" name="plantId" value="">
                
                <!-- Basic Information Card -->
                <div class="modern-card form-section">
                    <h3>ℹ️ Basic Information</h3>
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="trackingNumber">Tracking Number</label>
                            <input type="text" id="trackingNumber" name="trackingNumber" readonly class="readonly-field">
                            <small>System generated unique identifier</small>
                        </div>
                        
                        <div class="field-group">
                            <label for="plantTag">Plant Tag</label>
                            <input type="text" id="plantTag" name="plantTag" placeholder="Custom plant identifier">
                            <small>Optional custom identifier for this plant</small>
                        </div>
                        
                        <div class="field-group">
                            <label for="geneticsName">Genetics *</label>
                            <select id="geneticsName" name="geneticsName" required>
                                <option value="" disabled selected>Select Genetics</option>
                            </select>
                            <small>The strain/genetics of this plant</small>
                        </div>
                        
                        <div class="field-group">
                            <label for="growthStage">Growth Stage *</label>
                            <select id="growthStage" name="growthStage" required>
                                <option value="Clone">🌿 Clone</option>
                                <option value="Veg">🌱 Vegetative</option>
                                <option value="Flower">🌸 Flowering</option>
                                <option value="Mother">👑 Mother Plant</option>
                            </select>
                            <small>Current growth stage of the plant</small>
                        </div>
                        
                        <div class="field-group">
                            <label for="roomName">Room *</label>
                            <select id="roomName" name="roomName" required>
                                <option value="" disabled selected>Select Room</option>
                            </select>
                            <small>Current location of the plant</small>
                        </div>
                        
                        <div class="field-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="Growing">🌱 Growing</option>
                                <option value="Harvested">✂️ Harvested</option>
                                <option value="Destroyed">🗑️ Destroyed</option>
                                <option value="Sent">📦 Sent</option>
                            </select>
                            <small>Current status of the plant</small>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" id="isMother" name="isMother" value="1" style="margin-right: 0.5rem;">
                            <span>👑 This is a Mother Plant</span>
                        </label>
                        <small style="color: var(--text-secondary); margin-left: 1.5rem;">Check if this plant is designated as a mother plant for cloning</small>
                    </div>
                </div>

                <!-- Source Information Card -->
                <div class="modern-card form-section">
                    <h3>🔗 Source Information</h3>
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="sourceType">Source Type</label>
                            <select id="sourceType" name="sourceType">
                                <option value="">Select Source</option>
                                <option value="mother">👑 From Mother Plant</option>
                                <option value="seed">🌰 From Seed</option>
                                <option value="clone">🌿 Clone/Cutting</option>
                                <option value="purchased">💰 Purchased</option>
                            </select>
                            <small>How this plant was obtained</small>
                        </div>
                        
                        <div id="motherPlantGroup" class="field-group conditional-field hidden">
                            <label for="motherId">Mother Plant</label>
                            <select id="motherId" name="motherId">
                                <option value="">Select Mother Plant</option>
                            </select>
                            <small>The mother plant this was cloned from</small>
                        </div>
                        
                        <div id="seedStockGroup" class="field-group conditional-field hidden">
                            <label for="seedStockId">Seed Stock</label>
                            <select id="seedStockId" name="seedStockId">
                                <option value="">Select Seed Stock</option>
                            </select>
                            <small>The seed batch this plant came from</small>
                        </div>
                    </div>
                </div>

                <!-- Dates & Timeline Card -->
                <div class="modern-card form-section">
                    <h3>📅 Dates & Timeline</h3>
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="dateCreated">Date Created</label>
                            <input type="datetime-local" id="dateCreated" name="dateCreated">
                            <small>When this plant was first added to the system</small>
                        </div>
                        
                        <div class="field-group">
                            <label for="dateStageChanged">Stage Change Date</label>
                            <input type="datetime-local" id="dateStageChanged" name="dateStageChanged">
                            <small>When plant moved to current stage</small>
                        </div>
                        
                        <div id="harvestDateGroup" class="field-group conditional-field hidden">
                            <label for="dateHarvested">Harvest Date</label>
                            <input type="datetime-local" id="dateHarvested" name="dateHarvested">
                            <small>When this plant was harvested</small>
                        </div>
                        
                        <div id="destructionDateGroup" class="field-group conditional-field hidden">
                            <label for="destructionDate">Destruction Date</label>
                            <input type="datetime-local" id="destructionDate" name="destructionDate">
                            <small>When this plant was destroyed</small>
                        </div>
                    </div>
                </div>

            <!-- Photos Card -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>📸 Plant Photos</h3>
                <div style="margin-top: 1rem;">
                    <div id="currentPhotos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <!-- Current photos will be loaded here -->
                    </div>
                    
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                        <button type="button" class="modern-btn secondary" onclick="triggerFileUpload()">
                            📁 Upload Photos
                        </button>
                        <button type="button" class="modern-btn secondary" onclick="openCamera()" id="cameraBtn">
                            📷 Take Photo
                        </button>
                    </div>
                    
                    <input type="file" id="photoUpload" name="photos[]" multiple accept="image/*" style="display: none;">
                    
                    <div id="cameraSection" style="display: none; text-align: center; margin-top: 1rem;">
                        <video id="cameraVideo" autoplay playsinline style="max-width: 100%; height: auto; border-radius: 8px;"></video>
                        <canvas id="cameraCanvas" style="display: none;"></canvas>
                        <div style="margin-top: 1rem;">
                            <button type="button" class="modern-btn" onclick="capturePhoto()">📸 Capture</button>
                            <button type="button" class="modern-btn secondary" onclick="closeCamera()">❌ Cancel</button>
                        </div>
                    </div>
                    
                    <div id="photoPreview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;"></div>
                </div>
            </div>

                <!-- Harvest Information Card -->
                <div class="modern-card form-section conditional-field hidden" id="harvestInfoCard">
                    <h3>✂️ Harvest Information</h3>
                    <div class="form-grid-2">
                        <div class="field-group weight-input">
                            <label for="wetWeight">💧 Wet Weight</label>
                            <input type="number" id="wetWeight" name="wetWeight" step="0.1" min="0" placeholder="0.0">
                            <small>Fresh weight immediately after harvest</small>
                        </div>
                        
                        <div class="field-group weight-input">
                            <label for="dryWeight">🏺 Dry Weight</label>
                            <input type="number" id="dryWeight" name="dryWeight" step="0.1" min="0" placeholder="0.0">
                            <small>Weight after drying process</small>
                        </div>
                        
                        <div class="field-group weight-input">
                            <label for="flowerWeight">🌸 Flower Weight</label>
                            <input type="number" id="flowerWeight" name="flowerWeight" step="0.1" min="0" placeholder="0.0">
                            <small>Usable flower weight</small>
                        </div>
                        
                        <div class="field-group weight-input">
                            <label for="trimWeight">🍃 Trim Weight</label>
                            <input type="number" id="trimWeight" name="trimWeight" step="0.1" min="0" placeholder="0.0">
                            <small>Trim and leaf weight</small>
                        </div>
                    </div>
                </div>

                <!-- Destruction Information Card -->
                <div class="modern-card form-section conditional-field hidden" id="destructionInfoCard">
                    <h3>🗑️ Destruction Information</h3>
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="destructionReason">Reason for Destruction *</label>
                            <select id="destructionReason" name="destructionReason">
                                <option value="">Select Reason</option>
                                <option value="disease">🦠 Disease/Infection</option>
                                <option value="pests">🐛 Pest Infestation</option>
                                <option value="poor_growth">📉 Poor Growth/Development</option>
                                <option value="hermaphrodite">⚧ Hermaphrodite</option>
                                <option value="overcrowding">🏠 Overcrowding</option>
                                <option value="quality_control">🔍 Quality Control</option>
                                <option value="compliance">📋 Compliance Requirement</option>
                                <option value="other">❓ Other</option>
                            </select>
                            <small>Required when plant status is destroyed</small>
                        </div>
                    </div>
                    
                    <div class="field-group" style="margin-top: 1rem;">
                        <label for="destructionNotes">Destruction Notes</label>
                        <textarea id="destructionNotes" name="destructionNotes" rows="3" placeholder="Additional details about the destruction..."></textarea>
                        <small>Optional additional information about the destruction</small>
                    </div>
                </div>

                <!-- Notes Card -->
                <div class="modern-card form-section">
                    <h3>📝 Notes & Observations</h3>
                    <div class="field-group">
                        <label for="notes">Plant Notes</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Record observations, treatments, issues, or other relevant information..."></textarea>
                        <small>Optional notes about this plant's condition, treatments, or observations</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="modern-btn" style="font-size: 1.1rem; padding: 1rem 2rem;">
                        💾 Update Plant
                    </button>
                    <a href="all_plants.php" class="modern-btn secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Initialize the edit plant form
        const urlParams = new URLSearchParams(window.location.search);
        const plantId = urlParams.get('id');
        
        if (!plantId) {
            window.location.href = 'all_plants.php';
        }

        document.getElementById('plantId').value = plantId;
        
        // Show view summary button
        document.getElementById('viewSummaryBtn').style.display = 'block';
        
        // Load plant data and initialize form
        loadPlantData(plantId);
        loadFormData();
        
        // Status change handlers
        document.getElementById('status').addEventListener('change', handleStatusChange);
        document.getElementById('sourceType').addEventListener('change', handleSourceTypeChange);

        // View plant summary function
        function viewPlantSummary() {
            window.open(`plant_summary.php?id=${plantId}`, '_blank');
        }

        // Enhanced status change handler with animations
        function handleStatusChange() {
            const status = document.getElementById('status').value;
            const harvestCard = document.getElementById('harvestInfoCard');
            const destructionCard = document.getElementById('destructionInfoCard');
            const harvestDateGroup = document.getElementById('harvestDateGroup');
            const destructionDateGroup = document.getElementById('destructionDateGroup');
            
            // Hide all status-specific sections first
            hideConditionalField(harvestCard);
            hideConditionalField(destructionCard);
            hideConditionalField(harvestDateGroup);
            hideConditionalField(destructionDateGroup);
            
            // Show relevant sections based on status
            if (status === 'Harvested') {
                showConditionalField(harvestCard);
                showConditionalField(harvestDateGroup);
            } else if (status === 'Destroyed') {
                showConditionalField(destructionCard);
                showConditionalField(destructionDateGroup);
                document.getElementById('destructionReason').required = true;
            } else {
                document.getElementById('destructionReason').required = false;
            }
        }

        // Enhanced source type change handler with animations
        function handleSourceTypeChange() {
            const sourceType = document.getElementById('sourceType').value;
            const motherGroup = document.getElementById('motherPlantGroup');
            const seedGroup = document.getElementById('seedStockGroup');
            
            hideConditionalField(motherGroup);
            hideConditionalField(seedGroup);
            
            if (sourceType === 'mother' || sourceType === 'clone') {
                showConditionalField(motherGroup);
                loadMotherPlants();
            } else if (sourceType === 'seed') {
                showConditionalField(seedGroup);
                loadSeedStocks();
            }
        }

        // Utility functions for conditional field animations
        function showConditionalField(element) {
            element.classList.remove('hidden');
            element.classList.add('visible');
        }

        function hideConditionalField(element) {
            element.classList.remove('visible');
            element.classList.add('hidden');
        }

        // Load plant data function
        async function loadPlantData(plantId) {
            try {
                const response = await fetch(`get_plant_details.php?id=${plantId}`);
                const plant = await response.json();
                
                if (plant.error) {
                    showStatusMessage(plant.error, 'error');
                    return;
                }
                
                // Populate basic fields
                document.getElementById('trackingNumber').value = plant.tracking_number || '';
                document.getElementById('plantTag').value = plant.plant_tag || '';
                document.getElementById('geneticsName').value = plant.genetics_id || '';
                document.getElementById('growthStage').value = plant.growth_stage || '';
                document.getElementById('roomName').value = plant.room_id || '';
                document.getElementById('status').value = plant.status || '';
                document.getElementById('isMother').checked = plant.is_mother == 1;
                document.getElementById('notes').value = plant.notes || '';
                
                // Populate source information
                if (plant.source_type) {
                    document.getElementById('sourceType').value = plant.source_type;
                    handleSourceTypeChange();
                    
                    if (plant.mother_id) {
                        setTimeout(() => {
                            document.getElementById('motherId').value = plant.mother_id;
                        }, 500);
                    }
                    if (plant.seed_stock_id) {
                        setTimeout(() => {
                            document.getElementById('seedStockId').value = plant.seed_stock_id;
                        }, 500);
                    }
                }
                
                // Populate dates
                if (plant.date_created) {
                    document.getElementById('dateCreated').value = formatDateForInput(plant.date_created);
                }
                if (plant.date_stage_changed) {
                    document.getElementById('dateStageChanged').value = formatDateForInput(plant.date_stage_changed);
                }
                if (plant.date_harvested) {
                    document.getElementById('dateHarvested').value = formatDateForInput(plant.date_harvested);
                }
                
                // Populate harvest information
                if (plant.wet_weight) document.getElementById('wetWeight').value = plant.wet_weight;
                if (plant.dry_weight) document.getElementById('dryWeight').value = plant.dry_weight;
                if (plant.flower_weight) document.getElementById('flowerWeight').value = plant.flower_weight;
                if (plant.trim_weight) document.getElementById('trimWeight').value = plant.trim_weight;
                
                // Populate destruction information
                if (plant.destruction_reason) {
                    document.getElementById('destructionReason').value = plant.destruction_reason;
                    document.getElementById('destructionNotes').value = plant.destruction_notes || '';
                    if (plant.destruction_date) {
                        document.getElementById('destructionDate').value = formatDateForInput(plant.destruction_date);
                    }
                }
                
                // Handle status-specific fields
                handleStatusChange();
                
            } catch (error) {
                console.error('Error loading plant data:', error);
                showStatusMessage('Error loading plant data', 'error');
            }
        }

        // Load form dropdown data
        async function loadFormData() {
            try {
                // Load genetics
                const geneticsResponse = await fetch('get_genetics.php');
                const genetics = await geneticsResponse.json();
                const geneticsSelect = document.getElementById('geneticsName');
                genetics.forEach(g => {
                    const option = document.createElement('option');
                    option.value = g.id;
                    option.textContent = g.name;
                    geneticsSelect.appendChild(option);
                });

                // Load all rooms
                const roomsResponse = await fetch('get_all_rooms.php');
                const rooms = await roomsResponse.json();
                const roomSelect = document.getElementById('roomName');
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = `${room.name} (${room.room_type})`;
                    roomSelect.appendChild(option);
                });

            } catch (error) {
                console.error('Error loading form data:', error);
            }
        }

        // Load mother plants
        async function loadMotherPlants() {
            try {
                const response = await fetch('get_mother_plants.php');
                const mothers = await response.json();
                const motherSelect = document.getElementById('motherId');
                motherSelect.innerHTML = '<option value="">Select Mother Plant</option>';
                mothers.forEach(mother => {
                    const option = document.createElement('option');
                    option.value = mother.id;
                    option.textContent = `${mother.genetics_name} - ${mother.plant_tag || 'ID: ' + mother.id}`;
                    motherSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading mother plants:', error);
            }
        }

        // Load seed stocks
        async function loadSeedStocks() {
            try {
                const response = await fetch('get_seed_stocks.php');
                const seeds = await response.json();
                const seedSelect = document.getElementById('seedStockId');
                seedSelect.innerHTML = '<option value="">Select Seed Stock</option>';
                seeds.forEach(seed => {
                    const option = document.createElement('option');
                    option.value = seed.id;
                    option.textContent = `${seed.genetics_name} - ${seed.batch_name}`;
                    seedSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading seed stocks:', error);
            }
        }

        // Utility functions
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().slice(0, 16);
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

        // Photo functionality
        function triggerFileUpload() {
            document.getElementById('photoUpload').click();
        }

        document.getElementById('photoUpload').addEventListener('change', function(e) {
            const files = e.target.files;
            const preview = document.getElementById('photoPreview');
            
            for (let file of files) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const photoDiv = document.createElement('div');
                        photoDiv.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden;';
                        photoDiv.innerHTML = `
                            <img src="${e.target.result}" style="width: 100%; height: 150px; object-fit: cover;">
                            <button type="button" onclick="this.parentElement.remove()" 
                                    style="position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.8); color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer;">×</button>
                        `;
                        preview.appendChild(photoDiv);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // Camera functionality
        let cameraStream = null;

        function openCamera() {
            const cameraSection = document.getElementById('cameraSection');
            const video = document.getElementById('cameraVideo');
            
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    cameraStream = stream;
                    video.srcObject = stream;
                    cameraSection.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error accessing camera:', error);
                    showStatusMessage('Unable to access camera', 'error');
                });
        }

        function closeCamera() {
            const cameraSection = document.getElementById('cameraSection');
            const video = document.getElementById('cameraVideo');
            
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            
            video.srcObject = null;
            cameraSection.style.display = 'none';
        }

        function capturePhoto() {
            const video = document.getElementById('cameraVideo');
            const canvas = document.getElementById('cameraCanvas');
            const preview = document.getElementById('photoPreview');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            
            canvas.toBlob(blob => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoDiv = document.createElement('div');
                    photoDiv.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden;';
                    photoDiv.innerHTML = `
                        <img src="${e.target.result}" style="width: 100%; height: 150px; object-fit: cover;">
                        <button type="button" onclick="this.parentElement.remove()" 
                                style="position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.8); color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer;">×</button>
                    `;
                    preview.appendChild(photoDiv);
                };
                reader.readAsDataURL(blob);
            }, 'image/jpeg', 0.8);
            
            closeCamera();
        }

        // Load current photos
        function loadCurrentPhotos(plantId) {
            fetch(`get_plant_photos.php?plant_id=${plantId}`)
                .then(response => response.json())
                .then(photos => {
                    const container = document.getElementById('currentPhotos');
                    container.innerHTML = '';
                    
                    photos.forEach(photo => {
                        const photoDiv = document.createElement('div');
                        photoDiv.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden;';
                        photoDiv.innerHTML = `
                            <img src="${photo.file_path}" style="width: 100%; height: 150px; object-fit: cover;">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px; font-size: 0.8rem;">
                                ${new Date(photo.date_taken).toLocaleDateString()}
                            </div>
                        `;
                        container.appendChild(photoDiv);
                    });
                })
                .catch(error => console.error('Error loading photos:', error));
        }

        // Form submission handler
        document.getElementById('editPlantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Add captured photos if any
            const photoPreview = document.getElementById('photoPreview');
            const images = photoPreview.querySelectorAll('img');
            
            images.forEach((img, index) => {
                // Convert image to blob and add to form data
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                ctx.drawImage(img, 0, 0);
                
                canvas.toBlob(blob => {
                    formData.append('captured_photos[]', blob, `captured_${index}.jpg`);
                }, 'image/jpeg', 0.8);
            });
            
            // Submit form
            fetch('handle_edit_plant.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'all_plants.php?success=' + encodeURIComponent('Plant updated successfully');
                } else {
                    throw new Error('Failed to update plant');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusMessage('Error updating plant', 'error');
            });
        });

        // Load photos when plant data is loaded
        if (plantId) {
            loadCurrentPhotos(plantId);
        }

        // Check for success/error messages
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