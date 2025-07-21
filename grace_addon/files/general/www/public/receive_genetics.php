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
    <title>CultivationTracker - Add Plants</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div> 

        <div style="margin-bottom: 2rem;">
            <h1>🌱 Receive Genetics</h1>
            <p style="color: var(--text-secondary); margin: 0;">Any time you're receiving or adding genetics, either through a Form D declaration, taking clones, or from another licensed cultivator, this is where you want to add them.</p>
        </div>

        <form id="receiveGeneticsForm" class="modern-form" action="handle_receive_genetics.php" method="post" style="background: none; border: none; padding: 0; box-shadow: none;"> 
            
            <!-- Source Selection Card -->
            <div class="modern-card" style="margin-bottom: 1.5rem;">
                <h3>📍 Plant Source</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <div>
                        <label for="sourceType">Source Type *</label>
                        <select id="sourceType" name="sourceType" required>
                            <option value="" disabled selected>Select Source</option>
                            <option value="mother">From Mother Plant(s)</option>
                            <option value="seed">From Seed Stock</option>
                            <option value="clone">Clone/Cutting</option>
                            <option value="purchased">Purchased Plants</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="dateAdded">Date Added *</label>
                        <input type="datetime-local" id="dateAdded" name="dateAdded" required>
                        <small style="color: var(--text-secondary); font-size: 0.8rem;">When these plants were added to the system</small>
                    </div>
                </div>
                
                <!-- Single Mother Plant Selection -->
                <div id="singleMotherGroup" style="display: none; margin-top: 1rem;">
                    <label for="motherId">Mother Plant *</label>
                    <select id="motherId" name="motherId">
                        <option value="" disabled selected>Select Mother Plant</option>
                    </select>
                </div>
                
                <!-- Multiple Mother Plant Selection -->
                <div id="multiMotherGroup" style="display: none; margin-top: 1rem;">
                    <label>Select Mother Plants and Clone Distribution *</label>
                    <div id="motherSelectionContainer" style="margin-top: 0.5rem;">
                        <!-- Mother plant selections will be added here -->
                    </div>
                    <button type="button" id="addMotherBtn" class="modern-btn secondary" style="margin-top: 1rem;">
                        ➕ Add Another Mother Plant
                    </button>
                    <small style="color: var(--text-secondary); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                        Distribute clones between multiple mother plants. Total must equal the number of plants above.
                    </small>
                </div>
                
                <!-- Seed Stock Selection -->
                <div id="seedStockGroup" style="display: none; margin-top: 1rem;">
                    <label for="seedStockId">Seed Stock *</label>
                    <select id="seedStockId" name="seedStockId">
                        <option value="" disabled selected>Select Seed Stock</option>
                    </select>
                </div>
            </div>

            <!-- Plant Details Card -->
            <div class="modern-card" style="margin-bottom: 1.5rem;">
                <h3>🌿 Plant Details</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <div>
                        <label for="plantCount">Number of Plants *</label>
                        <input type="number" id="plantCount" name="plantCount" min="1" required>
                        <small style="color: var(--text-secondary); font-size: 0.8rem;">How many plants to add</small>
                    </div>

                    <div>
                        <label for="geneticsName">Genetics *</label>
                        <select id="geneticsName" name="geneticsName" required>
                            <option value="" disabled selected>Select Genetics</option>
                        </select>
                    </div>

                    <div>
                        <label for="roomName">Room *</label>
                        <select id="roomName" name="roomName" required>
                            <option value="" disabled selected>Select Room</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="growthStage">Initial Growth Stage</label>
                        <select id="growthStage" name="growthStage">
                            <option value="Clone">Clone</option>
                            <option value="Veg">Vegetative</option>
                            <option value="Flower">Flowering</option>
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Additional information about these plants..."></textarea>
                </div>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="modern-btn" style="font-size: 1.1rem; padding: 1rem 2rem;">🌱 Add Plants</button>
            </div>
        </form>
    </main>

    <script src="js/growcart.js"></script> 
    <script>
        const form = document.getElementById('receiveGeneticsForm');
        const statusMessage = document.getElementById('statusMessage');
        const geneticsDropdown = document.getElementById('geneticsName');

        // Check if there's a success or error message in the URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage) {
            showStatusMessage(successMessage, 'success');
            
            // Show tracking numbers if available
            const trackingNumbers = urlParams.get('tracking_numbers');
            if (trackingNumbers) {
                const numbers = JSON.parse(decodeURIComponent(trackingNumbers));
                const trackingInfo = document.createElement('div');
                trackingInfo.className = 'card';
                trackingInfo.innerHTML = `
                    <h3>Generated Tracking Numbers</h3>
                    <p>Your plants have been assigned the following tracking numbers:</p>
                    <ul>${numbers.map(num => `<li><strong>${num}</strong></li>`).join('')}</ul>
                `;
                form.parentNode.insertBefore(trackingInfo, form);
                
                setTimeout(() => {
                    trackingInfo.remove();
                }, 10000);
            }
            
            form.reset(); // Clear the form
        } else if (errorMessage) {
            showStatusMessage(errorMessage, 'error');

            // Pre-populate the form with the submitted data (if available)
            const submittedData = JSON.parse(urlParams.get('data') || '{}');
            form.plantCount.value = submittedData.plantCount || '';
            form.geneticsName.value = submittedData.geneticsName || '';
        }

        function showStatusMessage(message, type) {
            statusMessage.textContent = message;
            statusMessage.classList.add(type);
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
                statusMessage.classList.remove(type);
            }, 5000);
        }

        // Fetch genetics data and populate dropdown on page load
        fetch('get_genetics.php')
            .then(response => response.json())
            .then(genetics => {
                genetics.forEach(geneticsItem => {
                    const option = document.createElement('option');
                    option.value = geneticsItem.id; 
                    option.textContent = geneticsItem.name;
                    geneticsDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching genetics:', error));

        // Fetch all rooms and populate dropdown
        const roomDropdown = document.getElementById('roomName');
        fetch('get_all_rooms.php')
            .then(response => response.json())
            .then(rooms => {
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = `${room.name} (${room.room_type})`;
                    roomDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching rooms:', error));

        // Set default date to now
        document.getElementById('dateAdded').value = new Date().toISOString().slice(0, 16);

        // Handle source type changes
        document.getElementById('sourceType').addEventListener('change', function() {
            const sourceType = this.value;
            const singleMotherGroup = document.getElementById('singleMotherGroup');
            const multiMotherGroup = document.getElementById('multiMotherGroup');
            const seedGroup = document.getElementById('seedStockGroup');
            
            // Hide all source-specific fields
            singleMotherGroup.style.display = 'none';
            multiMotherGroup.style.display = 'none';
            seedGroup.style.display = 'none';
            
            // Show relevant fields based on source type
            if (sourceType === 'mother') {
                multiMotherGroup.style.display = 'block';
                loadMotherPlants();
                initializeMotherSelection();
            } else if (sourceType === 'clone') {
                singleMotherGroup.style.display = 'block';
                loadMotherPlants();
            } else if (sourceType === 'seed') {
                seedGroup.style.display = 'block';
                loadSeedStocks();
            }
        });

        // Multi-mother selection functionality
        let motherPlantsList = [];
        let motherSelectionCount = 0;

        function initializeMotherSelection() {
            const container = document.getElementById('motherSelectionContainer');
            container.innerHTML = '';
            motherSelectionCount = 0;
            addMotherSelection();
        }

        function addMotherSelection() {
            motherSelectionCount++;
            const container = document.getElementById('motherSelectionContainer');
            
            const motherDiv = document.createElement('div');
            motherDiv.className = 'mother-selection-row';
            motherDiv.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end; margin-bottom: 1rem; padding: 1rem; border: 1px solid var(--border-color); border-radius: 8px;';
            
            motherDiv.innerHTML = `
                <div>
                    <label>Mother Plant</label>
                    <select name="motherIds[]" class="mother-select" required>
                        <option value="" disabled selected>Select Mother Plant</option>
                    </select>
                </div>
                <div>
                    <label>Number of Clones</label>
                    <input type="number" name="motherCounts[]" class="mother-count" min="1" required placeholder="0" onchange="updateCloneDistribution()">
                </div>
                <div>
                    ${motherSelectionCount > 1 ? '<button type="button" class="modern-btn secondary" onclick="removeMotherSelection(this)" style="background: var(--accent-error);">❌</button>' : ''}
                </div>
            `;
            
            container.appendChild(motherDiv);
            
            // Populate the new select with mother plants
            const newSelect = motherDiv.querySelector('.mother-select');
            motherPlantsList.forEach(mother => {
                const option = document.createElement('option');
                option.value = mother.id;
                option.textContent = `${mother.genetics_name || 'Unknown'} - ${mother.plant_tag || mother.tracking_number || 'ID: ' + mother.id}`;
                newSelect.appendChild(option);
            });
        }

        function removeMotherSelection(button) {
            button.closest('.mother-selection-row').remove();
            updateCloneDistribution();
        }

        function updateCloneDistribution() {
            const totalPlants = parseInt(document.getElementById('plantCount').value) || 0;
            const motherCounts = document.querySelectorAll('.mother-count');
            let totalAssigned = 0;
            
            motherCounts.forEach(input => {
                totalAssigned += parseInt(input.value) || 0;
            });
            
            const remaining = totalPlants - totalAssigned;
            const statusDiv = document.getElementById('cloneDistributionStatus') || createDistributionStatus();
            
            if (remaining === 0) {
                statusDiv.innerHTML = '<span style="color: var(--accent-success);">✅ All clones distributed correctly</span>';
                statusDiv.style.color = 'var(--accent-success)';
            } else if (remaining > 0) {
                statusDiv.innerHTML = `<span style="color: var(--accent-warning);">⚠️ ${remaining} clones remaining to distribute</span>`;
                statusDiv.style.color = 'var(--accent-warning)';
            } else {
                statusDiv.innerHTML = `<span style="color: var(--accent-error);">❌ Over-distributed by ${Math.abs(remaining)} clones</span>`;
                statusDiv.style.color = 'var(--accent-error)';
            }
        }

        function createDistributionStatus() {
            const statusDiv = document.createElement('div');
            statusDiv.id = 'cloneDistributionStatus';
            statusDiv.style.cssText = 'margin-top: 1rem; padding: 0.5rem; border-radius: 4px; font-weight: 600;';
            document.getElementById('multiMotherGroup').appendChild(statusDiv);
            return statusDiv;
        }

        // Add event listener for add mother button
        document.getElementById('addMotherBtn').addEventListener('click', addMotherSelection);

        // Update distribution when plant count changes
        document.getElementById('plantCount').addEventListener('change', updateCloneDistribution);

        // Load mother plants
        function loadMotherPlants() {
            console.log('Loading mother plants...');
            fetch('get_mother_plants.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Mother plants data:', data);
                    
                    // Handle the new debug format or old format
                    const mothers = data.mothers || data;
                    motherPlantsList = mothers; // Store for multi-select use
                    
                    // Populate single mother select
                    const singleSelect = document.getElementById('motherId');
                    if (singleSelect) {
                        singleSelect.innerHTML = '<option value="" disabled selected>Select Mother Plant</option>';
                        
                        if (mothers.length === 0) {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No mother plants available';
                            option.disabled = true;
                            singleSelect.appendChild(option);
                            console.log('No mother plants found');
                            
                            // Show debug info if available
                            if (data.debug) {
                                console.log('Debug info:', data.debug);
                                showStatusMessage(data.debug.message, 'error');
                            }
                        } else {
                            mothers.forEach(mother => {
                                const option = document.createElement('option');
                                option.value = mother.id;
                                option.textContent = `${mother.genetics_name || 'Unknown'} - ${mother.plant_tag || mother.tracking_number || 'ID: ' + mother.id}`;
                                singleSelect.appendChild(option);
                            });
                            console.log(`Added ${mothers.length} mother plants to dropdown`);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading mother plants:', error);
                    const singleSelect = document.getElementById('motherId');
                    if (singleSelect) {
                        singleSelect.innerHTML = '<option value="" disabled selected>Error loading mother plants</option>';
                    }
                    showStatusMessage('Error loading mother plants: ' + error.message, 'error');
                });
        }

        // Load seed stocks
        function loadSeedStocks() {
            fetch('get_seed_stocks.php')
                .then(response => response.json())
                .then(seeds => {
                    const select = document.getElementById('seedStockId');
                    select.innerHTML = '<option value="" disabled selected>Select Seed Stock</option>';
                    seeds.forEach(seed => {
                        const option = document.createElement('option');
                        option.value = seed.id;
                        option.textContent = `${seed.genetics_name} - ${seed.batch_name} (${(seed.seed_count - seed.used_count)} remaining)`;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading seed stocks:', error));
        }

        // Auto-populate genetics when seed stock is selected
        document.getElementById('seedStockId').addEventListener('change', function() {
            const seedStockId = this.value;
            if (seedStockId) {
                fetch(`get_seed_stock_details.php?id=${seedStockId}`)
                    .then(response => response.json())
                    .then(seedStock => {
                        if (seedStock.genetics_id) {
                            document.getElementById('geneticsName').value = seedStock.genetics_id;
                        }
                    })
                    .catch(error => console.error('Error loading seed stock details:', error));
            }
        });

        // Auto-populate genetics when mother plant is selected
        document.getElementById('motherId').addEventListener('change', function() {
            const motherId = this.value;
            if (motherId) {
                fetch(`get_plant_details.php?id=${motherId}`)
                    .then(response => response.json())
                    .then(mother => {
                        if (mother.genetics_id) {
                            document.getElementById('geneticsName').value = mother.genetics_id;
                        }
                    })
                    .catch(error => console.error('Error loading mother plant details:', error));
            }
        });
    </script>
</body>
</html>
