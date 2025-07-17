<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">   

    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">   

    <link rel="stylesheet" href="css/growcart.css"> 
    <title>GRACe - Plant Tracking</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>Plant Tracking</h1>

        <section>
            <h2>Plant / Product Management</h2>
            <ul>
                <li><a href="list_all_genetics.php">List all plants</a><br />
		View current and historical plants, live / harvested / destroyed</li>
                <li><a href="receive_genetics.php">Receive plants or take clones</a><br />
		Newly added plants, such as from a license holder, Form D declaration, or clones taken from a mother plant.</li>
                <li><a href="harvest_plants.php">Harvest/Destroy/Send plants</a></li>
                <li><a href="record_dry_weight.php">Record dry weight change</a><br />
		All dry weight flower changes, such as sending for testing, destruction, or harvesting into inventory</li>
            </ul>
        </section>

        <section>
            <h2>Plant Stage Management</h2>
            <ul>
                <li><a href="plants_clone.php">Clone Stage Plants</a><br />
		Manage plants in clone/cutting stage and move to vegetative</li>
                <li><a href="plants_veg.php">Vegetative Stage Plants</a><br />
		Manage plants in vegetative stage and move to flower or back to clone (mothers)</li>
                <li><a href="plants_flower.php">Flowering Stage Plants</a><br />
		Manage flowering plants, harvest, destroy, or move between flower rooms</li>
                <li><a href="current_plants.php">Current Plants Overview</a><br />
		View all current plants by stage and room</li>
            </ul>
        </section>

        <section>
            <h2>Shipping</h2>
            <ul>
                <li><a href="generate_shipping_manifest.php">Generate Shipping Manifest</a></li>
                <li><a href="amend_complete_manifest.php">Amend / Complete Manifest</a> - Coming soon</li>
            </ul>
        </section>

        <section>
            <h2>Recalls</h2>
            <ul>
                <li><a href="initiate_recall.php">Initiate Recall</a> - Coming soon</li>
            </ul>
        </section>

    </main>

    <script src="js/growcart.js"></script> 
</body>
</html>
