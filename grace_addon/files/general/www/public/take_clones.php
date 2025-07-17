<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Take Clones</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <h1>Take Clones</h1>
        <div id="motherInfo" class="card">
            <!-- Mother plant info will be loaded here -->
        </div>

        <form id="takeClonesForm" class="form" action="handle_take_clones.php" method="post">
            <input type="hidden" id="motherId" name="motherId" value="">
            
            <label for="cloneCount">Number of Clones:</label>
            <input type="number" id="cloneCount" name="cloneCount" class="input" min="1" required>

            <label for="roomName">Clone Room:</label>
            <select id="roomName" name="roomName" class="input" required>
                <option value="" disabled selected>Select Clone Room</option>
            </select>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" class="input" rows="3" placeholder="Optional notes about this clone batch"></textarea>

            <button type="submit" class="button">Take Clones</button>
            <a href="plants_mother.php" class="button secondary">Cancel</a>
        </form>
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