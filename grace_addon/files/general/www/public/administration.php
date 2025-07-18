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
    <title>GRACe - Administration</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div style="margin-bottom: 2rem;">
            <h1>⚙️ Administration</h1>
            <p style="color: var(--text-secondary);">Manage your cultivation system settings and configurations</p>
        </div>

        <div class="dashboard-grid">
            <div class="modern-card">
                <h2>🏢 Contact Management</h2>
                <div class="quick-actions" style="grid-template-columns: 1fr;">
                    <a href="add_verified_company.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h3>Add Verified Company</h3>
                            <p>Add a company you'll send flower / plants to, such as an offtake buyer or testing lab</p>
                        </div>
                    </a>
                    <a href="manage_companies.php" class="quick-action">
                        <span class="quick-action-icon">📝</span>
                        <div class="quick-action-content">
                            <h3>Manage Companies</h3>
                            <p>View and edit existing verified companies</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="modern-card">
                <h2>🧬 Genetics Management</h2>
                <div class="quick-actions" style="grid-template-columns: 1fr;">
                    <a href="add_new_genetics.php" class="quick-action">
                        <span class="quick-action-icon">🧬</span>
                        <div class="quick-action-content">
                            <h3>Add New Genetics</h3>
                            <p>Any genetics you'll have as either plants/flower</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="modern-card">
                <h2>🏠 Room Management</h2>
                <div class="quick-actions" style="grid-template-columns: 1fr;">
                    <a href="manage_rooms.php" class="quick-action">
                        <span class="quick-action-icon">🏠</span>
                        <div class="quick-action-content">
                            <h3>Manage Rooms</h3>
                            <p>Add and manage grow rooms for different plant stages</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="modern-card">
                <h2>📋 Record Management</h2>
                <div class="quick-actions" style="grid-template-columns: 1fr;">
                    <a href="police_vet_check_records.php" class="quick-action">
                        <span class="quick-action-icon">👮</span>
                        <div class="quick-action-content">
                            <h3>Police Vet Check Records</h3>
                            <p>Manage police vetting documentation</p>
                        </div>
                    </a>
                    <a href="sops.php" class="quick-action">
                        <span class="quick-action-icon">📖</span>
                        <div class="quick-action-content">
                            <h3>Manage SOPs</h3>
                            <p>Standard Operating Procedures</p>
                        </div>
                    </a>
                    <a href="offtake_agreements.php" class="quick-action">
                        <span class="quick-action-icon">🤝</span>
                        <div class="quick-action-content">
                            <h3>Offtake Agreements</h3>
                            <p>Manage buyer agreements</p>
                        </div>
                    </a>
                    <a href="company_licenses.php" class="quick-action">
                        <span class="quick-action-icon">📜</span>
                        <div class="quick-action-content">
                            <h3>Company Licenses</h3>
                            <p>Manage licensing documentation</p>
                        </div>
                    </a>
                    <a href="chain_of_custody_documents.php" class="quick-action">
                        <span class="quick-action-icon">🔗</span>
                        <div class="quick-action-content">
                            <h3>Chain of Custody Documents</h3>
                            <p>Track product movement documentation</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="modern-card">
                <h2>🔧 System Management</h2>
                <div class="quick-actions" style="grid-template-columns: 1fr;">
                    <a href="own_company.php" class="quick-action">
                        <span class="quick-action-icon">🏢</span>
                        <div class="quick-action-content">
                            <h3>Update Company Information</h3>
                            <p>Enter your own company information, so we can populate CoC docs etc</p>
                        </div>
                    </a>
                    <a href="show_database.php" class="quick-action">
                        <span class="quick-action-icon">💾</span>
                        <div class="quick-action-content">
                            <h3>Dump Database</h3>
                            <p>Export database for backup or analysis</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script src="js/growcart.js"></script>
</body>
</html>
