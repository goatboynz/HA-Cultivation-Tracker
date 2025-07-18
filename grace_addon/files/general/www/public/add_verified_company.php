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
    <title>GRACe - Add Verified Company</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div> 

        <div style="margin-bottom: 2rem;">
            <h1>🏢 Add Verified Company</h1>
            <p style="color: var(--text-secondary); margin: 0;">Companies added here will show up as a destination in the forms for CoC / Testing.</p>
        </div>

        <div class="modern-card">
            <h3>➕ Company Information</h3>
            <form id="addCompanyForm" class="modern-form" style="background: none; border: none; padding: 0; box-shadow: none;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label for="companyName">Company Name:</label>
                        <input type="text" id="companyName" name="companyName" required>
                    </div>
                    <div>
                        <label for="licenseNumber">License #:</label>
                        <input type="text" id="licenseNumber" name="licenseNumber" required>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label for="contactName">Primary Contact Name:</label>
                        <input type="text" id="contactName" name="contactName" required>
                    </div>
                    <div>
                        <label for="contactEmail">Primary Contact Email:</label>
                        <input type="email" id="contactEmail" name="contactEmail" required>
                    </div>
                    <div>
                        <label for="contactPhone">Primary Contact Phone:</label>
                        <input type="tel" id="contactPhone" name="contactPhone" required>
                    </div>
                </div>

                <button type="submit" class="modern-btn">💾 Add Company</button>
            </form>
        </div>
    </main>

    <script src="js/growcart.js"></script> 
    <script>
        const form = document.getElementById('addCompanyForm');
        const statusMessage = document.getElementById('statusMessage');

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(form);

            fetch('handle_add_verified_company.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) 
            .then(message => {
                if (message.startsWith('Success')) {
                    showStatusMessage(message, 'success');
                    form.reset();
                } else {
                    showStatusMessage(message, 'error');
                }
            })
            .catch(error => {
                console.error('Error adding company:', error);
                showStatusMessage('An error occurred while adding the company.', 'error');
            });
        });

        function showStatusMessage(message, type) {
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
