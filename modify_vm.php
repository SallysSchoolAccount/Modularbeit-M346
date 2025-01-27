<?php
include 'logic.php';

$message = "";

// Wenn das Formular abgesendet wird, VM-Ressourcen ändern
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vm_name = $_POST['vm_name'];
    $new_cpu = $_POST['cpu'];
    $new_ram = $_POST['ram'];
    $new_ssd = $_POST['ssd'];
    $message = modify_vm($vm_name, $new_cpu, $new_ram, $new_ssd);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VM Ändern</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-container">
        <h1><a href="index.php" style="text-decoration: none; color: inherit;">VM ändern</a></h1>
        <nav>
            <ul>
                <li><a href="add_vm.php">VM Hinzufügen</a></li>
                <li><a href="remove_vm.php">VM Entfernen</a></li>
                <li><a href="contact.php">Kontakte</a></li>
                <li><a href="server_storage.php">Server Speicherstand</a>
                <li><a href="modify_vm.php">VM ändern</a>
            </ul>
        </nav>
    </div>
</header>

<main>
    <form method="POST" action="modify_vm.php">
        <label for="vm_name">VM-Name:</label>
        <input type="text" id="vm_name" name="vm_name" required>

        <label for="cpu">Neue Prozessoren (CPU):</label>
        <select name="cpu" id="cpu">
            <option value="1">1 Core</option>
            <option value="2">2 Cores</option>
            <option value="4">4 Cores</option>
            <option value="8">8 Cores</option>
            <option value="16">16 Cores</option>
        </select>

        <label for="ram">Neuer Arbeitsspeicher (RAM):</label>
        <select name="ram" id="ram">
            <option value="512">512 MB</option>
            <option value="1024">1024 MB</option>
            <option value="2048">2048 MB</option>
            <option value="4096">4096 MB</option>
            <option value="8192">8192 MB</option>
            <option value="16384">16384 MB</option>
            <option value="32768">32768 MB</option>
        </select>

        <label for="ssd">Neuer Speicherplatz (SSD):</label>
        <select name="ssd" id="ssd">
            <option value="10">10 GB</option>
            <option value="20">20 GB</option>
            <option value="40">40 GB</option>
            <option value="80">80 GB</option>
            <option value="240">240 GB</option>
            <option value="500">500 GB</option>
            <option value="1000">1000 GB</option>
        </select>

        <button type="submit" name="modify_vm">Ändern</button>
    </form>

    <!-- Message Box -->
    <?php if ($message): ?>
        <div class="message-box">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>