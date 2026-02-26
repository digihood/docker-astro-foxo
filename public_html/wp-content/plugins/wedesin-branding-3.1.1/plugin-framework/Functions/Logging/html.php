<?php

// Funkce pro načtení seznamu kanálů

use digibrand\framework\Globals;

function listChannels() {
    $upload_dir = wp_upload_dir();
    $log_dir = $upload_dir['basedir']. '/digi-logs/';
    
  
  
    $files = scandir($log_dir);
    $channels = [];

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) == "json") {
            $channels[] = pathinfo($file, PATHINFO_FILENAME);
        }
    }

    return $channels;
}

// Funkce pro načtení logů na základě vybraného kanálu
function getLogs($channel) {
    $upload_dir = wp_upload_dir();
    $log_dir = $upload_dir['basedir']. '/digi-logs/';
    $filename = $log_dir . $channel . '.json';
    if (file_exists($filename)) {
        return json_decode(file_get_contents($filename), true);
    }
    return [];
}

$selectedChannel = isset($_GET['channel']) ? $_GET['channel'] : (isset(listChannels()[0]) ? listChannels()[0] : null);
$logs = getLogs($selectedChannel);

?>

<!DOCTYPE html>
<html>
<script src="<?= Globals::$FWDIGI_URL ?>/plugin-framework/Functions/Logging/src/json-view.js"></script>
<body>

<h2>Zobrazit logy na základě vybraného kanálu</h2>

<form method="get" action="admin.php">
    <select name="channel" onchange="this.form.submit()">
        <?php
        $channels = listChannels();
        foreach ($channels as $channel) {
            echo "<option value='" . $channel . "'" . ($channel == $selectedChannel ? " selected" : "") . ">" . $channel . "</option>";
        }
        ?>
    </select>
    <input type="hidden" name="page" value="d1g1-logs">
    <input type="hidden" name="tab" value="<?= Globals::$FWDIGI_PLUGINID ?>">
</form>
<?php
if($selectedChannel){
   $upload_dir = wp_upload_dir();
   ?>
    <a href="<?= $upload_dir['baseurl']. '/digi-logs/'.$selectedChannel ?>.json" downl>Stáhnout log</a>
    <table border='1'>
        <tr><th>Timestamp</th><th>Level</th><th>Message</th><th>Data</th><th>Called From</th></tr>
        <?php
        foreach ($logs as $log) {
            echo "<tr>";
            echo "<td>" . $log['timestamp'] . "</td>";
            echo "<td>" . $log['level'] . "</td>";
            echo "<td>" . $log['message'] . "</td>";
            echo "<td>" . @json_encode($log['data']) . "</td>";
            echo "<td>" . $log['called_from'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
<?php
}
?>
</body>
</html>
