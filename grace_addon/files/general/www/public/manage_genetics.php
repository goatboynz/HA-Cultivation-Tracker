<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Manage Genetics</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="grid">
            <div>
                <h1>Genetics Management</h1>
                <p><small>Manage your genetic strains and their characteristics</small></p>
            </div>
            <div style="text-align: right;">
                <button onclick="showAddGeneticsForm()" class="button">Add New Genetics</button>
            </div>
        </div>

        <!-- Add Genetics Form -->
        <div id="addGeneticsForm" style="display: none;" class="card">
            <h3>Add New Genetics</h3>
            <form id="geneticsForm" action="handle_add_genetics.php" method="post" enctype="multipart/form-data">
                <div class="grid">
                    <div>
                        <label for="name">Strain Name *:</label>
                        <input type="text" id="name" name="name" class="input" required>
                    </div>
                    <div>
                        <label for="breeder">Breeder:</label>
                        <input type="text" id="breeder" name="breeder" class="input">
                    </div>
                </div>
                
                <div class="grid">
                    <div>
                        <label for="genetic_lineage">Genetic Lineage:</label>
                        <input type="text" id="genetic_lineage" name="genetic_lineage" class="input" placeholder="e.g., OG Kush x Sour Diesel">
                    </div>
                    <div>
                        <label for="indica_sativa_ratio">Indica/Sativa Ratio:</label>
                        <select id="indica_sativa_ratio" name="indica_sativa_ratio" class="input">
                            <option value="">Select Type</option>
                            <option value="100% Indica">100% Indica</option>
                            <option value="80% Indica / 20% Sativa">80% Indica / 20% Sativa</option>
                            <option value="60% Indica / 40% Sativa">60% Indica / 40% Sativa</option>
                            <option value="50% Indica / 50% Sativa">50% Indica / 50% Sativa (Hybrid)</option>
                            <option value="40% Indica / 60% Sativa">40% Indica / 60% Sativa</option>
                            <option value="20% Indica / 80% Sativa">20% Indica / 80% Sativa</option>
                            <option value="100% Sativa">100% Sativa</option>
                        </select>
                    </div>
                </div>

                <div class="grid">
                    <div>
                        <label for="flowering_days">Flowering Days:</label>
                        <input type="number" id="flowering_days" name="flowering_days" class="input" min="35" max="120" placeholder="e.g., 63">
                    </div>
                    <div>
                        <label for="thc_percentage">THC % (estimated):</label>
                        <input type="number" id="thc_percentage" name="thc_percentage" class="input" min="0" max="35" step="0.1" placeholder="e.g., 22.5">
                    </div>
                </div>

                <div class="grid">
                    <div>
                        <label for="cbd_percentage">CBD % (estimated):</label>
                        <input type="number" id="cbd_percentage" name="cbd_percentage" class="input" min="0" max="25" step="0.1" placeholder="e.g., 0.5">
                    </div>
                    <div>
                        <label for="photo_upload">Photo:</label>
                        <input type="file" id="photo_upload" name="photo_upload" class="input" accept="image/*">
                    </div>
                </div>

                <div>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" class="input" rows="3" placeholder="Describe the strain characteristics, effects, flavor profile, etc."></textarea>
                </div>

                <div class="grid">
                    <button type="submit" class="button">Add Genetics</button>
                    <button type="button" onclick="hideAddGeneticsForm()" class="button secondary">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Genetics Table -->
        <div class="table-container">
            <table id="geneticsTable">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Strain Name</th>
                        <th>Breeder</th>
                        <th>Type</th>
                        <th>Flowering Days</th>
                        <th>THC %</th>
                        <th>CBD %</th>
                        <th>Plants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="geneticsTableBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function showAddGeneticsForm() {
            document.getElementById('addGeneticsForm').style.display = 'block';
        }

        function hideAddGeneticsForm() {
            document.getElementById('addGeneticsForm').style.display = 'none';
            document.getElementById('geneticsForm').reset();
        }

        function loadGenetics() {
            fetch('get_genetics_detailed.php')
                .then(response => response.json())
                .then(genetics => {
                    const tbody = document.getElementById('geneticsTableBody');
                    tbody.innerHTML = '';
                    
                    genetics.forEach(genetic => {
                        const row = document.createElement('tr');
                        const photoCell = genetic.photo_url ? 
                            `<img src="${genetic.photo_url}" alt="${genetic.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">` : 
                            '<span style="color: #666;">No photo</span>';
                        
                        row.innerHTML = `
                            <td>${photoCell}</td>
                            <td><strong>${genetic.name}</strong><br><small>${genetic.genetic_lineage || ''}</small></td>
                            <td>${genetic.breeder || '-'}</td>
                            <td>${genetic.indica_sativa_ratio || '-'}</td>
                            <td>${genetic.flowering_days ? genetic.flowering_days + ' days' : '-'}</td>
                            <td>${genetic.thc_percentage ? genetic.thc_percentage + '%' : '-'}</td>
                            <td>${genetic.cbd_percentage ? genetic.cbd_percentage + '%' : '-'}</td>
                            <td>${genetic.plant_count || 0}</td>
                            <td>
                                <button onclick="editGenetics(${genetic.id})" class="button small">Edit</button>
                                <button onclick="viewDetails(${genetic.id})" class="button small">Details</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error loading genetics:', error));
        }

        function editGenetics(id) {
            window.location.href = `edit_genetics.php?id=${id}`;
        }

        function viewDetails(id) {
            window.location.href = `genetics_details.php?id=${id}`;
        }

        // Load genetics on page load
        loadGenetics();

        // Check for success/error messages
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage) {
            showStatusMessage(successMessage, 'success');
        } else if (errorMessage) {
            showStatusMessage(errorMessage, 'error');
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
    </script>
</body>
</html>