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
    <title>CultivationTracker - Plant Tracking</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌿 Plant Tracking</h1>
                <p style="color: var(--text-secondary); margin: 0;">Complete plant lifecycle management and tracking system</p>
            </div>
        </div>

        <!-- Plant Management Section -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h2>🌱 Plant & Product Management</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <a href="all_plants.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">🌿</div>
                        <div>
                            <h3>All Plants</h3>
                            <p>View current and historical plants, live / harvested / destroyed</p>
                        </div>
                    </div>
                </a>
                
                <a href="receive_genetics.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">➕</div>
                        <div>
                            <h3>Receive Plants</h3>
                            <p>Add new plants from license holders, Form D declarations, or clones from mothers</p>
                        </div>
                    </div>
                </a>
                
                <a href="harvest_plants.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">✂️</div>
                        <div>
                            <h3>Harvest/Destroy/Send</h3>
                            <p>Process plants for harvest, destruction, or shipping</p>
                        </div>
                    </div>
                </a>
                
                <a href="record_dry_weight.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">⚖️</div>
                        <div>
                            <h3>Record Dry Weight</h3>
                            <p>Track dry weight changes for testing, destruction, or inventory</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Stage Management Section -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h2>🔄 Plant Stage Management</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <a href="plants_clone.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">🌿</div>
                        <div>
                            <h3>Clone Stage Plants</h3>
                            <p>Manage plants in clone/cutting stage and move to vegetative</p>
                        </div>
                    </div>
                </a>
                
                <a href="plants_veg.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">🌱</div>
                        <div>
                            <h3>Vegetative Stage Plants</h3>
                            <p>Manage plants in vegetative stage and move to flower or back to clone (mothers)</p>
                        </div>
                    </div>
                </a>
                
                <a href="plants_flower.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">🌸</div>
                        <div>
                            <h3>Flowering Stage Plants</h3>
                            <p>Manage flowering plants, harvest, destroy, or move between flower rooms</p>
                        </div>
                    </div>
                </a>
                
                <a href="current_plants.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">📊</div>
                        <div>
                            <h3>Current Plants Overview</h3>
                            <p>View all current plants by stage and room</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Shipping Section -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h2>📦 Shipping Management</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <a href="generate_shipping_manifest.php" class="tracking-link">
                    <div class="tracking-item">
                        <div class="tracking-icon">📋</div>
                        <div>
                            <h3>Generate Shipping Manifest</h3>
                            <p>Create shipping documentation for plant transfers</p>
                        </div>
                    </div>
                </a>
                
                <div class="tracking-item disabled">
                    <div class="tracking-icon">📝</div>
                    <div>
                        <h3>Amend / Complete Manifest</h3>
                        <p>Coming soon - Modify existing shipping manifests</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recalls Section -->
        <div class="modern-card">
            <h2>⚠️ Recall Management</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div class="tracking-item disabled">
                    <div class="tracking-icon">🚨</div>
                    <div>
                        <h3>Initiate Recall</h3>
                        <p>Coming soon - Manage product recalls and notifications</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .tracking-link {
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform 0.2s ease;
        }

        .tracking-link:hover {
            transform: translateY(-2px);
        }

        .tracking-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .tracking-item:hover {
            border-color: var(--accent-primary);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        .tracking-item.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .tracking-item.disabled:hover {
            border-color: var(--border-color);
            box-shadow: none;
            transform: none;
        }

        .tracking-icon {
            font-size: 2rem;
            min-width: 3rem;
            text-align: center;
        }

        .tracking-item h3 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .tracking-item p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
        }
    </style>

    <script src="js/growcart.js"></script>
</body>
</html>