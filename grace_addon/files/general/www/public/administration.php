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
                <h2>💾 Database Management</h2>
                <div id="statusMessage" class="status-message" style="display: none; margin-bottom: 1rem;"></div>
                
                <!-- Database Info -->
                <div id="databaseInfo" style="background: var(--bg-tertiary); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <h4 style="margin: 0 0 0.5rem 0;">📊 Database Information</h4>
                    <div id="dbInfoContent" style="font-size: 0.9rem; color: var(--text-secondary);">
                        Loading database information...
                    </div>
                </div>
                
                <div class="quick-actions" style="grid-template-columns: 1fr;">
                    <div class="quick-action" style="cursor: default; background: var(--bg-secondary);">
                        <span class="quick-action-icon">📥</span>
                        <div class="quick-action-content">
                            <h3>Backup Database</h3>
                            <p>Download a complete backup of your cultivation data</p>
                            <button onclick="backupDatabase()" class="modern-btn" style="margin-top: 0.5rem;">📥 Download Backup</button>
                        </div>
                    </div>
                    <div class="quick-action" style="cursor: default; background: var(--bg-secondary);">
                        <span class="quick-action-icon">📤</span>
                        <div class="quick-action-content">
                            <h3>Restore Database</h3>
                            <p>Upload and restore from a previous backup file</p>
                            <div style="margin-top: 0.5rem;">
                                <input type="file" id="restoreFile" accept=".db,.sqlite,.sqlite3" style="display: none;" onchange="handleFileSelect(this)">
                                <button onclick="document.getElementById('restoreFile').click()" class="modern-btn secondary" style="margin-right: 0.5rem;">📁 Select File</button>
                                <button onclick="restoreDatabase()" class="modern-btn" id="restoreBtn" disabled>📤 Restore Database</button>
                            </div>
                            <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">⚠️ This will replace all current data!</small>
                        </div>
                    </div>
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
    <script>
        let selectedFile = null;

        // Load database information on page load
        function loadDatabaseInfo() {
            fetch('database_info.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const info = data.data;
                        const content = document.getElementById('dbInfoContent');
                        content.innerHTML = `
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div><strong>File Size:</strong> ${info.file_size_formatted}</div>
                                <div><strong>Last Modified:</strong> ${info.last_modified_formatted}</div>
                                <div><strong>Total Records:</strong> ${info.total_records.toLocaleString()}</div>
                                <div><strong>Version:</strong> ${info.version}</div>
                            </div>
                            <details style="margin-top: 0.5rem;">
                                <summary style="cursor: pointer; color: var(--text-primary);">Table Details</summary>
                                <div style="margin-top: 0.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                                    ${Object.entries(info.tables).map(([table, count]) => 
                                        `<div><strong>${table}:</strong> ${count.toLocaleString()}</div>`
                                    ).join('')}
                                </div>
                            </details>
                        `;
                    } else {
                        document.getElementById('dbInfoContent').innerHTML = 'Error loading database information';
                    }
                })
                .catch(error => {
                    console.error('Error loading database info:', error);
                    document.getElementById('dbInfoContent').innerHTML = 'Error loading database information';
                });
        }

        // Load database info when page loads
        document.addEventListener('DOMContentLoaded', loadDatabaseInfo);

        function showStatusMessage(message, type) {
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = message;
            statusMessage.className = `status-message ${type}`;
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
            }, 5000);
        }

        function backupDatabase() {
            showStatusMessage('Creating database backup...', 'info');
            
            fetch('backup_database.php', {
                method: 'POST'
            })
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('Backup failed');
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `cultivation_backup_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.db`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                showStatusMessage('Database backup downloaded successfully!', 'success');
            })
            .catch(error => {
                console.error('Backup error:', error);
                showStatusMessage('Error creating backup: ' + error.message, 'error');
            });
        }

        function handleFileSelect(input) {
            selectedFile = input.files[0];
            const restoreBtn = document.getElementById('restoreBtn');
            
            if (selectedFile) {
                restoreBtn.disabled = false;
                restoreBtn.textContent = `📤 Restore ${selectedFile.name}`;
            } else {
                restoreBtn.disabled = true;
                restoreBtn.textContent = '📤 Restore Database';
            }
        }

        function restoreDatabase() {
            if (!selectedFile) {
                showStatusMessage('Please select a backup file first', 'error');
                return;
            }

            if (!confirm('⚠️ WARNING: This will replace ALL current data with the backup file. This action cannot be undone. Are you sure you want to continue?')) {
                return;
            }

            if (!confirm('🔴 FINAL CONFIRMATION: All your current plants, genetics, rooms, and other data will be permanently lost. Type "RESTORE" in the next dialog to confirm.')) {
                return;
            }

            const userConfirmation = prompt('Type "RESTORE" to confirm database restoration:');
            if (userConfirmation !== 'RESTORE') {
                showStatusMessage('Restoration cancelled - confirmation text did not match', 'info');
                return;
            }

            showStatusMessage('Restoring database... Please wait and do not refresh the page.', 'info');

            const formData = new FormData();
            formData.append('backup_file', selectedFile);

            fetch('restore_database.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusMessage('Database restored successfully! Refreshing page...', 'success');
                    // Reset file selection
                    document.getElementById('restoreFile').value = '';
                    selectedFile = null;
                    document.getElementById('restoreBtn').disabled = true;
                    document.getElementById('restoreBtn').textContent = '📤 Restore Database';
                    // Reload database info
                    loadDatabaseInfo();
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showStatusMessage('Error restoring database: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Restore error:', error);
                showStatusMessage('Error restoring database: ' + error.message, 'error');
            });
        }
    </script>
</body>
</html>
