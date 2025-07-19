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
    <title>CultivationTracker - Harvest/Destroy/Send Plants</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>✂️ Harvest/Destroy/Send Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Process plants for harvest, destruction, or external transfer</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🔍 Filters</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="stageFilter">Growth Stage:</label>
                    <select id="stageFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Stages</option>
                        <option value="Clone">🌿 Clone</option>
                        <option value="Veg">🌱 Veg</option>
                        <option value="Flower">🌸 Flower</option>
                        <option value="Mother">👑 Mother</option>
                    </select>
                </div>
                <div>
                    <label for="roomFilter">Room:</label>
                    <select id="roomFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Rooms</option>
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
                    <label for="geneticsFilter">Genetics:</label>
                    <select id="geneticsFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Genetics</option>
                    </select>
                </div>
                <div>
                    <label for="ageFilter">Age Range:</label>
                    <select id="ageFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Ages</option>
                        <option value="0-30">0-30 days</option>
                        <option value="31-60">31-60 days</option>
                        <option value="61-90">61-90 days</option>
                        <option value="90+">90+ days</option>
                    </select>
                </div>
                <div>
                    <label for="motherFilter">Mother Plants:</label>
                    <select id="motherFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Plants</option>
                        <option value="1">👑 Mother Plants Only</option>
                        <option value="0">🌱 Regular Plants Only</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button onclick="clearFilters()" class="modern-btn secondary">🔄 Clear Filters</button>
                <span id="filterCount" style="padding: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;"></span>
            </div>
        </div>

        <!-- Action Selection -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🎯 Select Action</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="action">Action:</label>
                    <select id="action" name="action" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" required>
                        <option value="harvest">✂️ Harvest</option>
                        <option value="destroy">🗑️ Destroy</option>
                        <option value="send">📦 Send External</option>
                    </select>
                </div>
                
                <div id="companySelection" style="display: none;">
                    <label for="companyId">Company:</label>
                    <select id="companyId" name="companyId" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="" disabled selected>Select Company</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Plants Table -->
        <div class="modern-card">
            <h3>🌿 Available Plants</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="plantsTable" class="modern-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllCheckbox"></th>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Stage</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Age</th>
                            <th>Days in Stage</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody id="plantsTableBody">
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Plants Found</h3>
                <p>No plants match your current filter criteria</p>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="button" class="modern-btn" id="processSelectedButton">
                    <span id="processButtonText">Process Selected</span>
                </button>
                <div style="margin-top: 1rem;">
                    <span id="selectionCount" style="color: var(--text-secondary); font-size: 0.9rem;">0 plants selected</span>
                </div>
            </div>
        </div>

        <!-- Batch Operation Modal -->
        <div id="batchModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitle">Batch Operation</h3>
                    <button onclick="closeBatchModal()" class="close-btn">×</button>
                </div>
                <div class="modal-body">
                    <form id="batchForm">
                        <div class="form-group">
                            <label for="batchName">Batch Name:</label>
                            <input type="text" id="batchName" name="batchName" class="form-control" placeholder="Auto-generated if empty">
                        </div>
                        
                        <div id="destructionFields" style="display: none;">
                            <div class="form-group">
                                <label for="destructionReason">Destruction Reason: *</label>
                                <select id="destructionReason" name="destructionReason" class="form-control" required>
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
                            </div>
                            
                            <div class="form-group">
                                <label for="destructionMethod">Destruction Method:</label>
                                <select id="destructionMethod" name="destructionMethod" class="form-control">
                                    <option value="">Select Method</option>
                                    <option value="composting">🌱 Composting</option>
                                    <option value="grinding">⚙️ Grinding/Mulching</option>
                                    <option value="burning">🔥 Burning</option>
                                    <option value="burial">🕳️ Burial</option>
                                    <option value="other">❓ Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="witnessName">Witness Name:</label>
                                <input type="text" id="witnessName" name="witnessName" class="form-control" placeholder="Name of witness to destruction">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="batchNotes">Notes:</label>
                            <textarea id="batchNotes" name="batchNotes" class="form-control" rows="3" placeholder="Additional notes about this batch operation..."></textarea>
                        </div>
                        
                        <div id="weightInputs" class="form-group">
                            <h4>Individual Plant Weights</h4>
                            <div id="plantWeightsList">
                                <!-- Plant weight inputs will be generated here -->
                            </div>
                        </div>
                        
                        <div class="modal-actions">
                            <button type="button" onclick="processBatchOperation()" class="modern-btn">
                                <span id="modalProcessBtn">Process Batch</span>
                            </button>
                            <button type="button" onclick="closeBatchModal()" class="modern-btn secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </main>

    <script src="js/growcart.js"></script>
    <script>
        let allPlants = [];
        let filteredPlants = [];
        
        const plantsTableBody = document.getElementById('plantsTableBody');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const processSelectedButton = document.getElementById('processSelectedButton');
        const actionDropdown = document.getElementById('action');
        const companySelection = document.getElementById('companySelection');
        const companyDropdown = document.getElementById('companyId');

        // Load all plants data
        function loadPlants() {
            fetch('get_plants_for_harvest.php')
                .then(response => response.json())
                .then(plantsData => {
                    allPlants = plantsData;
                    populateFilters();
                    applyFilters();
                })
                .catch(error => {
                    console.error('Error fetching plant data:', error);
                    showStatusMessage('Error loading plants', 'error');
                });
        }

        // Populate filter dropdowns
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
            const genetics = [...new Set(allPlants.map(p => p.geneticsName).filter(g => g))];
            const geneticsFilter = document.getElementById('geneticsFilter');
            geneticsFilter.innerHTML = '<option value="">All Genetics</option>';
            genetics.forEach(genetic => {
                const option = document.createElement('option');
                option.value = genetic;
                option.textContent = genetic;
                geneticsFilter.appendChild(option);
            });
        }

        // Apply filters to plant list
        function applyFilters() {
            const stageFilter = document.getElementById('stageFilter').value;
            const roomFilter = document.getElementById('roomFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const geneticsFilter = document.getElementById('geneticsFilter').value;
            const ageFilter = document.getElementById('ageFilter').value;
            const motherFilter = document.getElementById('motherFilter').value;

            filteredPlants = allPlants.filter(plant => {
                let ageMatch = true;
                if (ageFilter) {
                    const age = parseInt(plant.age);
                    switch(ageFilter) {
                        case '0-30': ageMatch = age <= 30; break;
                        case '31-60': ageMatch = age >= 31 && age <= 60; break;
                        case '61-90': ageMatch = age >= 61 && age <= 90; break;
                        case '90+': ageMatch = age > 90; break;
                    }
                }

                return (!stageFilter || plant.growth_stage === stageFilter) &&
                       (!roomFilter || plant.room_name === roomFilter) &&
                       (!statusFilter || plant.status === statusFilter) &&
                       (!geneticsFilter || plant.geneticsName === geneticsFilter) &&
                       (!motherFilter || plant.is_mother == motherFilter) &&
                       ageMatch;
            });

            displayPlants();
            updateFilterCount();
        }

        // Display filtered plants in table
        function displayPlants() {
            const tbody = plantsTableBody;
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';
            selectAllCheckbox.checked = false;

            if (filteredPlants.length === 0) {
                noDataMessage.style.display = 'block';
                updateSelectionCount();
                return;
            }

            noDataMessage.style.display = 'none';

            filteredPlants.forEach(plant => {
                const row = tbody.insertRow();

                // Checkbox
                const checkboxCell = row.insertCell();
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'selectedPlants[]';
                checkbox.value = plant.id;
                checkbox.addEventListener('change', updateSelectionCount);
                checkboxCell.appendChild(checkbox);

                // Plant data
                row.insertCell().innerHTML = `<strong>${plant.tracking_number || 'N/A'}</strong>`;
                row.insertCell().textContent = plant.plant_tag || '-';
                row.insertCell().textContent = plant.geneticsName || 'Unknown';
                row.insertCell().innerHTML = `<span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span>`;
                row.insertCell().textContent = plant.room_name || 'Unassigned';
                row.insertCell().innerHTML = `<span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span>`;
                row.insertCell().textContent = `${plant.age} days`;
                row.insertCell().textContent = `${plant.days_in_stage} days`;
                row.insertCell().innerHTML = plant.is_mother == 1 ? '<span style="color: var(--accent-primary);">👑 Mother</span>' : '🌱 Regular';
            });

            updateSelectionCount();
        }

        // Update filter count display
        function updateFilterCount() {
            const filterCount = document.getElementById('filterCount');
            filterCount.textContent = `Showing ${filteredPlants.length} of ${allPlants.length} plants`;
        }

        // Update selection count
        function updateSelectionCount() {
            const selectedCheckboxes = plantsTableBody.querySelectorAll('input[type="checkbox"]:checked');
            const selectionCount = document.getElementById('selectionCount');
            const count = selectedCheckboxes.length;
            selectionCount.textContent = `${count} plant${count !== 1 ? 's' : ''} selected`;
            
            // Enable/disable process button
            processSelectedButton.disabled = count === 0;
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('stageFilter').value = '';
            document.getElementById('roomFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('geneticsFilter').value = '';
            document.getElementById('ageFilter').value = '';
            document.getElementById('motherFilter').value = '';
            applyFilters();
        }

        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', () => {
            const checkboxes = plantsTableBody.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
            updateSelectionCount();
        });

        // Handle "Process Selected" button click
        processSelectedButton.addEventListener('click', () => {
            const selectedCheckboxes = plantsTableBody.querySelectorAll('input[type="checkbox"]:checked');
            const selectedPlantIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            const selectedAction = actionDropdown.value;

            if (selectedPlantIds.length === 0) {
                showStatusMessage('Please select at least one plant to process.', 'error');
                return;
            }

            if (selectedAction === 'send' && !companyDropdown.value) {
                showStatusMessage('Please select a company for external sending.', 'error');
                return;
            }

            // Open batch modal for detailed input
            openBatchModal(selectedPlantIds, selectedAction);
        });

        function openBatchModal(plantIds, action) {
            const modal = document.getElementById('batchModal');
            const modalTitle = document.getElementById('modalTitle');
            const destructionFields = document.getElementById('destructionFields');
            const modalProcessBtn = document.getElementById('modalProcessBtn');
            
            // Set modal title and show/hide fields based on action
            if (action === 'destroy') {
                modalTitle.textContent = '🗑️ Batch Destruction';
                destructionFields.style.display = 'block';
                modalProcessBtn.textContent = '🗑️ Destroy Plants';
                document.getElementById('destructionReason').required = true;
            } else if (action === 'harvest') {
                modalTitle.textContent = '✂️ Batch Harvest';
                destructionFields.style.display = 'none';
                modalProcessBtn.textContent = '✂️ Harvest Plants';
                document.getElementById('destructionReason').required = false;
            } else {
                modalTitle.textContent = '📦 Send Plants';
                destructionFields.style.display = 'none';
                modalProcessBtn.textContent = '📦 Send Plants';
                document.getElementById('destructionReason').required = false;
            }
            
            // Generate weight inputs for selected plants
            generateWeightInputs(plantIds, action);
            
            // Store selected data for processing
            modal.dataset.plantIds = JSON.stringify(plantIds);
            modal.dataset.action = action;
            
            modal.style.display = 'flex';
        }

        function generateWeightInputs(plantIds, action) {
            const container = document.getElementById('plantWeightsList');
            container.innerHTML = '';
            
            plantIds.forEach(plantId => {
                const plant = filteredPlants.find(p => p.id == plantId);
                if (!plant) return;
                
                const weightItem = document.createElement('div');
                weightItem.className = 'plant-weight-item';
                
                if (action === 'harvest') {
                    weightItem.innerHTML = `
                        <label><strong>${plant.tracking_number}</strong><br>${plant.genetics_name}</label>
                        <input type="number" step="0.1" min="0" placeholder="Wet (g)" data-plant="${plantId}" data-type="wet">
                        <input type="number" step="0.1" min="0" placeholder="Dry (g)" data-plant="${plantId}" data-type="dry">
                        <input type="number" step="0.1" min="0" placeholder="Flower (g)" data-plant="${plantId}" data-type="flower">
                        <input type="number" step="0.1" min="0" placeholder="Trim (g)" data-plant="${plantId}" data-type="trim">
                    `;
                } else {
                    weightItem.innerHTML = `
                        <label><strong>${plant.tracking_number}</strong><br>${plant.genetics_name}</label>
                        <input type="number" step="0.1" min="0" placeholder="Weight (g)" data-plant="${plantId}" data-type="total">
                        <span></span>
                        <span></span>
                        <span></span>
                    `;
                }
                
                container.appendChild(weightItem);
            });
        }

        function closeBatchModal() {
            const modal = document.getElementById('batchModal');
            modal.style.display = 'none';
            
            // Reset form
            document.getElementById('batchForm').reset();
        }

        function processBatchOperation() {
            const modal = document.getElementById('batchModal');
            const plantIds = JSON.parse(modal.dataset.plantIds);
            const action = modal.dataset.action;
            
            // Collect form data
            const batchName = document.getElementById('batchName').value;
            const reason = document.getElementById('destructionReason').value;
            const method = document.getElementById('destructionMethod').value;
            const witnessName = document.getElementById('witnessName').value;
            const notes = document.getElementById('batchNotes').value;
            
            // Validate required fields
            if (action === 'destroy' && !reason) {
                showStatusMessage('Destruction reason is required', 'error');
                return;
            }
            
            // Collect weights
            const weights = {};
            const weightInputs = document.querySelectorAll('#plantWeightsList input[type="number"]');
            
            weightInputs.forEach(input => {
                const plantId = input.dataset.plant;
                const type = input.dataset.type;
                const value = parseFloat(input.value) || 0;
                
                if (!weights[plantId]) {
                    weights[plantId] = {};
                }
                weights[plantId][type] = value;
            });
            
            // Prepare data for batch operation
            const batchData = {
                selectedPlants: plantIds,
                action: action,
                batchName: batchName,
                reason: reason,
                method: method,
                witnessName: witnessName,
                notes: notes,
                weights: weights,
                companyId: companyDropdown.value
            };
            
            showStatusMessage(`Processing batch ${action}...`, 'info');
            closeBatchModal();
            
            // Send to enhanced batch handler
            fetch('handle_batch_operations.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(batchData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusMessage(data.message, 'success');
                    setTimeout(() => {
                        loadPlants(); // Reload the plant data
                    }, 1500);
                } else {
                    console.error('Error from server:', data.message);
                    showStatusMessage('An error occurred: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error during batch operation:', error);
                showStatusMessage('An error occurred during batch operation', 'error');
            });
        }

        // Show/Hide company selection based on action
        actionDropdown.addEventListener('change', () => {
            companySelection.style.display = actionDropdown.value === 'send' ? 'block' : 'none';
            
            // Update button text based on action
            const buttonText = document.getElementById('processButtonText');
            switch(actionDropdown.value) {
                case 'harvest':
                    buttonText.textContent = '✂️ Harvest Selected';
                    break;
                case 'destroy':
                    buttonText.textContent = '🗑️ Destroy Selected';
                    break;
                case 'send':
                    buttonText.textContent = '📦 Send Selected';
                    break;
                default:
                    buttonText.textContent = 'Process Selected';
            }
        });

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

        // Fetch and populate company dropdown
        fetch('get_companies.php')
            .then(response => response.json())
            .then(companies => {
                companies.forEach(company => {
                    const option = document.createElement('option');
                    option.value = company.id;
                    option.textContent = company.name;
                    companyDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching companies:', error));

        // Event listeners for filters
        document.getElementById('stageFilter').addEventListener('change', applyFilters);
        document.getElementById('roomFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('geneticsFilter').addEventListener('change', applyFilters);
        document.getElementById('ageFilter').addEventListener('change', applyFilters);
        document.getElementById('motherFilter').addEventListener('change', applyFilters);

        // Initialize page
        loadPlants();

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

    <style>
        .reason-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: var(--accent-danger);
            color: white;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--bg-primary);
            border-radius: 12px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid var(--border-color);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h3 {
            margin: 0;
            color: var(--text-primary);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .plant-weight-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 0.5rem;
            align-items: center;
            padding: 0.5rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .plant-weight-item label {
            font-size: 0.9rem;
            margin: 0;
        }

        .plant-weight-item input {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
    </style>
</body>
</html>
