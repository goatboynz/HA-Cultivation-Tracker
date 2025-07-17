// Enhanced Plant Edit JavaScript
let cameraStream = null;
let capturedPhotos = [];

// Load plant data
async function loadPlantData(plantId) {
    try {
        const response = await fetch(`get_plant_details.php?id=${plantId}`);
        const plant = await response.json();
        
        if (plant.error) {
            showStatusMessage(plant.error, 'error');
            return;
        }
        
        // Populate basic fields
        document.getElementById('trackingNumber').value = plant.tracking_number || 'N/A';
        document.getElementById('plantTag').value = plant.plant_tag || '';
        document.getElementById('geneticsName').value = plant.genetics_id || '';
        document.getElementById('growthStage').value = plant.growth_stage || '';
        document.getElementById('roomName').value = plant.room_id || '';
        document.getElementById('status').value = plant.status || '';
        document.getElementById('isMother').checked = plant.is_mother == 1;
        document.getElementById('notes').value = plant.notes || '';
        
        // Populate source information
        if (plant.mother_id) {
            document.getElementById('sourceType').value = 'mother';
            document.getElementById('motherId').value = plant.mother_id;
            showSourceFields('mother');
        } else if (plant.seed_stock_id) {
            document.getElementById('sourceType').value = 'seed';
            document.getElementById('seedStockId').value = plant.seed_stock_id;
            showSourceFields('seed');
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
        
        // Load plant photos
        loadPlantPhotos(plantId);
        
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

        // Load mother plants
        const mothersResponse = await fetch('get_mother_plants.php');
        const mothers = await mothersResponse.json();
        const motherSelect = document.getElementById('motherId');
        mothers.forEach(mother => {
            const option = document.createElement('option');
            option.value = mother.id;
            option.textContent = `${mother.genetics_name} - ${mother.plant_tag || 'ID: ' + mother.id}`;
            motherSelect.appendChild(option);
        });

        // Load seed stocks
        const seedsResponse = await fetch('get_seed_stocks.php');
        const seeds = await seedsResponse.json();
        const seedSelect = document.getElementById('seedStockId');
        seeds.forEach(seed => {
            const option = document.createElement('option');
            option.value = seed.id;
            option.textContent = `${seed.genetics_name} - ${seed.batch_name}`;
            seedSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error loading form data:', error);
    }
}

// Handle status changes
function handleStatusChange() {
    const status = document.getElementById('status').value;
    const harvestCard = document.getElementById('harvestInfoCard');
    const destructionCard = document.getElementById('destructionInfoCard');
    const harvestDateGroup = document.getElementById('harvestDateGroup');
    
    // Show/hide relevant sections based on status
    if (status === 'Harvested') {
        harvestCard.style.display = 'block';
        harvestDateGroup.style.display = 'block';
        destructionCard.style.display = 'none';
    } else if (status === 'Destroyed') {
        destructionCard.style.display = 'block';
        harvestCard.style.display = 'none';
        harvestDateGroup.style.display = 'none';
    } else {
        harvestCard.style.display = 'none';
        destructionCard.style.display = 'none';
        harvestDateGroup.style.display = 'none';
    }
}

// Handle source type changes
function handleSourceTypeChange() {
    const sourceType = document.getElementById('sourceType').value;
    showSourceFields(sourceType);
}

function showSourceFields(sourceType) {
    const motherGroup = document.getElementById('motherPlantGroup');
    const seedGroup = document.getElementById('seedStockGroup');
    
    motherGroup.style.display = 'none';
    seedGroup.style.display = 'none';
    
    if (sourceType === 'mother' || sourceType === 'clone') {
        motherGroup.style.display = 'block';
    } else if (sourceType === 'seed') {
        seedGroup.style.display = 'block';
    }
}

// Photo management functions
function triggerFileUpload() {
    document.getElementById('photoUpload').click();
}

async function openCamera() {
    const cameraSection = document.getElementById('cameraSection');
    const video = document.getElementById('cameraVideo');
    
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            } 
        });
        video.srcObject = cameraStream;
        cameraSection.style.display = 'block';
    } catch (error) {
        console.error('Error accessing camera:', error);
        showStatusMessage('Unable to access camera. Please check permissions.', 'error');
    }
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
    const ctx = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    
    canvas.toBlob(blob => {
        const file = new File([blob], `plant-photo-${Date.now()}.jpg`, { type: 'image/jpeg' });
        addPhotoToPreview(file);
        capturedPhotos.push(file);
        closeCamera();
    }, 'image/jpeg', 0.8);
}

function addPhotoToPreview(file) {
    const preview = document.getElementById('photoPreview');
    const img = document.createElement('img');
    const container = document.createElement('div');
    container.className = 'photo-preview-item';
    
    img.src = URL.createObjectURL(file);
    img.onload = () => URL.revokeObjectURL(img.src);
    
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'btn btn-danger btn-sm';
    deleteBtn.innerHTML = '×';
    deleteBtn.onclick = () => container.remove();
    
    container.appendChild(img);
    container.appendChild(deleteBtn);
    preview.appendChild(container);
}

// Handle file upload
document.getElementById('photoUpload').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            addPhotoToPreview(file);
        }
    });
});

// Load existing plant photos
async function loadPlantPhotos(plantId) {
    try {
        const response = await fetch(`get_plant_photos.php?plant_id=${plantId}`);
        const photos = await response.json();
        
        const currentPhotos = document.getElementById('currentPhotos');
        currentPhotos.innerHTML = '';
        
        photos.forEach(photo => {
            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item';
            photoItem.innerHTML = `
                <img src="${photo.file_path}" alt="Plant photo">
                <div class="photo-actions">
                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePhoto(${photo.id})">×</button>
                </div>
            `;
            currentPhotos.appendChild(photoItem);
        });
    } catch (error) {
        console.error('Error loading photos:', error);
    }
}

// Delete photo
async function deletePhoto(photoId) {
    if (!confirm('Delete this photo?')) return;
    
    try {
        const response = await fetch('delete_plant_photo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `photo_id=${photoId}`
        });
        
        if (response.ok) {
            loadPlantPhotos(document.getElementById('plantId').value);
            showStatusMessage('Photo deleted successfully', 'success');
        }
    } catch (error) {
        console.error('Error deleting photo:', error);
        showStatusMessage('Error deleting photo', 'error');
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

// Form submission
document.getElementById('editPlantForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Add captured photos
    capturedPhotos.forEach((photo, index) => {
        formData.append(`captured_photos[]`, photo);
    });
    
    // Add uploaded photos
    const uploadedFiles = document.getElementById('photoUpload').files;
    Array.from(uploadedFiles).forEach(file => {
        formData.append('uploaded_photos[]', file);
    });
    
    try {
        const response = await fetch('handle_edit_plant.php', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            showStatusMessage('Plant updated successfully', 'success');
            setTimeout(() => {
                window.location.href = 'all_plants.php';
            }, 2000);
        } else {
            throw new Error('Failed to update plant');
        }
    } catch (error) {
        console.error('Error updating plant:', error);
        showStatusMessage('Error updating plant', 'error');
    }
});

// Check for camera support
if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
    document.getElementById('cameraBtn').style.display = 'none';
}