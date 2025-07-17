<?php require_once 'auth.php'; ?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/growcart.css"> 
    <title>CultivationTracker - Reports</title> 
</head>
<body>
    <header class="container-fluid">
        <?php require_once 'nav.php'; ?>
    </header>

    <main class="container">
        <h1>Reports & Analytics</h1>
        <p><small>Generate comprehensive reports for your cultivation operation</small></p>

        <div class="grid">
            <!-- Plant Reports -->
            <div class="card">
                <h3>Plant Reports</h3>
                <div class="report-buttons">
                    <button onclick="generateReport('plants_all')" class="button">All Plants</button>
                    <button onclick="generateReport('plants_by_stage')" class="button">Plants by Stage</button>
                    <button onclick="generateReport('plants_by_room')" class="button">Plants by Room</button>
                    <button onclick="generateReport('mother_plants')" class="button">Mother Plants</button>
                    <button onclick="generateReport('clone_tracking')" class="button">Clone Tracking</button>
                </div>
            </div>

            <!-- Room Reports -->
            <div class="card">
                <h3>Room Reports</h3>
                <div class="report-buttons">
                    <button onclick="generateReport('room_utilization')" class="button">Room Utilization</button>
                    <button onclick="generateReport('room_capacity')" class="button">Room Capacity</button>
                    <button onclick="generateReport('room_timeline')" class="button">Room Timeline</button>
                </div>
            </div>

            <!-- Genetics Reports -->
            <div class="card">
                <h3>Genetics Reports</h3>
                <div class="report-buttons">
                    <button onclick="generateReport('genetics_performance')" class="button">Genetics Performance</button>
                    <button onclick="generateReport('genetics_inventory')" class="button">Genetics Inventory</button>
                    <button onclick="generateReport('harvest_summary')" class="button">Harvest Summary</button>
                </div>
            </div>

            <!-- Plant Count Reports -->
            <div class="card">
                <h3>📊 Plant Count Reports</h3>
                <p><small>Export total plant counts for different time periods</small></p>
                <div class="report-buttons">
                    <button onclick="generatePlantCountReport('monthly')" class="button">Monthly Plant Counts</button>
                    <button onclick="generatePlantCountReport('6month')" class="button">6-Month Plant Counts</button>
                    <button onclick="generatePlantCountReport('yearly')" class="button">Yearly Plant Counts</button>
                </div>
            </div>

            <!-- Timeline Reports -->
            <div class="card">
                <h3>Timeline Reports</h3>
                <div class="report-buttons">
                    <button onclick="generateReport('plant_lifecycle')" class="button">Plant Lifecycle</button>
                    <button onclick="generateReport('stage_transitions')" class="button">Stage Transitions</button>
                    <button onclick="generateReport('monthly_summary')" class="button">Monthly Summary</button>
                </div>
            </div>
        </div>

        <!-- Report Display Area -->
        <div id="reportContainer" style="display: none;">
            <div class="card">
                <div class="grid">
                    <div>
                        <h3 id="reportTitle">Report Results</h3>
                    </div>
                    <div style="text-align: right;">
                        <button id="downloadCsvBtn" class="btn btn-primary">📊 Download CSV</button>
                        <button id="downloadExcelBtn" class="btn btn-success">📈 Download Excel</button>
                        <button onclick="closeReport()" class="btn btn-secondary">Close</button>
                    </div>
                </div>
                <div class="table-container">
                    <table id="reportTable">
                        <thead id="reportTableHead">
                        </thead>
                        <tbody id="reportTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Custom Date Range -->
        <div class="card">
            <h3>Custom Date Range Reports</h3>
            <form id="dateRangeForm">
                <div class="grid">
                    <div>
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="startDate" class="input">
                    </div>
                    <div>
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="endDate" class="input">
                    </div>
                    <div>
                        <label for="reportType">Report Type:</label>
                        <select id="reportType" name="reportType" class="input">
                            <option value="plants_created">Plants Created</option>
                            <option value="plants_harvested">Plants Harvested</option>
                            <option value="stage_changes">Stage Changes</option>
                            <option value="room_moves">Room Moves</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="generateDateRangeReport()" class="button">Generate Report</button>
            </form>
        </div>
    </main>

    <script>
        let currentReportData = [];
        let currentReportType = '';

        function generateReport(reportType) {
            currentReportType = reportType;
            
            fetch(`generate_report.php?type=${reportType}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    
                    currentReportData = data.data;
                    displayReport(data.title, data.headers, data.data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error generating report');
                });
        }

        function generatePlantCountReport(period) {
            currentReportType = `plant_count_${period}`;
            
            fetch(`generate_plant_count_report.php?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    
                    currentReportData = data.data;
                    displayReport(data.title, data.headers, data.data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error generating plant count report');
                });
        }

        function generateDateRangeReport() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const reportType = document.getElementById('reportType').value;
            
            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return;
            }
            
            currentReportType = reportType + '_daterange';
            
            fetch(`generate_report.php?type=${reportType}&start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    
                    currentReportData = data.data;
                    displayReport(data.title, data.headers, data.data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error generating report');
                });
        }

        function displayReport(title, headers, data) {
            document.getElementById('reportTitle').textContent = title;
            
            // Create table headers
            const thead = document.getElementById('reportTableHead');
            thead.innerHTML = '';
            const headerRow = document.createElement('tr');
            headers.forEach(header => {
                const th = document.createElement('th');
                th.textContent = header;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            
            // Create table body
            const tbody = document.getElementById('reportTableBody');
            tbody.innerHTML = '';
            data.forEach(row => {
                const tr = document.createElement('tr');
                Object.values(row).forEach(value => {
                    const td = document.createElement('td');
                    td.textContent = value || '';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
            
            document.getElementById('reportContainer').style.display = 'block';
            document.getElementById('reportContainer').scrollIntoView({ behavior: 'smooth' });
        }

        function closeReport() {
            document.getElementById('reportContainer').style.display = 'none';
        }

        // CSV Download functionality
        document.getElementById('downloadCsvBtn').addEventListener('click', function() {
            if (currentReportData.length === 0) {
                alert('No data to download');
                return;
            }
            
            downloadCSV(currentReportData, currentReportType);
        });

        // Excel Download functionality
        document.getElementById('downloadExcelBtn').addEventListener('click', function() {
            if (currentReportData.length === 0) {
                alert('No data to download');
                return;
            }
            
            downloadExcel(currentReportData, currentReportType);
        });

        function downloadCSV(data, filename) {
            if (data.length === 0) return;
            
            // Get headers from first object
            const headers = Object.keys(data[0]);
            
            // Create CSV content
            let csvContent = headers.join(',') + '\n';
            
            data.forEach(row => {
                const values = headers.map(header => {
                    let value = row[header] || '';
                    // Escape commas and quotes
                    if (typeof value === 'string' && (value.includes(',') || value.includes('"'))) {
                        value = '"' + value.replace(/"/g, '""') + '"';
                    }
                    return value;
                });
                csvContent += values.join(',') + '\n';
            });
            
            // Create and download file
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${filename}_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        function downloadExcel(data, filename) {
            if (data.length === 0) return;
            
            // Create Excel-compatible HTML table
            const headers = Object.keys(data[0]);
            let excelContent = '<table>';
            
            // Add headers
            excelContent += '<tr>';
            headers.forEach(header => {
                excelContent += `<th>${header}</th>`;
            });
            excelContent += '</tr>';
            
            // Add data rows
            data.forEach(row => {
                excelContent += '<tr>';
                headers.forEach(header => {
                    const value = row[header] || '';
                    excelContent += `<td>${value}</td>`;
                });
                excelContent += '</tr>';
            });
            excelContent += '</table>';
            
            // Create and download file
            const blob = new Blob([excelContent], { 
                type: 'application/vnd.ms-excel;charset=utf-8;' 
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${filename}_${new Date().toISOString().split('T')[0]}.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>