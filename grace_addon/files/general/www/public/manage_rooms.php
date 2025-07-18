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
    <title>GRACe - Manage Rooms</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>

        <div style="margin-bottom: 2rem;">
            <h1>🏠 Manage Rooms</h1>
            <p style="color: var(--text-secondary); margin: 0;">Set up and manage your cultivation rooms</p>
        </div>

        <div class="modern-card" style="margin-bottom: 2rem;">
            <h2>➕ Add New Room</h2>
            <div style="margin: 1rem 0;">
                <button id="seedRoomsBtn" class="modern-btn secondary">🏗️ Create Default Rooms</button>
            </div>
            <form id="addRoomForm" class="modern-form" style="background: none; border: none; padding: 0; box-shadow: none;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin: 1rem 0;">
                    <div>
                        <label for="roomName">Room Name:</label>
                        <input type="text" id="roomName" name="roomName" required>
                    </div>
                    <div>
                        <label for="roomType">Room Type:</label>
                        <select id="roomType" name="roomType" required>
                            <option value="">Select Type</option>
                            <option value="Clone">🌿 Clone Room</option>
                            <option value="Veg">🌱 Vegetative Room</option>
                            <option value="Flower">🌸 Flower Room</option>
                            <option value="Mother">👑 Mother Room</option>
                            <option value="Dry">🏺 Drying Room</option>
                            <option value="Storage">📦 Storage Room</option>
                        </select>
                    </div>
                </div>
                <div style="margin: 1rem 0;">
                    <label for="roomDescription">Description (optional):</label>
                    <textarea id="roomDescription" name="roomDescription" rows="3"></textarea>
                </div>
                <button type="submit" class="modern-btn">💾 Add Room</button>
            </form>
        </div>

        <div class="modern-card">
            <h2>🏠 Your Rooms</h2>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table id="roomsTable" class="modern-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="js/growcart.js"></script>
    <script>
        const addRoomForm = document.getElementById('addRoomForm');
        const roomsTable = document.getElementById('roomsTable').getElementsByTagName('tbody')[0];
        const statusMessage = document.getElementById('statusMessage');

        function showStatusMessage(message, type) {
            statusMessage.textContent = message;
            statusMessage.classList.add(type);
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
                statusMessage.classList.remove(type);
            }, 5000);
        }

        function loadRooms() {
            fetch('get_all_rooms.php')
                .then(response => response.json())
                .then(rooms => {
                    roomsTable.innerHTML = '';
                    rooms.forEach(room => {
                        const row = roomsTable.insertRow();
                        row.innerHTML = `
                            <td>${room.name}</td>
                            <td>${room.room_type}</td>
                            <td>${room.description || ''}</td>
                            <td>
                                <button onclick="deleteRoom(${room.id})" class="modern-btn secondary" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; color: var(--accent-error); border-color: var(--accent-error);">🗑️ Delete</button>
                            </td>
                        `;
                    });
                })
                .catch(error => console.error('Error loading rooms:', error));
        }

        function deleteRoom(roomId) {
            if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
                fetch('delete_room.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'room_id=' + roomId
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showStatusMessage('Room deleted successfully', 'success');
                        loadRooms();
                    } else {
                        showStatusMessage('Error deleting room: ' + result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error deleting room', 'error');
                });
            }
        }

        addRoomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_room.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showStatusMessage('Room added successfully', 'success');
                    addRoomForm.reset();
                    loadRooms();
                } else {
                    showStatusMessage('Error adding room: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusMessage('Error adding room', 'error');
            });
        });

        // Seed default rooms functionality
        document.getElementById('seedRoomsBtn').addEventListener('click', function() {
            if (confirm('This will create default rooms (Clone Room 1, Veg Room 1, Flower Room 1, etc.). Continue?')) {
                fetch('seed_default_rooms.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showStatusMessage('Default rooms created successfully', 'success');
                        loadRooms();
                    } else {
                        showStatusMessage('Error creating default rooms: ' + result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error creating default rooms', 'error');
                });
            }
        });

        // Load rooms on page load
        loadRooms();
    </script>
</body>
</html>