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
    <title>CultivationTracker - Add Plants</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div> 

        <h1>Receive Genetics</h1>

	<p><small>Any time you're receiving or adding genetics, either through a Form D declaration, taking clones, or from another licensed cultivator, this is where you want to add them.</small></p>

        <form id="receiveGeneticsForm" class="modern-form" action="handle_receive_genetics.php" method="post"> 
            
            <!-- Source Selection Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Plant Source</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="sourceType">Source Type *</label>
                            <select id="sourceType" name="sourceType" class="form-control" required>
                                <option value="" disabled selected>Select Source</option>
                                <option value="mother">From Mother Plant</option>
                                <option value="seed">From Seed Stock</option>
                                <option value="clone">Clone/Cutting</option>
                                <option value="purchased">Purchased Plants</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="motherPlantGroup" style="display: none;">
                            <label for="motherId">Mother Plant *</label>
                            <select id="motherId" name="motherId" class="form-control">
                                <option value="" disabled selected>Select Mother Plant</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="seedStockGroup" style="display: none;">
                            <label for="seedStockId">Seed Stock *</label>
                            <select id="seedStockId" name="seedStockId" class="form-control">
                                <option value="" disabled selected>Select Seed Stock</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plant Details Card -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Plant Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="plantCount">Number of Plants *</label>
                            <input type="number" id="plantCount" name="plantCount" class="form-control" min="1" required>
                            <small class="form-text">How many plants to add</small>
                        </div>

                        <div class="form-group">
                            <label for="geneticsName">Genetics *</label>
                            <select id="geneticsName" name="geneticsName" class="form-control" required>
                                <option value="" disabled selected>Select Genetics</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roomName">Room *</label>
                            <select id="roomName" name="roomName" class="form-control" required>
                                <option value="" disabled selected>Select Room</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="growthStage">Initial Growth Stage</label>
                            <select id="growthStage" name="growthStage" class="form-control">
                                <option value="Clone">Clone</option>
                                <option value="Veg">Vegetative</option>
                                <option value="Flower">Flowering</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Additional information about these plants..."></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">🌱 Add Plants</button>
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

        // Handle source type changes
        document.getElementById('sourceType').addEventListener('change', function() {
            const sourceType = this.value;
            const motherGroup = document.getElementById('motherPlantGroup');
            const seedGroup = document.getElementById('seedStockGroup');
            
            // Hide all source-specific fields
            motherGroup.style.display = 'none';
            seedGroup.style.display = 'none';
            
            // Show relevant fields based on source type
            if (sourceType === 'mother' || sourceType === 'clone') {
                motherGroup.style.display = 'block';
                loadMotherPlants();
            } else if (sourceType === 'seed') {
                seedGroup.style.display = 'block';
                loadSeedStocks();
            }
        });

        // Load mother plants
        function loadMotherPlants() {
            fetch('get_mother_plants.php')
                .then(response => response.json())
                .then(mothers => {
                    const select = document.getElementById('motherId');
                    select.innerHTML = '<option value="" disabled selected>Select Mother Plant</option>';
                    mothers.forEach(mother => {
                        const option = document.createElement('option');
                        option.value = mother.id;
                        option.textContent = `${mother.genetics_name} - ${mother.plant_tag || 'ID: ' + mother.id}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading mother plants:', error));
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
