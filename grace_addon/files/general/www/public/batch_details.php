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
    <title>CultivationTracker - Batch Details</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 id="pageTitle">📦 Batch Details</h1>
                <p style="color: var(--text-secondary); margin: 0;" id="pageSubtitle">Detailed information about batch operation</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="history.back()" class="modern-btn secondary">← Back</button>
                <button onclick="exportBatch()" class="modern-btn">📄 Export Report</button>
            </div>
        </div>

        <!-- Batch Information -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3 id="batchTitle">📦 Batch Information</h3>
            <div id="batchInfo" style="margin-top: 1rem;">
                <!-- Batch info will be loaded here -->
            </div>
        </div>

        <!-- Plant Details -->
        <div class="modern-card">
            <h3>🌿 Plant Details</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="plantsTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Tag</th>
                            <th>Genetics</th>
                            <th>Stage</th>
                            <th>Room</th>
                            <th>Age (Days)</th>
                            <th>Weight</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody id="plantsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noDataMessage" style="display: none; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <h3>No Plant Details Found</h3>
                <p>No plant details available for this batch</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="modern-card" style="margin-top: 2rem;">
            <h3>📊 Summary Statistics</h3>
            <div id="summaryStats" style="margin-top: 1rem;">
                <!-- Summary stats will be loaded here -->
            </div>
        </div>
    </main>

    <script>
        let batchData = null;
        let plantDetails = [];

        function loadBatchDetails() {
            const urlParams = new URLSearchParams(window.location.search);
            const batchId = urlParams.get('id');
            const batchType = urlParams.get('type');
            
            if (!batchId || !batchType) {
                showStatusMessage('Invalid batch parameters', 'error');
                return;
            }

            fetch(`get_batch_details.php?id=${batchId}&type=${batchType}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        batchData = data.batch;
                        plantDetails = data.plants;
                        displayBatchInfo();
                        displayPlantDetails();
                        displaySummaryStats();
                    } else {
                        showStatusMessage('Error loading batch details: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading batch details:', error);
                    showStatusMessage('Error loading batch details', 'error');
                });
        }

        function displayBatchInfo() {
            const isDestruction = batchData.batch_type === 'destruction';
            const title = isDestruction ? '🗑️ Destruction Batch' : '✂️ Harvest Batch';
            const icon = isDestruction ? '🗑️' : '✂️';
            
            document.getElementById('pageTitle').innerHTML = `${icon} ${batchData.batch_name}`;
            document.getElementById('pageSubtitle').textContent = `${title} - ${new Date(batchData.operation_date).toLocaleDateString()}`;
            document.getElementById('batchTitle').innerHTML = `${icon} ${batchData.batch_name}`;

            const infoGrid = document.getElementById('batchInfo');
            
            if (isDestruction) {
                infoGrid.innerHTML = `
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div class="info-item">
                            <span class="info-label">Destruction Date</span>
                            <span class="info-value">${new Date(batchData.destruction_date).toLocaleString()}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Plants</span>
                            <span class="info-value">${batchData.total_plants}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Weight</span>
                            <span class="info-value">${batchData.total_weight || 0}g</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Reason</span>
                            <span class="info-value">${formatReason(batchData.reason)}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Method</span>
                            <span class="info-value">${batchData.method || 'Not specified'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Witness</span>
                            <span class="info-value">${batchData.witness_name || 'Not specified'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created By</span>
                            <span class="info-value">${batchData.created_by || 'System'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created Date</span>
                            <span class="info-value">${new Date(batchData.created_date).toLocaleString()}</span>
                        </div>
                    </div>
                    ${batchData.notes ? `
                        <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-tertiary); border-radius: 8px;">
                            <strong>Notes:</strong><br>
                            ${batchData.notes}
                        </div>
                    ` : ''}
                    ${batchData.compliance_notes ? `
                        <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-tertiary); border-radius: 8px; border-left: 4px solid var(--accent-warning);">
                            <strong>Compliance Notes:</strong><br>
                            ${batchData.compliance_notes}
                        </div>
                    ` : ''}
                `;
            } else {
                // Harvest batch info
                infoGrid.innerHTML = `
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div class="info-item">
                            <span class="info-label">Harvest Date</span>
                            <span class="info-value">${new Date(batchData.harvest_date).toLocaleString()}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Plants</span>
                            <span class="info-value">${batchData.total_plants}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Wet Weight</span>
                            <span class="info-value">${batchData.total_wet_weight || 0}g</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dry Weight</span>
                            <span class="info-value">${batchData.total_dry_weight || 0}g</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Flower Weight</span>
                            <span class="info-value">${batchData.total_flower_weight || 0}g</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Trim Weight</span>
                            <span class="info-value">${batchData.total_trim_weight || 0}g</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created By</span>
                            <span class="info-value">${batchData.created_by || 'System'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created Date</span>
                            <span class="info-value">${new Date(batchData.created_date).toLocaleString()}</span>
                        </div>
                    </div>
                    ${batchData.notes ? `
                        <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-tertiary); border-radius: 8px;">
                            <strong>Notes:</strong><br>
                            ${batchData.notes}
                        </div>
                    ` : ''}
                `;
            }
        }

        function displayPlantDetails() {
            const tbody = document.getElementById('plantsTableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            tbody.innerHTML = '';

            if (plantDetails.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            plantDetails.forEach(plant => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${plant.tracking_number}</strong></td>
                    <td>${plant.plant_tag || '-'}</td>
                    <td>${plant.genetics_name || 'Unknown'}</td>
                    <td><span class="stage-badge ${plant.growth_stage.toLowerCase()}">${plant.growth_stage}</span></td>
                    <td>${plant.room_name || 'Unassigned'}</td>
                    <td>${plant.age_days} days</td>
                    <td>${plant.individual_weight ? plant.individual_weight + 'g' : '-'}</td>
                    <td>${plant.individual_notes || '-'}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function displaySummaryStats() {
            const container = document.getElementById('summaryStats');
            
            // Calculate statistics
            const totalPlants = plantDetails.length;
            const geneticsCount = [...new Set(plantDetails.map(p => p.genetics_name))].length;
            const roomsCount = [...new Set(plantDetails.map(p => p.room_name))].length;
            const avgAge = totalPlants > 0 ? Math.round(plantDetails.reduce((sum, p) => sum + p.age_days, 0) / totalPlants) : 0;
            const totalWeight = plantDetails.reduce((sum, p) => sum + (parseFloat(p.individual_weight) || 0), 0);

            // Group by genetics
            const geneticsBreakdown = {};
            plantDetails.forEach(plant => {
                const genetics = plant.genetics_name || 'Unknown';
                if (!geneticsBreakdown[genetics]) {
                    geneticsBreakdown[genetics] = { count: 0, weight: 0 };
                }
                geneticsBreakdown[genetics].count++;
                geneticsBreakdown[genetics].weight += parseFloat(plant.individual_weight) || 0;
            });

            container.innerHTML = `
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <div class="stat-card">
                        <h4>🌿 Total Plants</h4>
                        <div class="stat-number">${totalPlants}</div>
                    </div>
                    <div class="stat-card">
                        <h4>🧬 Genetics</h4>
                        <div class="stat-number">${geneticsCount}</div>
                    </div>
                    <div class="stat-card">
                        <h4>🏠 Rooms</h4>
                        <div class="stat-number">${roomsCount}</div>
                    </div>
                    <div class="stat-card">
                        <h4>📅 Avg Age</h4>
                        <div class="stat-number">${avgAge} days</div>
                    </div>
                    <div class="stat-card">
                        <h4>⚖️ Total Weight</h4>
                        <div class="stat-number">${totalWeight.toFixed(1)}g</div>
                    </div>
                </div>
                
                <div class="modern-card" style="background: var(--bg-secondary);">
                    <h4>🧬 Genetics Breakdown</h4>
                    <div style="margin-top: 1rem;">
                        ${Object.entries(geneticsBreakdown).map(([genetics, data]) => `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                                <span><strong>${genetics}</strong></span>
                                <span>${data.count} plants (${data.weight.toFixed(1)}g)</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function formatReason(reason) {
            const reasonMap = {
                'disease': '🦠 Disease/Infection',
                'pests': '🐛 Pest Infestation',
                'poor_growth': '📉 Poor Growth',
                'hermaphrodite': '⚧ Hermaphrodite',
                'overcrowding': '🏠 Overcrowding',
                'quality_control': '🔍 Quality Control',
                'compliance': '📋 Compliance',
                'other': '❓ Other'
            };
            return reasonMap[reason] || reason;
        }

        function exportBatch() {
            const urlParams = new URLSearchParams(window.location.search);
            const batchId = urlParams.get('id');
            const batchType = urlParams.get('type');
            
            window.open(`export_batch.php?id=${batchId}&type=${batchType}`, '_blank');
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

        // Load batch details on page load
        loadBatchDetails();

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
    </style>
</body>
</html>