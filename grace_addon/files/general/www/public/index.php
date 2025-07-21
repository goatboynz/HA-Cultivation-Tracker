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
    <title>CultivationTracker - Professional Cannabis Management System</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 3rem; margin-bottom: 1rem;">🌿 CultivationTracker</h1>
            <p style="font-size: 1.2rem; color: var(--text-secondary); margin-bottom: 2rem;">Professional Cannabis Cultivation Management System</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="dashboard.php" class="modern-btn" style="font-size: 1.1rem; padding: 1rem 2rem;">📊 Go to Dashboard</a>
                <a href="all_plants.php" class="modern-btn secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">🌱 View Plants</a>
                <a href="reports.php" class="modern-btn secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">📊 Reports</a>
            </div>
        </div>

        <!-- Quick Stats -->
        <section style="margin-bottom: 3rem;">
            <div class="dashboard-grid">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-success), #047857);">
                    <h3 style="color: white;">🌱 Active Plants</h3>
                    <div class="stat-number" id="activePlants" style="color: white;">-</div>
                    <div class="stat-label" style="color: #d1fae5;">Currently Growing</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));">
                    <h3 style="color: white;">🧬 Genetics</h3>
                    <div class="stat-number" id="totalGenetics" style="color: white;">-</div>
                    <div class="stat-label" style="color: #ddd6fe;">Available Strains</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-warning), #d97706);">
                    <h3 style="color: white;">🏠 Rooms</h3>
                    <div class="stat-number" id="totalRooms" style="color: white;">-</div>
                    <div class="stat-label" style="color: #fed7aa;">Cultivation Spaces</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-info), #0369a1);">
                    <h3 style="color: white;">🏢 Partners</h3>
                    <div class="stat-number" id="totalCompanies" style="color: white;">-</div>
                    <div class="stat-label" style="color: #bae6fd;">Business Partners</div>
                </div>
            </div>
        </section>

        <!-- Main Navigation Sections -->
        <section>
            <h2 style="text-align: center; margin-bottom: 2rem;">🗂️ System Navigation</h2>
            
            <!-- Plant Management -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>🌱 Plant Management</h3>
                <div class="quick-actions">
                    <a href="dashboard.php" class="quick-action">
                        <span class="quick-action-icon">📊</span>
                        <div class="quick-action-content">
                            <h4>Dashboard</h4>
                            <p>Main cultivation overview and statistics</p>
                        </div>
                    </a>
                    <a href="receive_genetics.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h4>Add Plants</h4>
                            <p>Start new plants from seeds or clones</p>
                        </div>
                    </a>
                    <a href="all_plants.php" class="quick-action">
                        <span class="quick-action-icon">📋</span>
                        <div class="quick-action-content">
                            <h4>All Plants</h4>
                            <p>Complete plant database and management</p>
                        </div>
                    </a>
                    <a href="current_plants.php" class="quick-action">
                        <span class="quick-action-icon">📈</span>
                        <div class="quick-action-content">
                            <h4>Plant Summary</h4>
                            <p>Current plants by stage and room</p>
                        </div>
                    </a>
                    <a href="tracking.php" class="quick-action">
                        <span class="quick-action-icon">🔍</span>
                        <div class="quick-action-content">
                            <h4>Plant Tracking</h4>
                            <p>Complete plant lifecycle tracking</p>
                        </div>
                    </a>
                    <a href="edit_plant.php" class="quick-action">
                        <span class="quick-action-icon">✏️</span>
                        <div class="quick-action-content">
                            <h4>Edit Plant</h4>
                            <p>Modify plant information and details</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Plant Stages -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>🔄 Plant Stages</h3>
                <div class="quick-actions">
                    <a href="plants_clone.php" class="quick-action">
                        <span class="quick-action-icon">🌿</span>
                        <div class="quick-action-content">
                            <h4>Clone Stage</h4>
                            <p>Manage clones and cuttings</p>
                        </div>
                    </a>
                    <a href="plants_veg.php" class="quick-action">
                        <span class="quick-action-icon">🌱</span>
                        <div class="quick-action-content">
                            <h4>Vegetative Stage</h4>
                            <p>Manage vegetative growth plants</p>
                        </div>
                    </a>
                    <a href="plants_flower.php" class="quick-action">
                        <span class="quick-action-icon">🌸</span>
                        <div class="quick-action-content">
                            <h4>Flowering Stage</h4>
                            <p>Manage flowering plants</p>
                        </div>
                    </a>
                    <a href="plants_mother.php" class="quick-action">
                        <span class="quick-action-icon">👑</span>
                        <div class="quick-action-content">
                            <h4>Mother Plants</h4>
                            <p>Manage mother plants for cloning</p>
                        </div>
                    </a>
                    <a href="take_clones.php" class="quick-action">
                        <span class="quick-action-icon">🔄</span>
                        <div class="quick-action-content">
                            <h4>Take Clones</h4>
                            <p>Create new clones from mothers</p>
                        </div>
                    </a>
                    <a href="move_plant_stage.php" class="quick-action">
                        <span class="quick-action-icon">📦</span>
                        <div class="quick-action-content">
                            <h4>Move Plants</h4>
                            <p>Move plants between stages</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Operations -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>⚖️ Operations</h3>
                <div class="quick-actions">
                    <a href="harvest_plants.php" class="quick-action">
                        <span class="quick-action-icon">✂️</span>
                        <div class="quick-action-content">
                            <h4>Harvest Plants</h4>
                            <p>Process plants for harvest or destruction</p>
                        </div>
                    </a>
                    <a href="destroyed_plants.php" class="quick-action">
                        <span class="quick-action-icon">🗑️</span>
                        <div class="quick-action-content">
                            <h4>Destroyed Plants</h4>
                            <p>View destroyed plants with reasons</p>
                        </div>
                    </a>
                    <a href="record_dry_weight.php" class="quick-action">
                        <span class="quick-action-icon">⚖️</span>
                        <div class="quick-action-content">
                            <h4>Record Weights</h4>
                            <p>Track flower weight transactions</p>
                        </div>
                    </a>
                    <a href="batch_details.php" class="quick-action">
                        <span class="quick-action-icon">📦</span>
                        <div class="quick-action-content">
                            <h4>Batch Operations</h4>
                            <p>View batch harvest and destruction details</p>
                        </div>
                    </a>
                    <a href="generate_shipping_manifest.php" class="quick-action">
                        <span class="quick-action-icon">📦</span>
                        <div class="quick-action-content">
                            <h4>Shipping Manifest</h4>
                            <p>Generate shipping documentation</p>
                        </div>
                    </a>
                    <a href="current_dried_flower.php" class="quick-action">
                        <span class="quick-action-icon">🏺</span>
                        <div class="quick-action-content">
                            <h4>Dried Flower</h4>
                            <p>Current dried flower inventory</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Genetics & Inventory -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>🧬 Genetics & Inventory</h3>
                <div class="quick-actions">
                    <a href="manage_genetics.php" class="quick-action">
                        <span class="quick-action-icon">🧬</span>
                        <div class="quick-action-content">
                            <h4>Manage Genetics</h4>
                            <p>View and organize genetics library</p>
                        </div>
                    </a>
                    <a href="add_new_genetics.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h4>Add Genetics</h4>
                            <p>Add new strains to database</p>
                        </div>
                    </a>
                    <a href="genetics_details.php" class="quick-action">
                        <span class="quick-action-icon">📋</span>
                        <div class="quick-action-content">
                            <h4>Genetics Details</h4>
                            <p>Detailed genetics information</p>
                        </div>
                    </a>
                    <a href="seed_stock.php" class="quick-action">
                        <span class="quick-action-icon">🌰</span>
                        <div class="quick-action-content">
                            <h4>Seed Stock</h4>
                            <p>Manage seed inventory and batches</p>
                        </div>
                    </a>
                    <a href="list_all_genetics.php" class="quick-action">
                        <span class="quick-action-icon">📜</span>
                        <div class="quick-action-content">
                            <h4>List All Genetics</h4>
                            <p>Complete genetics listing</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Facilities -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>🏠 Facilities</h3>
                <div class="quick-actions">
                    <a href="manage_rooms.php" class="quick-action">
                        <span class="quick-action-icon">🏠</span>
                        <div class="quick-action-content">
                            <h4>Manage Rooms</h4>
                            <p>Set up and organize cultivation spaces</p>
                        </div>
                    </a>
                    <a href="add_room.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h4>Add Room</h4>
                            <p>Create new cultivation rooms</p>
                        </div>
                    </a>
                    <a href="annual_stocktake.php" class="quick-action">
                        <span class="quick-action-icon">📊</span>
                        <div class="quick-action-content">
                            <h4>Annual Stocktake</h4>
                            <p>Annual inventory and compliance</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Business Management -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>🏢 Business Management</h3>
                <div class="quick-actions">
                    <a href="own_company.php" class="quick-action">
                        <span class="quick-action-icon">🏢</span>
                        <div class="quick-action-content">
                            <h4>Company Information</h4>
                            <p>Update your business details</p>
                        </div>
                    </a>
                    <a href="manage_companies.php" class="quick-action">
                        <span class="quick-action-icon">🤝</span>
                        <div class="quick-action-content">
                            <h4>Partner Companies</h4>
                            <p>Manage business partners and clients</p>
                        </div>
                    </a>
                    <a href="add_verified_company.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h4>Add Company</h4>
                            <p>Register new partners or clients</p>
                        </div>
                    </a>
                    <a href="offtake_agreements.php" class="quick-action">
                        <span class="quick-action-icon">📄</span>
                        <div class="quick-action-content">
                            <h4>Offtake Agreements</h4>
                            <p>Manage buyer contracts</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Reports & Analytics -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>📊 Reports & Analytics</h3>
                <div class="quick-actions">
                    <a href="reports.php" class="quick-action">
                        <span class="quick-action-icon">📊</span>
                        <div class="quick-action-content">
                            <h4>All Reports</h4>
                            <p>Comprehensive reporting dashboard</p>
                        </div>
                    </a>
                    <a href="generate_plant_count_report.php" class="quick-action">
                        <span class="quick-action-icon">🌿</span>
                        <div class="quick-action-content">
                            <h4>Plant Count Report</h4>
                            <p>Generate plant count reports</p>
                        </div>
                    </a>
                    <a href="generate_report.php" class="quick-action">
                        <span class="quick-action-icon">📋</span>
                        <div class="quick-action-content">
                            <h4>Custom Reports</h4>
                            <p>Create custom reports</p>
                        </div>
                    </a>
                    <a href="reporting.php" class="quick-action">
                        <span class="quick-action-icon">📈</span>
                        <div class="quick-action-content">
                            <h4>Analytics</h4>
                            <p>Advanced analytics and insights</p>
                        </div>
                    </a>
                    <a href="this_months_flower_transactions.php" class="quick-action">
                        <span class="quick-action-icon">📈</span>
                        <div class="quick-action-content">
                            <h4>This Month's Transactions</h4>
                            <p>Current month flower transactions</p>
                        </div>
                    </a>
                    <a href="last_months_flower_transactions.php" class="quick-action">
                        <span class="quick-action-icon">📉</span>
                        <div class="quick-action-content">
                            <h4>Last Month's Transactions</h4>
                            <p>Previous month flower transactions</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Compliance -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3>📋 Compliance & Documentation</h3>
                <div class="quick-actions">
                    <a href="company_licenses.php" class="quick-action">
                        <span class="quick-action-icon">📜</span>
                        <div class="quick-action-content">
                            <h4>Company Licenses</h4>
                            <p>Manage licensing documentation</p>
                        </div>
                    </a>
                    <a href="police_vet_check_records.php" class="quick-action">
                        <span class="quick-action-icon">👮</span>
                        <div class="quick-action-content">
                            <h4>Police Vet Records</h4>
                            <p>Police vetting documentation</p>
                        </div>
                    </a>
                    <a href="sops.php" class="quick-action">
                        <span class="quick-action-icon">📖</span>
                        <div class="quick-action-content">
                            <h4>Standard Procedures</h4>
                            <p>SOPs and operational guidelines</p>
                        </div>
                    </a>
                    <a href="chain_of_custody_documents.php" class="quick-action">
                        <span class="quick-action-icon">🔗</span>
                        <div class="quick-action-content">
                            <h4>Chain of Custody</h4>
                            <p>Product movement documentation</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Administration -->
            <div class="modern-card">
                <h3>⚙️ System Administration</h3>
                <div class="quick-actions">
                    <a href="administration.php" class="quick-action">
                        <span class="quick-action-icon">⚙️</span>
                        <div class="quick-action-content">
                            <h4>Administration</h4>
                            <p>System settings and configuration</p>
                        </div>
                    </a>
                    <a href="show_database.php" class="quick-action">
                        <span class="quick-action-icon">💾</span>
                        <div class="quick-action-content">
                            <h4>Database Export</h4>
                            <p>Export database for analysis</p>
                        </div>
                    </a>
                    <a href="phpinfo.php" class="quick-action" target="_blank">
                        <span class="quick-action-icon">🐘</span>
                        <div class="quick-action-content">
                            <h4>PHP Information</h4>
                            <p>System PHP configuration</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer style="text-align: center; padding: 2rem; margin-top: 3rem; border-top: 1px solid var(--border-color);">
        <p style="color: var(--text-secondary); margin: 0;">
            🌿 CultivationTracker - Professional Cannabis Management System<br>
            <small>Version 2.12.0 | Built for compliance and efficiency</small>
        </p>
    </footer>

    <script>
        // Load quick stats
        function loadQuickStats() {
            // Load active plants count
            fetch('get_all_plants_detailed.php')
                .then(response => response.json())
                .then(plants => {
                    const activePlants = plants.filter(p => p.status === 'Growing').length;
                    document.getElementById('activePlants').textContent = activePlants;
                })
                .catch(error => console.error('Error loading plants:', error));

            // Load genetics count
            fetch('get_genetics.php')
                .then(response => response.json())
                .then(genetics => {
                    document.getElementById('totalGenetics').textContent = genetics.length;
                })
                .catch(error => console.error('Error loading genetics:', error));

            // Load rooms count
            fetch('get_all_rooms.php')
                .then(response => response.json())
                .then(rooms => {
                    document.getElementById('totalRooms').textContent = rooms.length;
                })
                .catch(error => console.error('Error loading rooms:', error));

            // Load companies count
            fetch('get_companies.php')
                .then(response => response.json())
                .then(companies => {
                    document.getElementById('totalCompanies').textContent = companies.length;
                })
                .catch(error => console.error('Error loading companies:', error));
        }

        // Load stats on page load
        loadQuickStats();
    </script>
</body>
</html>