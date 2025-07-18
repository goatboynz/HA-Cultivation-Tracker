        <nav class="modern-nav">
            <div class="nav-brand">
                <a href="dashboard.php">
                    <strong>🌿 MediFlower</strong>
                    <small>Cultivation Tracker</small>
                </a>
            </div>
            
            <input type="checkbox" id="nav-toggle" class="nav-toggle">
            <label for="nav-toggle" class="nav-toggle-label">
                <span class="hamburger"></span>
            </label>
            
            <div class="nav-menu">
                <a href="dashboard.php" class="nav-item">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
                
                <div class="nav-dropdown">
                    <button class="nav-item dropdown-toggle">
                        <span class="nav-icon">🌱</span>
                        Plants
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="receive_genetics.php">➕ Add Plants</a>
                        <a href="all_plants.php">📋 All Plants</a>
                        <a href="current_plants.php">📈 Plant Summary</a>
                        <a href="plants_clone.php">🌿 Clone Stage</a>
                        <a href="plants_veg.php">🌱 Veg Stage</a>
                        <a href="plants_flower.php">🌸 Flower Stage</a>
                        <a href="plants_mother.php">👑 Mother Plants</a>
                        <a href="take_clones.php">🔄 Take Clones</a>
                        <a href="harvest_plants.php">✂️ Harvest Plants</a>
                        <a href="tracking.php">🔍 Plant Tracking</a>
                    </div>
                </div>
                
                <div class="nav-dropdown">
                    <button class="nav-item dropdown-toggle">
                        <span class="nav-icon">🧬</span>
                        Genetics
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="manage_genetics.php">🧬 Manage Genetics</a>
                        <a href="seed_stock.php">🌰 Seed Stock</a>
                    </div>
                </div>
                
                <div class="nav-dropdown">
                    <button class="nav-item dropdown-toggle">
                        <span class="nav-icon">🏠</span>
                        Rooms
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="manage_rooms.php">🏠 Manage Rooms</a>
                    </div>
                </div>
                
                <div class="nav-dropdown">
                    <button class="nav-item dropdown-toggle">
                        <span class="nav-icon">⚖️</span>
                        Operations
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="record_dry_weight.php">⚖️ Record Weights</a>
                        <a href="generate_shipping_manifest.php">📦 Shipping</a>
                        <a href="current_dried_flower.php">🏺 Dried Flower</a>
                    </div>
                </div>
                
                <a href="reports.php" class="nav-item">
                    <span class="nav-icon">📊</span>
                    Reports
                </a>
                
                <a href="administration.php" class="nav-item">
                    <span class="nav-icon">⚙️</span>
                    Admin
                </a>
                
                <a href="logout.php" class="nav-item logout">
                    <span class="nav-icon">🚪</span>
                    Logout
                </a>
            </div>
        </nav>
