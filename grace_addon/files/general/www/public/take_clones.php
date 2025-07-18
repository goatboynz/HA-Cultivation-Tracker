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
    <title>CultivationTracker - Take Clones</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🔄 Take Clones</h1>
                <p style="color: var(--text-secondary); margin: 0;">Create new clones from mother plants</p>
            </div>
            <a href="plants_mother.php" class="modern-btn secondary">← Back to Mothers</a>
        </div>

        <!-- Mother Plant Info -->
        <div id="motherInfo" class="modern-card" style="margin-bottom: 2rem;">
            <!-- Mother plant info will be loaded here -->
        </div>

        <!-- Clone Form -->
        <div class="modern-card">
            <h3>🌿 Clone Details</h3>
            <form id="takeClonesForm" action="handle_take_clones.php" method="post" style="margin-top: 1rem;">
                <input type="hidden" id="motherId" name="motherId" value="">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label for="cloneCount">Number of Clones:</label>
                        <input type="number" id="cloneCount" name="cloneCount" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" min="1" required>
                    </div>
                    
                    <div>
                        <label for="roomName">Clone Room:</label>
                        <select id="roomName" name="roomName" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" required>
                            <option value="" disabled selected>Select Clone Room</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" rows="3" placeholder="Optional notes about this clone batch"></textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="modern-btn">🔄 Take Clones</button>
                    <a href="plants_mother.php" class="modern-btn secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const motherId = urlParams.get('mother_id');
        
        if (!motherId) {
            window.location.href = 'plants_mother.php';
        }

        document.getElementById('motherId').value = motherId;

        // Load mother plant info
        fetch(`get_plant_details.php?id=${motherId}`)
            .then(response => response.json())
            .then(mother => {
                document.getElementById('motherInfo').innerHTML = `
                    <h3>Mother Plant: ${mother.genetics_name}</h3>
                    <p><strong>Tag:</strong> ${mother.plant_tag || 'ID: ' + mother.id}</p>
                    <p><strong>Room:</strong> ${mother.room_name}</p>
                    <p><strong>Status:</strong> ${mother.status}</p>
                `;
            });

        // Load clone rooms
        fetch('get_rooms_by_type.php?type=Clone')
            .then(response => response.json())
            .then(rooms => {
                const select = document.getElementById('roomName');
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.name;
                    select.appendChild(option);
                });
            });
    </script>
</body>
</html>