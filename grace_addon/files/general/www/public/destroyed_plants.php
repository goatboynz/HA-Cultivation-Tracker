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
    <title>CultivationTracker - Destroyed Plants</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🗑️ Destroyed Plants</h1>
                <p style="color: var(--text-secondary); margin: 0;">Complete record of all destroyed plants with reasons and compliance details</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="refreshData()" class="modern-btn secondary">🔄 Refresh</button>
                <a href="harvest_plants.php" class="modern-btn">🗑️ Destroy Plants</a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-danger), #dc2626);">
                <h3 style="color: white;">🗑️ Total Destroyed</h3>
                <div class="stat-number" id="totalDestroyed" style="color: white;">-</div>
                <div class="stat-label" style="color: rgba(255,255,255,0.8);">All Time</div>
            </div>
            <div class="stat-card">
                <h3>📅 This Month</h3>
                <div class="stat-number" id="thisMonth">-</div>
                <div class="stat-label">Destroyed Plants</div>
            </div>
            <div class="stat-card">
                <h3>⚖️ Total Weight</h3>
                <div class="stat-number" id="totalWeight">-</div>
                <div class="stat-label">Destroyed (grams)</div>
            </div>
            <div class="stat-card">
                <h3>📊 Batch Operations</h3>
                <div class="stat-number" id="batchCount">-</div>
                <div class="stat-label">Batch Destructions</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3>🔍 Filters</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <label for="reasonFilter">Destruction Reason:</label>
                    <select id="reasonFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Reasons</option>
                        <option value="disease">Disease/Infection</option>
                        <option value="pests">Pest Infestation</option>
                        <option value="poor_growth">Poor Growth</option>
                        <option value="hermaphrodite">Hermaphrodite</option>
                        <option value="overcrowding">Overcrowding</option>
                        <option value="quality_control">Quality Control</option>
                        <option value="compliance">Compliance</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="geneticsFilter">Genetics:</label>
                    <select id="geneticsFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Genetics</option>
                    </select>
                </div>
                <div>
                    <label for="dateFilter">Date Range:</label>
                    <select id="dateFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
                <div>
                    <label for="typeFilter">Operation Type:</label>
                    <select id="typeFilter" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="">All Types</option>
                        <option value="individual">Individual</option>
                        <option value="batch">Batch</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button onclick="clearFilters()" class="modern-btn secondary">🔄 Clear Filters</button>
                <span id="filterCount" style="padding: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;"></span>
            </div>
        </div>

        <!-- Destroyed Plants Table -->
        <div class="modern-card">
            <h3>🗑️ Destruction Records</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="destroyedTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Stage</th>
                            <th>Reason</th>
                            <th>Weight</th>
                            <th>Witness</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="destroyedTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Destroyed Plants Found</h3>
                <p>No plants match your current filter criteria</p>
            </div>
        </div>

        <!-- Batch Operations -->
        <div class="modern-card" style="margin-top: 2rem;">
            <h3>📦 Batch Destruction Operations</h3>
            <div id="batchOperations" style="margin-top: 1rem;">
                <!-- Batch operations will be loaded here -->
            </div>
        </div>
    </main>

    <script>
        let allDestroyedPlants = [];
        let filteredPlants = [];
        let batchOperations = [];

        function loadDestroyedPlants() {
            fetch('get_destroyed_plants.php')
                .then(response => response.json())
                .then(data => {
                    allDestroyedPlants = data.plants || [];
                    batchOperations = data.batches || [];
                    populateFilters();
                    applyFilters();
                    updateSummaryCards();
                    displayBatchOperations();
                })
                .catch(error => {
                    console.error('Error loading destroyed plants:', error);
                    showStatusMessage('Error loading destroyed plants', 'error');
                });
        }

        function populateFilters() {
            // Populate genetics filter
            const genetics = [...new Set(allDestroyedPlants.map(p => p.genetics_name).filter(g => g))];
            const geneticsFilter = document.getElementById('geneticsFilter');
            geneticsFilter.innerHTML = '<option value="">All Genetics</option>';
            genetics.forEach(genetic => {
                const option = document.createElement('option');
                option.value = genetic;
                option.textContent = genetic;
                geneticsFilter.appendChild(option);
            });
        }

        function applyFilters() {
            const reasonFilter = document.getElementById('reasonFilter').value;
            const geneticsFilter = document.getElementById('geneticsFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;

            filteredPlants = allDestroyedPlants.filter(plant => {
                let dateMatch = true;
                if (dateFilter) {
                    const plantDate = new Date(plant.destruction_date);
                    const now = new Date();
                    
                    switch(dateFilter) {
                        case 'today':
                            dateMatch = plantDate.toDateString() === now.toDateString();
                            break;
                        case 'week':
                            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                            dateMatch = plantDate >= weekAgo;
                            break;
                        case 'month':
                            dateMatch = plantDate.getMonth() === now.getMonth() && plantDate.getFullYear() === now.getFullYear();
                            break;
                        case 'quarter':
                            const quarter = Math.floor(now.getMonth() / 3);
                            const plantQuarter = Math.floor(plantDate.getMonth() / 3);
                            dateMatch = plantQuarter === quarter && plantDate.getFullYear() === now.getFullYear();
                            break;
                        case 'year':
                            dateMatch = plantDate.getFullYear() === now.getFullYear();
                            break;
                    }
                }

                return (!reasonFilter || plant.destruction_reason === reasonFilter) &&
                       (!geneticsFilter || plant.genetics_name === geneticsFilter) &&
                       (!typeFilter || plant.operation_type === typeFilter) &&
                       dateMatch;
            });

            displayDestroyedPlants();
            updateFilterCount();
        }

        function displayDestroyedPlants() {
            const tbody = document.getElementById('destroyedTableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';

            if (filteredPlants.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            filteredPlants.forEach(plant => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${new Date(plant.destruction_date).toLocaleDateString()}</td>
                    <td><span class="status-badge ${plant.operation_type === 'batch' ? 'info' : 'secondary'}">${plant.operation_type || 'Individual'}</span></td>
                    <td><strong>${plant.tracking_number}</strong></td>
                    <td>${plant.plant_tag || '-'}</td>
                    <td>${plant.genetics_name || 'Unknown'}</td>
                    <td><span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span></td>
                    <td><span class="reason-badge">${formatReason(plant.destruction_reason)}</span></td>
                    <td>${plant.total_weight ? plant.total_weight + 'g' : '-'}</td>
                    <td>${plant.witness_name || '-'}</td>
                    <td>
                        <button onclick="viewDetails(${plant.id})" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">👁️ Details</button>
                        ${plant.batch_id ? `<button onclick="viewBatch(${plant.batch_id})" class="modern-btn" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;">📦 Batch</button>` : ''}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayBatchOperations() {
            const container = document.getElementById('batchOperations');
            
            if (batchOperations.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: var(--text-secondary);">No batch destruction operations found</p>';
                return;
            }

            container.innerHTML = batchOperations.map(batch => `
                <div class="modern-card" style="margin-bottom: 1rem; background: var(--bg-secondary);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div>
                            <h4 style="margin: 0; color: var(--accent-danger);">📦 ${batch.batch_name}</h4>
                            <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">${new Date(batch.destruction_date).toLocaleDateString()}</p>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 600;">${batch.total_plants} plants</div>
                            <div style="color: var(--text-secondary); font-size: 0.9rem;">${batch.total_weight || 0}g total</div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <div><strong>Reason:</strong> ${formatReason(batch.reason)}</div>
                        <div><strong>Method:</strong> ${batch.method || 'Not specified'}</div>
                        <div><strong>Witness:</strong> ${batch.witness_name || 'Not specified'}</div>
                        <div><strong>Created by:</strong> ${batch.created_by || 'System'}</div>
                    </div>
                    ${batch.notes ? `<div style="background: var(--bg-tertiary); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><strong>Notes:</strong> ${batch.notes}</div>` : ''}
                    <button onclick="viewBatchDetails(${batch.id})" class="modern-btn">📋 View Plant Details</button>
                </div>
            `).join('');
        }

        function updateSummaryCards() {
            document.getElementById('totalDestroyed').textContent = allDestroyedPlants.length;
            
            const thisMonth = allDestroyedPlants.filter(p => {
                const plantDate = new Date(p.destruction_date);
                const now = new Date();
                return plantDate.getMonth() === now.getMonth() && plantDate.getFullYear() === now.getFullYear();
            }).length;
            document.getElementById('thisMonth').textContent = thisMonth;
            
            const totalWeight = allDestroyedPlants.reduce((sum, p) => sum + (parseFloat(p.total_weight) || 0), 0);
            document.getElementById('totalWeight').textContent = totalWeight.toFixed(1) + 'g';
            
            document.getElementById('batchCount').textContent = batchOperations.length;
        }

        function updateFilterCount() {
            const filterCount = document.getElementById('filterCount');
            filterCount.textContent = `Showing ${filteredPlants.length} of ${allDestroyedPlants.length} destroyed plants`;
        }

        function formatReason(reason) {
            const reasonMap = {
                'disease': '🦠 Disease',
                'pests': '🐛 Pests',
                'poor_growth': '📉 Poor Growth',
                'hermaphrodite': '⚧ Hermaphrodite',
                'overcrowding': '🏠 Overcrowding',
                'quality_control': '🔍 Quality Control',
                'compliance': '📋 Compliance',
                'other': '❓ Other'
            };
            return reasonMap[reason] || reason;
        }

        function clearFilters() {
            document.getElementById('reasonFilter').value = '';
            document.getElementById('geneticsFilter').value = '';
            document.getElementById('dateFilter').value = '';
            document.getElementById('typeFilter').value = '';
            applyFilters();
        }

        function refreshData() {
            loadDestroyedPlants();
        }

        function viewDetails(plantId) {
            window.location.href = `plant_summary.php?id=${plantId}`;
        }

        function viewBatch(batchId) {
            window.location.href = `batch_details.php?id=${batchId}&type=destruction`;
        }

        function viewBatchDetails(batchId) {
            window.location.href = `batch_details.php?id=${batchId}&type=destruction`;
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

        // Event listeners for filters
        document.getElementById('reasonFilter').addEventListener('change', applyFilters);
        document.getElementById('geneticsFilter').addEventListener('change', applyFilters);
        document.getElementById('dateFilter').addEventListener('change', applyFilters);
        document.getElementById('typeFilter').addEventListener('change', applyFilters);

        // Load data on page load
        loadDestroyedPlants();

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
    </style>
</body>
</html>