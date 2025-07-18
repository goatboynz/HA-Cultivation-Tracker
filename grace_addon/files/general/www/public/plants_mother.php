<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Mother Plants</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <div class="grid">
            <div>
                <h1>Mother Plants</h1>
                <p><small>Manage your mother plants for clone production</small></p>
            </div>
            <div style="text-align: right;">
                <button onclick="showAddMotherForm()" class="button">Add Mother Plant</button>
            </div>
        </div>

        <!-- Add Mother Plant Form -->
        <div id="addMotherForm" style="display: none;" class="card">
            <h3>Add New Mother Plant</h3>
            <form id="motherForm" action="handle_add_mother.php" method="post">
                <div class="grid">
                    <div>
                        <label for="geneticsName">Genetics:</label>
                        <select id="geneticsName" name="geneticsName" class="input" required>
                            <option value="" disabled selected>Select Genetics</option>
                        </select>
                    </div>
                    <div>
                        <label for="roomName">Room:</label>
                        <select id="roomName" name="roomName" class="input" required>
                            <option value="" disabled selected>Select Room</option>
                        </select>
                    </div>
                </div>
                <div class="grid">
                    <div>
                        <label for="plantTag">Plant Tag:</label>
                        <input type="text" id="plantTag" name="plantTag" class="input" placeholder="Optional identifier">
                    </div>
                    <div>
                        <label for="notes">Notes:</label>
                        <input type="text" id="notes" name="notes" class="input" placeholder="Optional notes">
                    </div>
                </div>
                <div class="grid">
                    <button type="submit" class="button">Add Mother Plant</button>
                    <button type="button" onclick="hideAddMotherForm()" class="button secondary">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Mother Plants Table -->
        <div class="table-container">
            <table id="motherPlantsTable">
                <thead>
                    <tr>
                        <th>Plant Tag</th>
                        <th>Genetics</th>
                        <th>Room</th>
                        <th>Date Created</th>
                        <th>Clone Count</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="motherPlantsBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function showAddMotherForm() {
            document.getElementById('addMotherForm').style.display = 'block';
            loadGenetics();
            loadMotherRooms();
        }

        function hideAddMotherForm() {
            document.getElementById('addMotherForm').style.display = 'none';
            document.getElementById('motherForm').reset();
        }

        function loadGenetics() {
            fetch('get_genetics.php')
                .then(response => response.json())
                .then(genetics => {
                    const select = document.getElementById('geneticsName');
                    select.innerHTML = '<option value="" disabled selected>Select Genetics</option>';
                    genetics.forEach(g => {
                        const option = document.createElement('option');
                        option.value = g.id;
                        option.textContent = g.name;
                        select.appendChild(option);
                    });
                });
        }

        function loadMotherRooms() {
            // Load all rooms and show them with their types
            fetch('get_all_rooms.php')
                .then(response => response.json())
                .then(rooms => {
                    const select = document.getElementById('roomName');
                    select.innerHTML = '<option value="" disabled selected>Select Room</option>';
                    
                    if (rooms && rooms.length > 0) {
                        rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = `${room.name} (${room.room_type})`;
                            select.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No rooms available - Please add rooms first';
                        option.disabled = true;
                        select.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error loading rooms:', error);
                    const select = document.getElementById('roomName');
                    select.innerHTML = '<option value="" disabled selected>Error loading rooms</option>';
                });
        }

        function loadMotherPlants() {
            fetch('get_mother_plants_detailed.php')
                .then(response => response.json())
                .then(plants => {
                    const tbody = document.getElementById('motherPlantsBody');
                    tbody.innerHTML = '';
                    
                    plants.forEach(plant => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${plant.plant_tag || plant.tracking_number}</td>
                            <td>${plant.genetics_name}</td>
                            <td>${plant.room_name}</td>
                            <td>${new Date(plant.date_created).toLocaleDateString()}</td>
                            <td>${plant.clone_count || 0}</td>
                            <td><span class="status-badge ${plant.status.toLowerCase()}">${plant.status}</span></td>
                            <td>
                                <a href="edit_plant.php?id=${plant.id}" class="button small">Edit</a>
                                <button onclick="takeClones(${plant.id})" class="button small">Take Clones</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error loading mother plants:', error));
        }

        function takeClones(motherId) {
            window.location.href = `take_clones.php?mother_id=${motherId}`;
        }

        // Load mother plants on page load
        loadMotherPlants();

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