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
    <title>CultivationTracker - Record Flower Transaction</title> 
</head>
<body>
    <header class="container-fluid">
	<?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <div id="statusMessage" class="status-message" style="display: none;"></div> 

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>⚖️ Record Flower Transaction</h1>
                <p style="color: var(--text-secondary); margin: 0;">Track flower weight changes for harvest, testing, destruction, or external transfers</p>
            </div>
        </div>

        <div class="modern-card">
            <h3>📝 Transaction Details</h3>
            <form id="recordFlowerTransactionForm" action="record_flower_transaction.php" method="post" style="margin-top: 1rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label for="geneticsName">Genetics:</label>
                        <select id="geneticsName" name="geneticsName" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" required>
                            <option value="" disabled selected>Select Genetics</option>
                        </select>
                    </div>

                    <div>
                        <label for="weight">Weight (grams):</label>
                        <input type="number" id="weight" name="weight" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" min="0.01" step="0.01" required>
                    </div>

                    <div>
                        <label for="transactionType">Transaction Type:</label>
                        <select id="transactionType" name="transactionType" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" required>
                            <option value="Add">➕ Add</option>
                            <option value="Subtract">➖ Subtract</option>
                        </select>
                    </div>

                    <div>
                        <label for="reason">Reason:</label>
                        <select id="reason" name="reason" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" required>
                            <option value="" disabled selected>Select Reason</option>
                        </select>
                    </div>
                </div>

                <div id="companySelection" style="display: none; margin-bottom: 1rem;">
                    <label for="companyId">Company:</label>
                    <select id="companyId" name="companyId" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                        <option value="" disabled selected>Select Company</option>
                    </select>
                </div>

                <div id="otherReasonSection" style="display: none; margin-bottom: 1rem;">
                    <label for="otherReason">Other Reason:</label>
                    <textarea id="otherReason" name="otherReason" style="width: 100%; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);" rows="3"></textarea>
                </div>

                <button type="submit" class="modern-btn">💾 Record Transaction</button>
            </form>
        </div>
    </main>

    <script src="js/growcart.js"></script> 
    <script>
        const form = document.getElementById('recordFlowerTransactionForm');
        const statusMessage = document.getElementById('statusMessage');
        const geneticsDropdown = document.getElementById('geneticsName');
        const transactionTypeDropdown = document.getElementById('transactionType');
        const reasonDropdown = document.getElementById('reason');
        const companySelection = document.getElementById('companySelection');
        const companyDropdown = document.getElementById('companyId');
        const otherReasonSection = document.getElementById('otherReasonSection');

        // Check if there's a success or error message in the URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage) {
            showStatusMessage(successMessage, 'success');
            form.reset(); 
        } else if (errorMessage) {
            showStatusMessage(errorMessage, 'error');
        }

        // Populate form with submitted data if there was an error
        const submittedData = JSON.parse(urlParams.get('data') || '{}');
        form.geneticsName.value = submittedData.geneticsName || '';
        form.weight.value = submittedData.weight || '';
        form.transactionType.value = submittedData.transactionType || '';
        form.reason.value = submittedData.reason || '';
        if (submittedData.reason === 'Other') {
            otherReasonSection.style.display = 'block';
            form.otherReason.value = submittedData.otherReason || '';
        }
        if (submittedData.transactionType === 'Subtract' && 
            (submittedData.reason === 'Testing' || submittedData.reason === 'Send external')) {
            companySelection.style.display = 'block';
            form.companyId.value = submittedData.companyId || '';
        }

        function showStatusMessage(message, type) {
            statusMessage.textContent = message;
            statusMessage.classList.add(type);
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
                statusMessage.classList.remove(type);
            }, 5000); 
        }

        transactionTypeDropdown.addEventListener('change', updateReasonOptions);
        reasonDropdown.addEventListener('change', updateCompanyVisibility);

        function updateReasonOptions() {
            reasonDropdown.innerHTML = '<option value="" disabled selected>Select Reason</option>';
            if (transactionTypeDropdown.value === 'Subtract') {
                reasonDropdown.innerHTML += `
                    <option value="Testing">Testing</option>
                    <option value="Destroy">Destroy</option>
                    <option value="Send external">Send External</option>
                    <option value="Other">Other</option>
                `;
            } else {
                reasonDropdown.innerHTML += `
                    <option value="Harvest">Harvest</option>
                    <option value="Other">Other</option>
                `;
            }
            updateCompanyVisibility();
        }

        function updateCompanyVisibility() {
            if (transactionTypeDropdown.value === 'Subtract' && 
                (reasonDropdown.value === 'Testing' || reasonDropdown.value === 'Send external')) {
                companySelection.style.display = 'block';
                companyDropdown.required = true;
            } else {
                companySelection.style.display = 'none';
                companyDropdown.required = false;
            }
            otherReasonSection.style.display = reasonDropdown.value === 'Other' ? 'block' : 'none';
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (transactionTypeDropdown.value === 'Subtract' &&
                (reasonDropdown.value === 'Testing' || reasonDropdown.value === 'Send external') &&
                !companyDropdown.value) {
                alert('Please select a company for Testing or Send external transactions.');
                return;
            }
            this.submit();
        });

        // Initial setup
        updateReasonOptions();

        // Fetch and populate genetics dropdown
        fetch('get_genetics.php')
            .then(response => response.json())
            .then(genetics => {
                genetics.forEach(genetic => {
                    const option = document.createElement('option');
                    option.value = genetic.id;
                    option.textContent = genetic.name;
                    geneticsDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching genetics:', error));

        // Fetch and populate company dropdown
        fetch('get_companies.php')
            .then(response => response.json())
            .then(companies => {
                companies.forEach(company => {
                    const option = document.createElement('option');
                    option.value = company.id;
                    option.textContent = company.name;
                    companyDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching companies:', error));
    </script>
</body>
</html>
