<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

// Server starten falls es schon nicht gestartet ist
if (!isset($_SESSION['servers'])) {
    $_SESSION['servers'] = [
        'Small' => ['cpu' => 4, 'ram' => 32768, 'ssd' => 4000, 'used_cpu' => 0, 'used_ram' => 0, 'used_ssd' => 0, 'vms' => []],
        'Medium' => ['cpu' => 8, 'ram' => 65536, 'ssd' => 8000, 'used_cpu' => 0, 'used_ram' => 0, 'used_ssd' => 0, 'vms' => []],
        'Big' => ['cpu' => 16, 'ram' => 131072, 'ssd' => 16000, 'used_cpu' => 0, 'used_ram' => 0, 'used_ssd' => 0, 'vms' => []],
    ];
}

// Preis berechnung (einzeln)
function calculate_cost($value, $pricing) {
    return isset($pricing[$value]) ? $pricing[$value] : 0;
}

// Preis berechnung (Total)
function calculate_total_vm_price($vms) {
    $total_price = 0;
    foreach ($vms as $vm) {
        $cpu_price = calculate_cost($vm['cpu'], [1 => 5, 2 => 10, 4 => 18, 8 => 30, 16 => 45]);
        $ram_price = calculate_cost($vm['ram'], [512 => 5, 1024 => 10, 2048 => 20, 4096 => 40, 8192 => 80, 16384 => 160, 32768 => 320]);
        $ssd_price = calculate_cost($vm['ssd'], [10 => 5, 20 => 10, 40 => 20, 80 => 40, 240 => 120, 500 => 250, 1000 => 500]);
        $total_price += $cpu_price + $ram_price + $ssd_price;
    }
    return $total_price;
}

// Bestellen
function order_vm($cpu, $ram, $ssd, $vm_name) {
    // Zugang zum Server
    $servers = isset($_SESSION['servers']) ? $_SESSION['servers'] : array();

    // Check falls server ein array ist
    if (!is_array($servers)) {
        return "Error: Server data is not properly initialized.";
    }

    // Check falls der name schon gibt
    foreach ($servers as $name => $server) {
        if (isset($server['vms']) && is_array($server['vms'])) {
            foreach ($server['vms'] as $vm) {
                if (isset($vm['name']) && $vm['name'] == $vm_name) {
                    return "Error: The name '{$vm_name}' is already taken.";
                }
            }
        }
    }

    // Preis berechung
    $cpu_price = calculate_cost($cpu, [1 => 5, 2 => 10, 4 => 18, 8 => 30, 16 => 45]);
    $ram_price = calculate_cost($ram, [512 => 5, 1024 => 10, 2048 => 20, 4096 => 40, 8192 => 80, 16384 => 160, 32768 => 320]);
    $ssd_price = calculate_cost($ssd, [10 => 5, 20 => 10, 40 => 20, 80 => 40, 240 => 120, 500 => 250, 1000 => 500]);

    $total_price = $cpu_price + $ram_price + $ssd_price;

    // Zuerst versuchen im kleinen server zu setzen
    $smaller_server_name = 'Small';
    if (isset($servers[$smaller_server_name]) &&
        $servers[$smaller_server_name]['used_cpu'] + $cpu <= $servers[$smaller_server_name]['cpu'] &&
        $servers[$smaller_server_name]['used_ram'] + $ram <= $servers[$smaller_server_name]['ram'] &&
        $servers[$smaller_server_name]['used_ssd'] + $ssd <= $servers[$smaller_server_name]['ssd']) {

        // Rein in den kleinen Server
        $servers[$smaller_server_name]['used_cpu'] += $cpu;
        $servers[$smaller_server_name]['used_ram'] += $ram;
        $servers[$smaller_server_name]['used_ssd'] += $ssd;
        $servers[$smaller_server_name]['vms'][] = ['name' => $vm_name, 'cpu' => $cpu, 'ram' => $ram, 'ssd' => $ssd];
        $_SESSION['servers'] = $servers;
        return "VM successfully allocated on server {$smaller_server_name}. Cost: {$total_price} CHF";
    }

    // Falls er voll ist, den anderen checken
    foreach ($servers as $name => &$server) {
        if ($server['used_cpu'] + $cpu <= $server['cpu'] &&
            $server['used_ram'] + $ram <= $server['ram'] &&
            $server['used_ssd'] + $ssd <= $server['ssd']) {

            // In diesen server einsetzen
            $server['used_cpu'] += $cpu;
            $server['used_ram'] += $ram;
            $server['used_ssd'] += $ssd;
            $server['vms'][] = ['name' => $vm_name, 'cpu' => $cpu, 'ram' => $ram, 'ssd' => $ssd];
            $_SESSION['servers'] = $servers;
            return "VM successfully allocated on server {$name}. Cost: {$total_price} CHF";
        }
    }

    return "No server has enough resources!";
}

// VM löschen
function remove_vm($vm_name) {
    // Zugang zu der session
    if (!isset($_SESSION['servers']) || !is_array($_SESSION['servers'])) {
        return "Error: Servers data is not properly initialized.";
    }

    $servers = $_SESSION['servers'];

    // Check falls die Vm existiert
    foreach ($servers as $server_name => &$server) {
        if (!is_array($server) || !isset($server['vms']) || !is_array($server['vms'])) {
            continue; // Skip this server if it's not an array or doesn't have 'vms'
        }

        foreach ($server['vms'] as $key => $vm) {
            // Check falls der name stimmt
            if (isset($vm['name']) && $vm['name'] == $vm_name) {
                // Free resources
                $server['used_cpu'] -= $vm['cpu'];
                $server['used_ram'] -= $vm['ram'];
                $server['used_ssd'] -= $vm['ssd'];

                // Löschen
                unset($server['vms'][$key]);

                $_SESSION['servers'] = $servers;
                return "VM '{$vm_name}' successfully removed from server '{$server_name}'.";
            }
        }
    }

    return "Error: VM '{$vm_name}' not found.";
}