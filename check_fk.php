<?php
$pdo = new PDO("sqlite:database/database.sqlite");
$schema = $pdo->query("PRAGMA foreign_key_list(training)")->fetchAll(PDO::FETCH_ASSOC);
echo "Foreign keys on training table:\n";
foreach ($schema as $fk) {
    echo "  Column: " . $fk['from'] . " -> Table: " . $fk['table'] . " Column: " . $fk['to'] . "\n";
}

// Check if foreign keys are enabled
$enabled = $pdo->query("PRAGMA foreign_keys")->fetchColumn();
echo "\nForeign keys enabled: " . ($enabled ? 'YES' : 'NO') . "\n";
