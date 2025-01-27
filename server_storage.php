<?php
session_start();
include 'logic.php'; // Importiere die Logik

// Zeige den aktuellen Speicherstand
if (!isset($_SESSION['servers'])) {
    echo "<p>Keine Serverdaten verfügbar.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Speicherstand</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles for grid layout */
        .server-status {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Increased min width to 300px */
            gap: 1rem;
            padding: 1rem;
            align: center;

        }
        .server-card {
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: black; /* Text color for better contrast */
            background-color: #ffffff;
        }
        .indicator-container {
            width: 100%;
            background-color: #e0e0e0; /* Light gray background for empty portion */
            border-radius: 5px;
            overflow: hidden; /* To ensure the inner bar doesn't overflow */
            height: 20px; /* Height of the indicator */
            margin: 0.5rem 0;
        }
        .indicator {
            height: 100%; /* Fill the height of the container */
            transition: width 0.3s ease; /* Smooth transition */
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <h1><a href="index.php" style="text-decoration: none; color: inherit;">Willkommen bei OmniCloud</a></h1>
        <nav>
            <ul>
                <li><a href="add_vm.php">VM Hinzufügen</a></li>
                <li><a href="remove_vm.php">VM Entfernen</a></li>
                <li><a href="contact.php">Kontakte</a></li>
                <li><a href="server_storage.php">Server Speicherstand</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section>
        <h2>Speicherstand der Server</h2>
        <div class="server-status">
            <?php
            // Anzeige des Speicherstandes der Server in eine Waffeln layout
            $servers = $_SESSION['servers'];
            foreach ($servers as $name => $server) {
                $free_cpu = $server['cpu'] - $server['used_cpu'];
                $free_ram = $server['ram'] - $server['used_ram'];
                $free_ssd = $server['ssd'] - $server['used_ssd'];

                // Prozent der Resourcen berechnen
                $cpu_percentage = ($server['used_cpu'] / $server['cpu']) * 100;
                $ram_percentage = ($server['used_ram'] / $server['ram']) * 100;
                $ssd_percentage = ($server['used_ssd'] / $server['ssd']) * 100;

                // COLORS
                $cpu_color = $cpu_percentage >= 100 ? 'red' : ($cpu_percentage >= 50 ? 'orange' : 'green');
                $ram_color = $ram_percentage >= 100 ? 'red' : ($ram_percentage >= 50 ? 'orange' : 'green');
                $ssd_color = $ssd_percentage >= 100 ? 'red' : ($ssd_percentage >= 50 ? 'orange' : 'green');

                // Calculate total price of VMs
                $total_price = calculate_total_vm_price($server['vms']); // Assuming this function exists in logic.php

                echo "<div class='server-card'>
                            <h3>Server: $name</h3>
                            <p>Verfügbare CPU: $free_cpu / Maximal: {$server['cpu']}</p>
                            <div class='indicator-container'>
                                <div class='indicator' style='width: $cpu_percentage%; background-color: $cpu_color;'></div>
                            </div>
                            <p>Verfügbarer RAM: $free_ram MB / Maximal: {$server['ram']} MB</p>
 <div class='indicator-container'>
                                <div class='indicator' style='width: $ram_percentage%; background-color: $ram_color;'></div>
                            </div>
                            <p>Verfügbarer SSD: $free_ssd GB / Maximal: {$server['ssd']} GB</p>
                            <div class='indicator-container'>
                                <div class='indicator' style='width: $ssd_percentage%; background-color: $ssd_color;'></div>
                            </div>
                            <p>Anzahl VMs: " . count($server['vms']) . "</p>
                            <p>Gesamtpreis der VMs: CHF " . number_format($total_price, 2) . "</p> <!-- Display total price -->
                          </div>";
            }
            ?>
        </div>
    </section>
</main>
</body>
</html>