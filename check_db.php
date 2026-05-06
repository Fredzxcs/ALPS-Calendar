<?php
$pdo = new PDO("sqlite:database/database.sqlite");
foreach (array("course", "company", "users", "credentials") as $table) {
    $count = $pdo->query("SELECT COUNT(*) FROM \"" . $table . "\"")->fetchColumn();
    echo "$table: $count records\n";
}
