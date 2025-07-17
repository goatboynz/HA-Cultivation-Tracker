<?php require_once 'auth.php'; ?>
<?php require_once 'init_db.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css">
    <title>GRACe - Manage Companies</title>
    <style>
        .company-card {
            background: var(--pico-card-background-color);
            border: 1px solid var(--pico-card-border-color);
            border-radius: var(--pico-border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .company-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .edit-form {
            display: none;
            background: var(--pico-background-color);
            padding: 1rem;
            border-radius: var(--pico-border-radius);
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>Manage Companies</h1>
        
        <?php
        try {
            $pdo = initializeDatabase();
            $stmt = $pdo->query("SELECT * FROM Companies ORDER BY name");
            $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($companies)) {
                echo '<p>No companies found. <a href="add_verified_company.php">Add your first company</a>.</p>';
            } else {
                foreach ($companies as $company) {
                    echo '<div class="company-card">';
                    echo '<div class="company-display" id="display-' . $company['id'] . '">';
                    echo '<h3>' . htmlspecialchars($company['name']) . '</h3>';
                    echo '<p><strong>License:</strong> ' . htmlspecialchars($company['license_number']) . '</p>';
                    if ($company['address']) {
                        echo '<p><strong>Address:</strong> ' . htmlspecialchars($company['address']) . '</p>';
                    }
                    if ($company['primary_contact_name']) {
                        echo '<p><strong>Contact:</strong> ' . htmlspecialchars($company['primary_contact_name']) . '</p>';
                    }
                    if ($company['primary_contact_email']) {
                        echo '<p><strong>Email:</strong> ' . htmlspecialchars($company['primary_contact_email']) . '</p>';
                    }
                    if ($company['primary_contact_phone']) {
                        echo '<p><strong>Phone:</strong> ' . htmlspecialchars($company['primary_contact_phone']) . '</p>';
                    }
                    
                    echo '<div class="company-actions">';
                    echo '<button onclick="editCompany(' . $company['id'] . ')" class="secondary">Edit</button>';
                    echo '<button onclick="deleteCompany(' . $company['id'] . ', \'' . htmlspecialchars($company['name']) . '\')" class="contrast outline">Delete</button>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Edit form (hidden by default)
                    echo '<div class="edit-form" id="edit-' . $company['id'] . '">';
                    echo '<h4>Edit Company</h4>';
                    echo '<form onsubmit="updateCompany(event, ' . $company['id'] . ')">';
                    echo '<label>Company Name: <input type="text" name="name" value="' . htmlspecialchars($company['name']) . '" required></label>';
                    echo '<label>License Number: <input type="text" name="license_number" value="' . htmlspecialchars($company['license_number']) . '" required></label>';
                    echo '<label>Address: <textarea name="address">' . htmlspecialchars($company['address']) . '</textarea></label>';
                    echo '<label>Contact Name: <input type="text" name="primary_contact_name" value="' . htmlspecialchars($company['primary_contact_name']) . '"></label>';
                    echo '<label>Contact Email: <input type="email" name="primary_contact_email" value="' . htmlspecialchars($company['primary_contact_email']) . '"></label>';
                    echo '<label>Contact Phone: <input type="tel" name="primary_contact_phone" value="' . htmlspecialchars($company['primary_contact_phone']) . '"></label>';
                    echo '<div style="display: flex; gap: 0.5rem; margin-top: 1rem;">';
                    echo '<button type="submit" class="primary">Save Changes</button>';
                    echo '<button type="button" onclick="cancelEdit(' . $company['id'] . ')" class="secondary">Cancel</button>';
                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                    
                    echo '</div>';
                }
            }
        } catch (Exception $e) {
            echo '<p>Error loading companies: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
        
        <div style="margin-top: 2rem;">
            <a href="add_verified_company.php" class="primary">Add New Company</a>
        </div>
    </main>

    <script>
        function editCompany(id) {
            document.getElementById('display-' + id).style.display = 'none';
            document.getElementById('edit-' + id).style.display = 'block';
        }
        
        function cancelEdit(id) {
            document.getElementById('display-' + id).style.display = 'block';
            document.getElementById('edit-' + id).style.display = 'none';
        }
        
        function updateCompany(event, id) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            fetch('update_company.php', {
                method: 'POST',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    license_number: formData.get('license_number'),
                    address: formData.get('address'),
                    primary_contact_name: formData.get('primary_contact_name'),
                    primary_contact_email: formData.get('primary_contact_email'),
                    primary_contact_phone: formData.get('primary_contact_phone')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Company updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                alert('Error updating company: ' + error);
            });
        }
        
        function deleteCompany(id, name) {
            if (confirm('Are you sure you want to delete "' + name + '"? This action cannot be undone.')) {
                fetch('delete_company.php', {
                    method: 'POST',
                    body: JSON.stringify({ id: id }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Company deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Error deleting company: ' + error);
                });
            }
        }
    </script>
    <script src="js/growcart.js"></script>
</body>
</html>