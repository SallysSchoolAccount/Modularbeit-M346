<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'add_vm':
            header('Location: add_vm.php');
            break;
        case 'remove_vm':
            header('Location: remove_vm.php');
            break;
        case 'contact':
            header('Location: contact.php');
            break;
        case 'server_storage':
            header('Location: server_storage.php');
            break;
        default:
            header('Location: index.php');
            break;
    }
} else {
    header('Location: index.php');
}
exit;
