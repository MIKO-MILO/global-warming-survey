<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "survey_global_warming";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data untuk setiap pertanyaan
$queries = [
    "age_group" => "SELECT age_group, COUNT(*) AS count FROM survey_results GROUP BY age_group",
    "knowledge_level" => "SELECT knowledge_level, COUNT(*) AS count FROM survey_results GROUP BY knowledge_level",
    "action_willingness" => "SELECT action_willingness, COUNT(*) AS count FROM survey_results GROUP BY action_willingness",
    "renewable_support" => "SELECT renewable_support, COUNT(*) AS count FROM survey_results GROUP BY renewable_support",
    "climate_change_concern" => "SELECT climate_change_concern, COUNT(*) AS count FROM survey_results GROUP BY climate_change_concern"
];

$results = [];
foreach ($queries as $key => $query) {
    $result = $conn->query($query);
    $results[$key] = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $results[$key][] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Survei Pemanasan Global</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f8ff;
            color: #2c3e50;
        }

        .chart-container {
            width: 20%;
            margin: 20px auto;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 30%;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        td {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Hasil Survei Pemanasan Global</h1>

    <!-- Grafik 1: Kelompok Usia -->
    <h2>Kelompok Usia</h2>
    <div class="chart-container">
        <canvas id="ageGroupChart"width="400" height="400" ></canvas>
    </div>
    <table>
        <tr>
            <th>Kelompok Usia</th>
            <th>Jumlah Responden</th>
        </tr>
        <?php foreach($results['age_group'] as $row) { ?>
        <tr>
            <td><?php echo $row['age_group']; ?></td>
            <td><?php echo $row['count']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Grafik 2: Pengetahuan tentang Pemanasan Global -->
    <h2>Pengetahuan tentang Pemanasan Global</h2>
    <div class="chart-container">
        <canvas id="knowledgeChart"></canvas>
    </div>
    <table>
        <tr>
            <th>Pengetahuan</th>
            <th>Jumlah Responden</th>
        </tr>
        <?php foreach($results['knowledge_level'] as $row) { ?>
        <tr>
            <td><?php echo $row['knowledge_level']; ?></td>
            <td><?php echo $row['count']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Grafik 3: Kemauan Bertindak -->
    <h2>Kemauan Bertindak Melawan Pemanasan Global</h2>
    <div class="chart-container">
        <canvas id="actionWillingnessChart"></canvas>
    </div>
    <table>
        <tr>
            <th>Kemauan Bertindak</th>
            <th>Jumlah Responden</th>
        </tr>
        <?php foreach($results['action_willingness'] as $row) { ?>
        <tr>
            <td><?php echo $row['action_willingness']; ?></td>
            <td><?php echo $row['count']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Grafik 4: Dukungan Energi Terbarukan -->
    <h2>Dukungan Terhadap Energi Terbarukan</h2>
    <div class="chart-container">
        <canvas id="renewableSupportChart"></canvas>
    </div>
    <table>
        <tr>
            <th>Dukungan</th>
            <th>Jumlah Responden</th>
        </tr>
        <?php foreach($results['renewable_support'] as $row) { ?>
        <tr>
            <td><?php echo $row['renewable_support']; ?></td>
            <td><?php echo $row['count']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Grafik 5: Kepedulian Terhadap Perubahan Iklim -->
    <h2>Kepedulian Terhadap Perubahan Iklim</h2>
    <div class="chart-container">
        <canvas id="climateConcernChart"></canvas>
    </div>
    <table>
        <tr>
            <th>Kepedulian</th>
            <th>Jumlah Responden</th>
        </tr>
        <?php foreach($results['climate_change_concern'] as $row) { ?>
        <tr>
            <td><?php echo $row['climate_change_concern']; ?></td>
            <td><?php echo $row['count']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <script>
        // Data untuk setiap grafik
        var ageGroupData = <?php echo json_encode(array_column($results['age_group'], 'count')); ?>;
        var ageGroupLabels = <?php echo json_encode(array_column($results['age_group'], 'age_group')); ?>;
        
        var knowledgeData = <?php echo json_encode(array_column($results['knowledge_level'], 'count')); ?>;
        var knowledgeLabels = <?php echo json_encode(array_column($results['knowledge_level'], 'knowledge_level')); ?>;
        
        var actionWillingnessData = <?php echo json_encode(array_column($results['action_willingness'], 'count')); ?>;
        var actionWillingnessLabels = <?php echo json_encode(array_column($results['action_willingness'], 'action_willingness')); ?>;
        
        var renewableSupportData = <?php echo json_encode(array_column($results['renewable_support'], 'count')); ?>;
        var renewableSupportLabels = <?php echo json_encode(array_column($results['renewable_support'], 'renewable_support')); ?>;
        
        var climateConcernData = <?php echo json_encode(array_column($results['climate_change_concern'], 'count')); ?>;
        var climateConcernLabels = <?php echo json_encode(array_column($results['climate_change_concern'], 'climate_change_concern')); ?>;

        // Fungsi untuk membuat setiap grafik
        function createPieChart(canvasId, labels, data) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        // Membuat grafik
        createPieChart('ageGroupChart', ageGroupLabels, ageGroupData);
        createPieChart('knowledgeChart', knowledgeLabels, knowledgeData);
        createPieChart('actionWillingnessChart', actionWillingnessLabels, actionWillingnessData);
        createPieChart('renewableSupportChart', renewableSupportLabels, renewableSupportData);
        createPieChart('climateConcernChart', climateConcernLabels, climateConcernData);
    </script>
</body>
</html>
