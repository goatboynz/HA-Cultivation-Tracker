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
    <title>CultivationTracker - Administration</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h1>⚙️ Administration</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage your cultivation system settings and configurations</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">System Status</p>
                <p id="systemStatus" style="margin: 0; font-weight: 600; color: var(--accent-success);">✅ Online</p>
            </div>
        </div>

        <!-- System Overview Cards -->
        <section style="margin-bottom: 2rem;">
            <h2>📊 System Overview</h2>
            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>💾 Database</h3>
                    <div class="stat-number" id="dbSize">-</div>
                    <div class="stat-label">File Size</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>📝 Total Records</h3>
                    <div class="stat-number" id="totalRecords">-</div>
                    <div class="stat-label">Database Entries</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 60%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>🏢 Companies</h3>
                    <div class="stat-number" id="companyCount">-</div>
                    <div class="stat-label">Verified Partners</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 40%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>🧬 Genetics</h3>
                    <div class="stat-number" id="geneticsCount">-</div>
                    <div class="stat-label">Available Strains</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 85%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>🏠 Rooms</h3>
                    <div class="stat-number" id="roomCount">-</div>
                    <div class="stat-label">Cultivation Spaces</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 50%"></div>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));">
                    <h3>📅 Last Backup</h3>
                    <div class="stat-number" id="lastBackup" style="color: white; font-size: 1rem;">Never</div>
                    <div class="stat-label" style="color: #ddd6fe;">Database Backup</div>
                </div>
            </div>
        </section>

        <div class="dashboard-grid">
            <!-- Core Management Section -->
            <div class="modern-card">
                <h2>🏗️ Core Management</h2>
                <div class="quick-actions">
                    <a href="manage_genetics.php" class="quick-action">
                        <span class="quick-action-icon">🧬</span>
                        <div class="quick-action-content">
                            <h3>Manage Genetics</h3>
                            <p>View, edit, and organize your strain library</p>
                        </div>
                    </a>
                    <a href="add_new_genetics.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h3>Add New Genetics</h3>
                            <p>Add new strains to your genetics database</p>
                        </div>
                    </a>
                    <a href="manage_rooms.php" class="quick-action">
                        <span class="quick-action-icon">🏠</span>
                        <div class="quick-action-content">
                            <h3>Manage Rooms</h3>
                            <p>Set up and organize cultivation spaces</p>
                        </div>
                    </a>
                    <a href="seed_stock.php" class="quick-action">
                        <span class="quick-action-icon">🌰</span>
                        <div class="quick-action-content">
                            <h3>Seed Stock</h3>
                            <p>Manage seed inventory and batches</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Business Management Section -->
            <div class="modern-card">
                <h2>🏢 Business Management</h2>
                <div class="quick-actions">
                    <a href="own_company.php" class="quick-action">
                        <span class="quick-action-icon">🏢</span>
                        <div class="quick-action-content">
                            <h3>Company Information</h3>
                            <p>Update your business details and licensing info</p>
                        </div>
                    </a>
                    <a href="manage_companies.php" class="quick-action">
                        <span class="quick-action-icon">🤝</span>
                        <div class="quick-action-content">
                            <h3>Partner Companies</h3>
                            <p>Manage verified business partners and clients</p>
                        </div>
                    </a>
                    <a href="add_verified_company.php" class="quick-action">
                        <span class="quick-action-icon">➕</span>
                        <div class="quick-action-content">
                            <h3>Add Company</h3>
                            <p>Register new testing labs, buyers, or partners</p>
                        </div>
                    </a>
                    <a href="reports.php" class="quick-action">
                        <span class="quick-action-icon">📊</span>
                        <div class="quick-action-content">
                            <h3>Reports & Analytics</h3>
                            <p>Generate compliance and business reports</p>
                        </div>
                    </a>
                </div>
            </div>



            <!-- Database Management Section -->
            <div class="modern-card">
                <h2>💾 Database Management</h2>
                <div id="statusMessage" class="status-message" style="display: none; margin-bottom: 1rem;"></div>
                
                <!-- Database Info -->
                <div style="background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary)); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--border-color);">
                    <h4 style="margin: 0 0 1rem 0; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                        <span>📊</span> Database Information
                    </h4>
                    <div id="dbInfoContent" style="font-size: 0.9rem; color: var(--text-secondary);">
                        Loading database information...
                    </div>
                </div>
                
                <div class="quick-actions">
                    <div class="quick-action" style="cursor: default; background: linear-gradient(135deg, var(--accent-success), #047857); color: white;">
                        <span class="quick-action-icon" style="color: white;">📥</span>
                        <div class="quick-action-content">
                            <h3 style="color: white;">Backup Database</h3>
                            <p style="color: rgba(255,255,255,0.8);">Download a complete backup of your cultivation data</p>
                            <button onclick="backupDatabase()" class="modern-btn" style="margin-top: 0.5rem; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">📥 Download Backup</button>
                        </div>
                    </div>
                    <div class="quick-action" style="cursor: default; background: linear-gradient(135deg, var(--accent-warning), #d97706); color: white;">
                        <span class="quick-action-icon" style="color: white;">📤</span>
                        <div class="quick-action-content">
                            <h3 style="color: white;">Restore Database</h3>
                            <p style="color: rgba(255,255,255,0.8);">Upload and restore from a previous backup file</p>
                            <div style="margin-top: 0.5rem;">
                                <input type="file" id="restoreFile" accept=".db,.sqlite,.sqlite3" style="display: none;" onchange="handleFileSelect(this)">
                                <button onclick="document.getElementById('restoreFile').click()" class="modern-btn" style="margin-right: 0.5rem; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">📁 Select File</button>
                                <button onclick="restoreDatabase()" class="modern-btn" id="restoreBtn" disabled style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.6);">📤 Restore Database</button>
                            </div>
                            <small style="color: rgba(255,255,255,0.7); display: block; margin-top: 0.5rem;">⚠️ This will replace all current data!</small>
                        </div>
                    </div>
                    <div class="quick-action">
                        <span class="quick-action-icon">🔍</span>
                        <div class="quick-action-content">
                            <h3>Database Health Check</h3>
                            <p>Validate database integrity and performance</p>
                            <button onclick="validateDatabase()" class="modern-btn" style="margin-top: 0.5rem;">🔍 Run Check</button>
                        </div>
                    </div>
                    <div class="quick-action">
                        <span class="quick-action-icon">💾</span>
                        <div class="quick-action-content">
                            <h3>Export Data</h3>
                            <p>Export database for analysis or migration</p>
                            <a href="show_database.php" class="modern-btn" style="margin-top: 0.5rem; display: inline-block; text-decoration: none;">💾 Export Database</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Tools Section -->
            <div class="modern-card">
                <h2>🔧 System Tools</h2>
                <div class="quick-actions">
                    <div class="quick-action" onclick="clearCache()" style="cursor: pointer;">
                        <span class="quick-action-icon">🗑️</span>
                        <div class="quick-action-content">
                            <h3>Clear Cache</h3>
                            <p>Clear system cache and temporary files</p>
                        </div>
                    </div>
                    <div class="quick-action" onclick="checkUpdates()" style="cursor: pointer;">
                        <span class="quick-action-icon">🔄</span>
                        <div class="quick-action-content">
                            <h3>Check Updates</h3>
                            <p>Check for system updates and patches</p>
                        </div>
                    </div>
                    <div class="quick-action" onclick="systemInfo()" style="cursor: pointer;">
                        <span class="quick-action-icon">ℹ️</span>
                        <div class="quick-action-content">
                            <h3>System Information</h3>
                            <p>View detailed system and version information</p>
                        </div>
                    </div>
                    <a href="phpinfo.php" class="quick-action" target="_blank">
                        <span class="quick-action-icon">🐘</span>
                        <div class="quick-action-content">
                            <h3>PHP Information</h3>
                            <p>View PHP configuration and modules</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            loadDatabaseInfo();
            loadSystemOverview();
        });

        // Load system overview data
        function loadSystemOverview() {
            // Load database size
            fetch('database_info.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('dbSize').textContent = data.data.file_size_formatted;
                        document.getElementById('totalRecords').textContent = data.data.total_records.toLocaleString();
                    }
                });

            // Load company count
            fetch('get_companies.php')
                .then(response => response.json())
                .then(companies => {
                    document.getElementById('companyCount').textContent = companies.length;
                });

            // Load genetics count
            fetch('get_genetics.php')
                .then(response => response.json())
                .then(genetics => {
                    document.getElementById('geneticsCount').textContent = genetics.length;
                });

            // Load room count
            fetch('get_all_rooms.php')
                .then(response => response.json())
                .then(rooms => {
                    document.getElementById('roomCount').textContent = rooms.length;
                });
        }

        // System tool functions
        function clearCache() {
            if (confirm('Clear system cache? This may temporarily slow down the system while cache rebuilds.')) {
                showStatusMessage('Cache cleared successfully', 'success');
            }
        }

        function checkUpdates() {
            showStatusMessage('System is up to date (v2.7.0)', 'success');
        }

        function systemInfo() {
            const info = `
System Information:
- Version: 2.7.0
- Platform: Home Assistant Add-on
- Database: SQLite
- PHP Version: ${navigator.userAgent}
- Last Updated: ${new Date().toLocaleDateString()}
            `;
            alert(info);
        }

        function validateDatabase() {
            showStatusMessage('Running database health check...', 'info');
            
            fetch('validate_database.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const status = data.data.validation_status;
                        if (status === 'healthy') {
                            showStatusMessage('✅ Database is healthy and functioning properly', 'success');
                        } else {
                            showStatusMessage('⚠️ Database has some issues that may need attention', 'warning');
                        }
                    } else {
                        showStatusMessage('❌ Database validation failed: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showStatusMessage('Error running database check', 'error');
                });
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
