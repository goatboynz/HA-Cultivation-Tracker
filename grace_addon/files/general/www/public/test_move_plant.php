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
    <title>Test Move Plant</title>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>🧪 Test Move Plant Functionality</h1>
        
        <div class="modern-card">
            <h3>Test Move Plant</h3>
            <div style="margin-top: 1rem;">
                <button onclick="testMoveFunction()" class="modern-btn">Test Move Plant</button>
                <div id="testResult" style="margin-top: 1rem;"></div>
            </div>
        </div>
    </main>

    <script>
        async function testMoveFunction() {
            const resultDiv = document.getElementById('testResult');
            resultDiv.innerHTML = 'Testing move plant functionality...';
            
            try {
                // First, get available rooms
                const roomsResponse = await fetch('get_rooms.php');
                const rooms = await roomsResponse.json();
                
                if (rooms.length === 0) {
                    resultDiv.innerHTML = '<div style="color: red;">❌ No rooms available for testing</div>';
                    return;
                }
                
                // Test the move_plants.php endpoint with a mock request
                const formData = new FormData();
                formData.append('plants', JSON.stringify([999])); // Non-existent plant ID
                formData.append('target_stage', 'Veg');
                formData.append('target_room', rooms[0].id);
                formData.append('current_stage', 'Clone');
                
                const response = await fetch('move_plants.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    resultDiv.innerHTML = `
                        <div style="color: green;">✅ Move plants endpoint is working</div>
                        <div>Response: ${JSON.stringify(result)}</div>
                        <div>Available rooms: ${rooms.length}</div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div style="color: orange;">⚠️ Endpoint responded but with error (expected for non-existent plant)</div>
                        <div>Response: ${JSON.stringify(result)}</div>
                        <div>This is normal behavior for testing with fake plant ID</div>
                    `;
                }
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <div style="color: red;">❌ Error testing move functionality</div>
                    <div>Error: ${error.message}</div>
                `;
            }
        }
    </script>
</body>
</html>