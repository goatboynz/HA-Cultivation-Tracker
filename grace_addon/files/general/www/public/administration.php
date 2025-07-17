<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">   

    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">   

    <link rel="stylesheet" href="css/growcart.css"> 
    <title>GRACe - Administration</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>Administration</h1>

        <section>
            <h2>Contact Management</h2>
            <ul>
                <li><a href="add_verified_company.php">Add Verified Company</a><br />
		Add a company you'll send flower / plants to, such as an offtake buyer or testing lab.</li>
            </ul>
        </section>

        <section>
            <h2>Genetics Management</h2>
            <ul>
                <li><a href="add_new_genetics.php">Add New Genetics</a><br />
		Any genetics you'll have as either plants/flower</li>
            </ul>
        </section>

        <section>
            <h2>Room Management</h2>
            <ul>
                <li><a href="manage_rooms.php">Manage Rooms</a><br />
		Add and manage grow rooms for different plant stages</li>
            </ul>
        </section>

        <section>
            <h2>Record management</h2>
            <ul>
                <li><a href="police_vet_check_records.php">Police Vet Check Records</a></li>
            </ul>
            <ul>
                <li><a href="sops.php">Manage SOPs</a></li>
            </ul>
            <ul>
                <li><a href="offtake_agreements.php">Offtake Agreements</a></li>
            </ul>
            <ul>
                <li><a href="company_licenses.php">Company licenses </a></li>
            </ul>
            <ul>
                <li><a href="chain_of_custody_documents.php">Chain of Custody Documents </a></li>
            </ul>
        </section>

        <section>
            <h2>System Management</h2>
            <ul>
                <li><a href="own_company.php">Update company information</a><br />
		Enter your own company information, so we can populate CoC docs etc</li>
            </ul>
	    <ul>
		<li><a href="show_database.php">Dump database</a></li>
	    </ul>
        </section>

        <section>
            <h2>Testing & Development</h2>
            <div style="background: #2d3748; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <p><strong>⚠️ Warning:</strong> These tools are for testing purposes only. Use with caution!</p>
            </div>
            <ul>
                <li>
                    <button onclick="generateDummyData()" class="secondary">Generate Dummy Data</button><br />
                    <small>Creates sample genetics, rooms, plants, and transactions for testing</small>
                </li>
            </ul>
            <ul>
                <li>
                    <button onclick="cleanupDummyData()" class="contrast outline">Delete All Dummy Data</button><br />
                    <small>Removes all dummy data created for testing (irreversible!)</small>
                </li>
            </ul>
        </section>

        <script>
        function generateDummyData() {
            if (confirm('This will create dummy data for testing. Continue?')) {
                fetch('generate_dummy_data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Dummy data generated successfully!\n\n' + data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Error generating dummy data: ' + error);
                });
            }
        }

        function cleanupDummyData() {
            if (confirm('⚠️ WARNING: This will permanently delete ALL dummy data!\n\nThis action cannot be undone. Are you sure?')) {
                if (confirm('Last chance! This will delete all test data. Continue?')) {
                    fetch('cleanup_dummy_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Dummy data cleaned up successfully!\n\n' + data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        alert('Error cleaning up dummy data: ' + error);
                    });
                }
            }
        }
        </script>
    </main>

    <script src="js/growcart.js"></script>
</body>
</html>
