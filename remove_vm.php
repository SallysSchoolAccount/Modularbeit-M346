<?php
include 'logic.php';

$message = "";

// Wenn das Formular abgesendet wird, VM entfernen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vm_name = $_POST['vm_name'];
    $message = remove_vm($vm_name);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VM Entfernen</title>
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
        <form method="POST" action="remove_vm.php">
            <label for="vm_name">VM-Name:</label>
            <input type="text" id="vm_name" name="vm_name" required>
            <button type="submit" name="remove_vm">Entfernen</button>
        </form>
        <p><?php echo $message; ?></p>
    </main>
</body>
</html>
