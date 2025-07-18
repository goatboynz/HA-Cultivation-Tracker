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
    <title>CultivationTracker - Seed Stock Management</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="page-header">
            <div>
                <h1>Seed Stock Management</h1>
                <p class="subtitle">Track and manage your seed inventory</p>
            </div>
            <div class="header-actions">
                <button onclick="showAddSeedForm()" class="btn btn-primary">➕ Add Seed Stock</button>
            </div>
        </div>

        <!-- Add Seed Stock Form -->
        <div id="addSeedForm" style="display: none;" class="form-card fade-in">
            <div class="card-header">
                <h3>Add New Seed Stock</h3>
            </div>
            <div class="card-body">
                <form id="seedForm" action="handle_add_seed_stock.php" method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="batchName">Batch Name *</label>
                            <input type="text" id="batchName" name="batchName" class="form-control" required placeholder="e.g., OG-2024-001">
                        </div>
                        
                        <div class="form-group">
                            <label for="geneticsId">Genetics *</label>
                            <select id="geneticsId" name="geneticsId" class="form-control" required>
                                <option value="" disabled selected>Select Genetics</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="supplier">Supplier/Breeder</label>
                            <input type="text" id="supplier" name="supplier" class="form-control" placeholder="Seed supplier or breeder">
                        </div>
                        
                        <div class="form-group">
                            <label for="seedCount">Number of Seeds</label>
                            <input type="number" id="seedCount" name="seedCount" class="form-control" min="1" placeholder="Total seeds in batch">
                        </div>
                        
                        <div class="form-group">
                            <label for="purchaseDate">Purchase Date</label>
                            <input type="date" id="purchaseDate" name="purchaseDate" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="expiryDate">Expiry Date</label>
                            <input type="date" id="expiryDate" name="expiryDate" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="storageLocation">Storage Location</label>
                            <input type="text" id="storageLocation" name="storageLocation" class="form-control" placeholder="e.g., Fridge A, Shelf 2">
                        </div>
                        
                        <div class="form-group">
                            <label for="germinationRate">Germination Rate (%)</label>
                            <input type="number" id="germinationRate" name="germinationRate" class="form-control" min="0" max="100" step="0.1">
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price per Seed ($)</label>
                            <input type="number" id="price" name="price" class="form-control" min="0" step="0.01">
                        </div>
                        
                        <div class="form-group">
                            <label for="photo">Seed Package Photo</label>
                            <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Additional information about this seed stock..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Add Seed Stock</button>
                        <button type="button" onclick="hideAddSeedForm()" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Seed Stock Table -->
        <div class="info-card">
            <h3>Current Seed Stock</h3>
            <div class="table-responsive">
                <table class="modern-table" id="seedStockTable">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Batch Name</th>
                            <th>Genetics</th>
                            <th>Supplier</th>
                            <th>Seeds</th>
                            <th>Used</th>
                            <th>Remaining</th>
                            <th>Germination</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="seedStockTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="noDataMessage" style="display: none;">
            <div class="info-card">
                <p>No seed stock found. Add your first seed batch to get started.</p>
            </div>
        </div>
    </main>

    <script>
        function showAddSeedForm() {
            document.getElementById('addSeedForm').style.display = 'block';
            loadGenetics();
        }

        function hideAddSeedForm() {
            document.getElementById('addSeedForm').style.display = 'none';
            document.getElementById('seedForm').reset();
        }

        async function loadGenetics() {
            try {
                const response = await fetch('get_genetics.php');
                const genetics = await response.json();
                const select = document.getElementById('geneticsId');
                select.innerHTML = '<option value="" disabled selected>Select Genetics</option>';
                genetics.forEach(g => {
                    const option = document.createElement('option');
                    option.value = g.id;
                    option.textContent = g.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading genetics:', error);
            }
        }

        async function loadSeedStock() {
            try {
                const response = await fetch('get_seed_stocks_detailed.php');
                const seedStocks = await response.json();
                const tbody = document.getElementById('seedStockTableBody');
                const noDataMessage = document.getElementById('noDataMessage');
                
                tbody.innerHTML = '';
                
                if (seedStocks.length === 0) {
                    noDataMessage.style.display = 'block';
                    return;
                }
                
                noDataMessage.style.display = 'none';
                
                seedStocks.forEach(stock => {
                    const row = document.createElement('tr');
                    const photoCell = stock.photo_path ? 
                        `<img src="${stock.photo_path}" alt="${stock.batch_name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">` : 
                        '<span style="color: #666;">No photo</span>';
                    
                    const remaining = (stock.seed_count || 0) - (stock.used_count || 0);
                    const status = remaining <= 0 ? 'Empty' : 
                                  stock.expiry_date && new Date(stock.expiry_date) < new Date() ? 'Expired' : 
                                  remaining <= 5 ? 'Low Stock' : 'Available';
                    
                    const statusClass = status === 'Empty' ? 'danger' : 
                                       status === 'Expired' ? 'danger' : 
                                       status === 'Low Stock' ? 'warning' : 'success';
                    
                    row.innerHTML = `
                        <td>${photoCell}</td>
                        <td><strong>${stock.batch_name}</strong></td>
                        <td>${stock.genetics_name || 'Unknown'}</td>
                        <td>${stock.supplier || '-'}</td>
                        <td>${stock.seed_count || 0}</td>
                        <td>${stock.used_count || 0}</td>
                        <td>${remaining}</td>
                        <td>${stock.germination_rate ? stock.germination_rate + '%' : '-'}</td>
                        <td><span class="status-badge ${statusClass.toLowerCase()}">${status}</span></td>
                        <td>
                            <button onclick="editSeedStock(${stock.id})" class="btn btn-sm btn-secondary">Edit</button>
                            <button onclick="useSeed(${stock.id})" class="btn btn-sm btn-primary" ${remaining <= 0 ? 'disabled' : ''}>Use Seed</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } catch (error) {
                console.error('Error loading seed stock:', error);
            }
        }

        function editSeedStock(id) {
            window.location.href = `edit_seed_stock.php?id=${id}`;
        }

        function useSeed(seedStockId) {
            window.location.href = `receive_genetics.php?seed_stock_id=${seedStockId}`;
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

        // Load seed stock on page load
        loadSeedStock();

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
</body>
</html>