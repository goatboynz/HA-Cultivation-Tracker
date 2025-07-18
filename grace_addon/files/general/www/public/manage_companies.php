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
    <link rel="stylesheet" href="css/modern-theme.css">
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
        <div style="margin-bottom: 2rem;">
            <h1>🏢 Manage Companies</h1>
            <p style="color: var(--text-secondary);">View and edit your verified companies</p>
        </div>
        
        <?php
        try {
            $pdo = initializeDatabase();
            $stmt = $pdo->query("SELECT * FROM Companies ORDER BY name");
            $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($companies)) {
                echo '<div class="modern-card" style="text-align: center; padding: 3rem;">';
                echo '<h3>No Companies Found</h3>';
                echo '<p style="color: var(--text-secondary); margin-bottom: 2rem;">Get started by adding your first verified company</p>';
                echo '<a href="add_verified_company.php" class="modern-btn">Add Your First Company</a>';
                echo '</div>';
            } else {
                echo '<div class="dashboard-grid">';
                foreach ($companies as $company) {
                    echo '<div class="modern-card">';
                    echo '<div class="company-display" id="display-' . $company['id'] . '">';
                    echo '<h3>🏢 ' . htmlspecialchars($company['name']) . '</h3>';
                    echo '<div style="margin: 1rem 0;">';
                    echo '<p style="margin: 0.5rem 0;"><strong>License:</strong> <span style="color: var(--accent-primary);">' . htmlspecialchars($company['license_number']) . '</span></p>';
                    if ($company['address']) {
                        echo '<p style="margin: 0.5rem 0;"><strong>Address:</strong> ' . htmlspecialchars($company['address']) . '</p>';
                    }
                    if ($company['primary_contact_name']) {
                        echo '<p style="margin: 0.5rem 0;"><strong>Contact:</strong> ' . htmlspecialchars($company['primary_contact_name']) . '</p>';
                    }
                    if ($company['primary_contact_email']) {
                        echo '<p style="margin: 0.5rem 0;"><strong>Email:</strong> <a href="mailto:' . htmlspecialchars($company['primary_contact_email']) . '" style="color: var(--accent-primary);">' . htmlspecialchars($company['primary_contact_email']) . '</a></p>';
                    }
                    if ($company['primary_contact_phone']) {
                        echo '<p style="margin: 0.5rem 0;"><strong>Phone:</strong> <a href="tel:' . htmlspecialchars($company['primary_contact_phone']) . '" style="color: var(--accent-primary);">' . htmlspecialchars($company['primary_contact_phone']) . '</a></p>';
                    }
                    echo '</div>';
                    
                    echo '<div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">';
                    echo '<button onclick="editCompany(' . $company['id'] . ')" class="modern-btn secondary">✏️ Edit</button>';
                    echo '<button onclick="deleteCompany(' . $company['id'] . ', \'' . htmlspecialchars($company['name']) . '\')" class="modern-btn secondary" style="color: var(--accent-error); border-color: var(--accent-error);">🗑️ Delete</button>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Edit form (hidden by default)
                    echo '<div class="edit-form modern-form" id="edit-' . $company['id'] . '" style="display: none; margin-top: 1rem;">';
                    echo '<h4>✏️ Edit Company</h4>';
                    echo '<form onsubmit="updateCompany(event, ' . $company['id'] . ')">';
                    echo '<div style="margin-bottom: 1rem;">';
                    echo '<label>Company Name:</label>';
                    echo '<input type="text" name="name" value="' . htmlspecialchars($company['name']) . '" required>';
                    echo '</div>';
                    echo '<div style="margin-bottom: 1rem;">';
                    echo '<label>License Number:</label>';
                    echo '<input type="text" name="license_number" value="' . htmlspecialchars($company['license_number']) . '" required>';
                    echo '</div>';
                    echo '<div style="margin-bottom: 1rem;">';
                    echo '<label>Address:</label>';
                    echo '<textarea name="address" rows="3">' . htmlspecialchars($company['address']) . '</textarea>';
                    echo '</div>';
                    echo '<div style="margin-bottom: 1rem;">';
                    echo '<label>Contact Name:</label>';
                    echo '<input type="text" name="primary_contact_name" value="' . htmlspecialchars($company['primary_contact_name']) . '">';
                    echo '</div>';
                    echo '<div style="margin-bottom: 1rem;">';
                    echo '<label>Contact Email:</label>';
                    echo '<input type="email" name="primary_contact_email" value="' . htmlspecialchars($company['primary_contact_email']) . '">';
                    echo '</div>';
                    echo '<div style="margin-bottom: 1rem;">';
                    echo '<label>Contact Phone:</label>';
                    echo '<input type="tel" name="primary_contact_phone" value="' . htmlspecialchars($company['primary_contact_phone']) . '">';
                    echo '</div>';
                    echo '<div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">';
                    echo '<button type="submit" class="modern-btn">💾 Save Changes</button>';
                    echo '<button type="button" onclick="cancelEdit(' . $company['id'] . ')" class="modern-btn secondary">❌ Cancel</button>';
                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                    
                    echo '</div>';
                }
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="modern-card" style="border-color: var(--accent-error); background: rgba(239, 68, 68, 0.1);">';
            echo '<h3 style="color: var(--accent-error);">⚠️ Error Loading Companies</h3>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 2rem; text-align: center;">
            <a href="add_verified_company.php" class="modern-btn">➕ Add New Company</a>
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