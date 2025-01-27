    <?php
    include 'logic.php';

    // Standardwerte für CPU, RAM, SSD
    $cpu = 1;
    $ram = 512;
    $ssd = 10;
    $vm_name = "";
    $total_preis = "";

    // Wenn das Formular abgesendet wird, VM bestellen
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cpu = $_POST['cpu'];
        $ram = $_POST['ram'];
        $ssd = $_POST['ssd'];
        $vm_name = $_POST['vm_name'];

        // VM Bestellung und Zuweisung auf Server
        $total_preis = order_vm($cpu, $ram, $ssd, $vm_name);
    }
    ?>

    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OmniCloud - VM Hinzufügen</title>
        <link rel="stylesheet" href="style.css">
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
                    <li><a href="server_storage.php">Server Speicherstand</a>
                </ul>
            </nav>
        </div>
    </header>


    <main>
            <form method="POST" action="add_vm.php">
                <label for="vm_name">VM Name:</label>
                <input type="text" name="vm_name" id="vm_name" value="<?php echo htmlspecialchars($vm_name); ?>" required>

                <label for="cpu">Prozessoren (CPU):</label>
                <select name="cpu" id="cpu">
                    <option value="1" <?php if ($cpu == 1) echo 'selected'; ?>>1 Core (5 CHF)</option>
                    <option value="2" <?php if ($cpu == 2) echo 'selected'; ?>>2 Cores (10 CHF)</option>
                    <option value="4" <?php if ($cpu == 4) echo 'selected'; ?>>4 Cores (18 CHF)</option>
                    <option value="8" <?php if ($cpu == 8) echo 'selected'; ?>>8 Cores (30 CHF)</option>
                    <option value="16" <?php if ($cpu == 16) echo 'selected'; ?>>16 Cores (45 CHF)</option>
                </select>

                <label for="ram">Arbeitsspeicher (RAM):</label>
                <select name="ram" id="ram">
                    <option value="512" <?php if ($ram == 512) echo 'selected'; ?>>512 MB (5 CHF)</option>
                    <option value="1024" <?php if ($ram == 1024) echo 'selected'; ?>>1024 MB (10 CHF)</option>
                    <option value="2048" <?php if ($ram == 2048) echo 'selected'; ?>>2048 MB (20 CHF)</option>
                    <option value="4096" <?php if ($ram == 4096) echo 'selected'; ?>>4096 MB (40 CHF)</option>
                    <option value="8192" <?php if ($ram == 8192) echo 'selected'; ?>>8192 MB (80 CHF)</option>
                    <option value="16384" <?php if ($ram == 16384) echo 'selected'; ?>>16384 MB (160 CHF)</option>
                    <option value="32768" <?php if ($ram == 32768) echo 'selected'; ?>>32768 MB (320 CHF)</option>
                </select>

                <label for="ssd">Speicherplatz (SSD):</label>
                <select name="ssd" id="ssd">
                    <option value="10" <?php if ($ssd == 10) echo 'selected'; ?>>10 GB (5 CHF)</option>
                    <option value="20" <?php if ($ssd == 20) echo 'selected'; ?>>20 GB (10 CHF)</option>
                    <option value="40" <?php if ($ssd == 40) echo 'selected'; ?>>40 GB (20 CHF)</option>
                    <option value="80" <?php if ($ssd == 80) echo 'selected'; ?>>80 GB (40 CHF)</option>
                    <option value="240" <?php if ($ssd == 240) echo 'selected'; ?>>240 GB (120 CHF)</option>
                    <option value="500" <?php if ($ssd == 500) echo 'selected'; ?>>500 GB (250 CHF)</option>
                    <option value="1000" <?php if ($ssd == 1000) echo 'selected'; ?>>1000 GB (500 CHF)</option>
                </select>

                <!-- Preisberechnung und Bestellbestätigung -->
                <div id="priceDisplay">
                    <p><?php echo $total_preis; ?></p>
                </div>

                <!-- Bestellen-Button -->
                <button type="submit" name="order_vm">Bestellen</button>
            </form>
        </main>
    </body>
    </html>
