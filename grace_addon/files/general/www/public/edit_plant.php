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
    <title>CultivationTracker - Edit Plant</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="page-header">
            <div>
                <h1>Edit Plant</h1>
                <p class="subtitle">Comprehensive plant information management</p>
            </div>
            <div class="header-actions">
                <a href="all_plants.php" class="btn btn-secondary">← Back to Plants</a>
            </div>
        </div>

        <form id="editPlantForm" class="modern-form" action="handle_edit_plant.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="plantId" name="plantId" value="">
            
            <!-- Basic Information Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Basic Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="trackingNumber">Tracking Number</label>
                            <input type="text" id="trackingNumber" name="trackingNumber" class="form-control" readonly>
                            <small class="form-text">System generated unique identifier</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="plantTag">Plant Tag</label>
                            <input type="text" id="plantTag" name="plantTag" class="form-control" placeholder="Custom plant identifier">
                        </div>
                        
                        <div class="form-group">
                            <label for="geneticsName">Genetics *</label>
                            <select id="geneticsName" name="geneticsName" class="form-control" required>
                                <option value="" disabled selected>Select Genetics</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="growthStage">Growth Stage *</label>
                            <select id="growthStage" name="growthStage" class="form-control" required>
                                <option value="Clone">Clone</option>
                                <option value="Veg">Vegetative</option>
                                <option value="Flower">Flowering</option>
                                <option value="Mother">Mother Plant</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="roomName">Room *</label>
                            <select id="roomName" name="roomName" class="form-control" required>
                                <option value="" disabled selected>Select Room</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Growing">Growing</option>
                                <option value="Harvested">Harvested</option>
                                <option value="Destroyed">Destroyed</option>
                                <option value="Sent">Sent</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Source Information Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Source Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="sourceType">Source Type</label>
                            <select id="sourceType" name="sourceType" class="form-control">
                                <option value="">Select Source</option>
                                <option value="mother">From Mother Plant</option>
                                <option value="seed">From Seed</option>
                                <option value="clone">Clone/Cutting</option>
                                <option value="purchased">Purchased</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="motherPlantGroup" style="display: none;">
                            <label for="motherId">Mother Plant</label>
                            <select id="motherId" name="motherId" class="form-control">
                                <option value="">Select Mother Plant</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="seedStockGroup" style="display: none;">
                            <label for="seedStockId">Seed Stock</label>
                            <select id="seedStockId" name="seedStockId" class="form-control">
                                <option value="">Select Seed Stock</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="isMother">
                                <input type="checkbox" id="isMother" name="isMother" value="1">
                                This is a Mother Plant
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dates & Timeline Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Dates & Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="dateCreated">Date Created</label>
                            <input type="datetime-local" id="dateCreated" name="dateCreated" class="form-control">
                            <small class="form-text">When this plant was first added to the system</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="dateStageChanged">Stage Change Date</label>
                            <input type="datetime-local" id="dateStageChanged" name="dateStageChanged" class="form-control">
                            <small class="form-text">When plant moved to current stage</small>
                        </div>
                        
                        <div class="form-group" id="harvestDateGroup" style="display: none;">
                            <label for="dateHarvested">Harvest Date</label>
                            <input type="datetime-local" id="dateHarvested" name="dateHarvested" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photos Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Plant Photos</h3>
                </div>
                <div class="card-body">
                    <div class="photo-section">
                        <div class="current-photos" id="currentPhotos">
                            <!-- Current photos will be loaded here -->
                        </div>
                        
                        <div class="photo-upload-section">
                            <div class="upload-options">
                                <button type="button" class="btn btn-primary" onclick="triggerFileUpload()">
                                    📁 Upload Photo
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="openCamera()" id="cameraBtn">
                                    📷 Take Photo
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
            </div>

            <!-- Harvest Information Card -->
            <div class="form-card" id="harvestInfoCard" style="display: none;">
                <div class="card-header">
                    <h3>Harvest Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="wetWeight">Wet Weight (grams)</label>
                            <input type="number" id="wetWeight" name="wetWeight" class="form-control" step="0.1" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="dryWeight">Dry Weight (grams)</label>
                            <input type="number" id="dryWeight" name="dryWeight" class="form-control" step="0.1" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="flowerWeight">Flower Weight (grams)</label>
                            <input type="number" id="flowerWeight" name="flowerWeight" class="form-control" step="0.1" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="trimWeight">Trim Weight (grams)</label>
                            <input type="number" id="trimWeight" name="trimWeight" class="form-control" step="0.1" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destruction Information Card -->
            <div class="form-card" id="destructionInfoCard" style="display: none;">
                <div class="card-header">
                    <h3>Destruction Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="destructionReason">Reason for Destruction *</label>
                        <select id="destructionReason" name="destructionReason" class="form-control">
                            <option value="">Select Reason</option>
                            <option value="disease">Disease/Infection</option>
                            <option value="pests">Pest Infestation</option>
                            <option value="poor_growth">Poor Growth/Development</option>
                            <option value="hermaphrodite">Hermaphrodite</option>
                            <option value="overcrowding">Overcrowding</option>
                            <option value="quality_control">Quality Control</option>
                            <option value="compliance">Compliance Requirement</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="destructionNotes">Destruction Notes</label>
                        <textarea id="destructionNotes" name="destructionNotes" class="form-control" rows="3" placeholder="Additional details about the destruction..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="destructionDate">Destruction Date</label>
                        <input type="datetime-local" id="destructionDate" name="destructionDate" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Notes & Observations</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="notes">Plant Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Record observations, treatments, issues, or other relevant information..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    💾 Update Plant
                </button>
                <a href="all_plants.php" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </main>

    <script src="js/plant-edit.js"></script>
    <script>
        // Initialize the edit plant form
        const urlParams = new URLSearchParams(window.location.search);
        const plantId = urlParams.get('id');
        
        if (!plantId) {
            window.location.href = 'all_plants.php';
        }

        document.getElementById('plantId').value = plantId;
        
        // Load plant data and initialize form
        loadPlantData(plantId);
        loadFormData();
        
        // Status change handlers
        document.getElementById('status').addEventListener('change', handleStatusChange);
        document.getElementById('sourceType').addEventListener('change', handleSourceTypeChange);
    </script>
</body>
</html>