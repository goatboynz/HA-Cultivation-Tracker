<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>GRACe - Manage Rooms</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div>

        <h1>Manage Rooms</h1>

        <section>
            <h2>Add New Room</h2>
            <div class="grid">
                <button id="seedRoomsBtn" class="secondary">Create Default Rooms</button>
            </div>
            <form id="addRoomForm">
                <div class="grid">
                    <div>
                        <label for="roomName">Room Name:</label>
                        <input type="text" id="roomName" name="roomName" required>
                    </div>
                    <div>
                        <label for="roomType">Room Type:</label>
                        <select id="roomType" name="roomType" required>
                            <option value="">Select Type</option>
                            <option value="Clone">Clone Room</option>
                            <option value="Veg">Vegetative Room</option>
                            <option value="Flower">Flower Room</option>
                            <option value="Dry">Drying Room</option>
                            <option value="Storage">Storage Room</option>
                        </select>
                    </div>
                </div>
                <label for="roomDescription">Description (optional):</label>
                <textarea id="roomDescription" name="roomDescription" rows="3"></textarea>
                <button type="submit">Add Room</button>
            </form>
        </section>

        <section>
            <h2>Existing Rooms</h2>
            <table id="roomsTable" class="table">
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
        </section>
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
                                <button onclick="deleteRoom(${room.id})" class="secondary">Delete</button>
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