<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>GRACe - Current Plants</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>Current Plants Overview</h1>

        <div class="grid">
            <label for="hideZeroRowsCheckbox">
                <input type="checkbox" id="hideZeroRowsCheckbox" name="hideZeroRows">
                Hide rows with zero plants
            </label>
        </div>

        <section>
            <h2>Clone Stage</h2>
            <table id="cloneTable" class="table">
                <thead>
                    <tr>
                        <th>Genetics</th>
                        <th>Room</th>
                        <th>Count</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p id="noCloneMessage" style="display: none; text-align: center; font-style: italic;">No plants in clone stage</p>
        </section>

        <section>
            <h2>Vegetative Stage</h2>
            <table id="vegTable" class="table">
                <thead>
                    <tr>
                        <th>Genetics</th>
                        <th>Room</th>
                        <th>Count</th>
                        <th>Date Moved to Veg</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p id="noVegMessage" style="display: none; text-align: center; font-style: italic;">No plants in vegetative stage</p>
        </section>

        <section>
            <h2>Flowering Stage</h2>
            <table id="flowerTable" class="table">
                <thead>
                    <tr>
                        <th>Genetics</th>
                        <th>Room</th>
                        <th>Count</th>
                        <th>Date Moved to Flower</th>
                        <th>Days in Flower</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p id="noFlowerMessage" style="display: none; text-align: center; font-style: italic;">No plants in flowering stage</p>
        </section>

        <section>
            <h2>Summary</h2>
            <div class="grid">
                <div>
                    <h3>Total Plants by Stage</h3>
                    <ul id="stageSummary">
                    </ul>
                </div>
                <div>
                    <h3>Total Plants by Room</h3>
                    <ul id="roomSummary">
                    </ul>
                </div>
            </div>
        </section>
    </main>

    <script src="js/growcart.js"></script> 
    <script>
        const cloneTable = document.getElementById('cloneTable').getElementsByTagName('tbody')[0];
        const vegTable = document.getElementById('vegTable').getElementsByTagName('tbody')[0];
        const flowerTable = document.getElementById('flowerTable').getElementsByTagName('tbody')[0];
        const stageSummary = document.getElementById('stageSummary');
        const roomSummary = document.getElementById('roomSummary');
        const hideZeroCheckbox = document.getElementById('hideZeroRowsCheckbox');

        let allPlantData = {
            clone: [],
            veg: [],
            flower: []
        };

        function loadPlantsByStage(stage, table, noDataElement) {
            fetch(`get_plants_by_stage.php?stage=${stage}`)
                .then(response => response.json())
                .then(plants => {
                    allPlantData[stage.toLowerCase()] = plants;
                    displayPlants(stage, plants, table, noDataElement);
                    updateSummaries();
                })
                .catch(error => console.error(`Error loading ${stage} plants:`, error));
        }

        function displayPlants(stage, plants, table, noDataElement) {
            table.innerHTML = '';
            let visibleCount = 0;

            plants.forEach(plant => {
                const shouldHide = hideZeroCheckbox.checked && plant.count == 0;
                if (!shouldHide) {
                    visibleCount++;
                    const row = table.insertRow();
                    
                    if (stage === 'Flower') {
                        const daysInFlower = Math.floor((new Date() - new Date(plant.date_stage_changed)) / (1000 * 60 * 60 * 24));
                        row.innerHTML = `
                            <td>${plant.genetics_name}</td>
                            <td>${plant.room_name || 'Unassigned'}</td>
                            <td>${plant.count}</td>
                            <td>${new Date(plant.date_stage_changed).toLocaleDateString()}</td>
                            <td>${daysInFlower}</td>
                        `;
                    } else {
                        const dateField = stage === 'Clone' ? plant.date_created : plant.date_stage_changed;
                        row.innerHTML = `
                            <td>${plant.genetics_name}</td>
                            <td>${plant.room_name || 'Unassigned'}</td>
                            <td>${plant.count}</td>
                            <td>${new Date(dateField).toLocaleDateString()}</td>
                        `;
                    }
                }
            });

            noDataElement.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        function updateSummaries() {
            // Stage summary
            const stageTotals = {
                Clone: 0,
                Veg: 0,
                Flower: 0
            };

            Object.keys(allPlantData).forEach(stage => {
                const stageKey = stage.charAt(0).toUpperCase() + stage.slice(1);
                stageTotals[stageKey] = allPlantData[stage].reduce((sum, plant) => sum + parseInt(plant.count), 0);
            });

            stageSummary.innerHTML = '';
            Object.keys(stageTotals).forEach(stage => {
                const li = document.createElement('li');
                li.textContent = `${stage}: ${stageTotals[stage]} plants`;
                stageSummary.appendChild(li);
            });

            // Room summary
            const roomTotals = {};
            Object.values(allPlantData).flat().forEach(plant => {
                const roomName = plant.room_name || 'Unassigned';
                roomTotals[roomName] = (roomTotals[roomName] || 0) + parseInt(plant.count);
            });

            roomSummary.innerHTML = '';
            Object.keys(roomTotals).sort().forEach(room => {
                const li = document.createElement('li');
                li.textContent = `${room}: ${roomTotals[room]} plants`;
                roomSummary.appendChild(li);
            });
        }

        hideZeroCheckbox.addEventListener('change', function() {
            displayPlants('Clone', allPlantData.clone, cloneTable, document.getElementById('noCloneMessage'));
            displayPlants('Veg', allPlantData.veg, vegTable, document.getElementById('noVegMessage'));
            displayPlants('Flower', allPlantData.flower, flowerTable, document.getElementById('noFlowerMessage'));
        });

        // Load all plant data
        loadPlantsByStage('Clone', cloneTable, document.getElementById('noCloneMessage'));
        loadPlantsByStage('Veg', vegTable, document.getElementById('noVegMessage'));
        loadPlantsByStage('Flower', flowerTable, document.getElementById('noFlowerMessage'));
    </script>
</body>
</html>
