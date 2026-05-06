<?php
$pdo = new PDO("sqlite:database/database.sqlite");
foreach (array("course", "company", "users", "credentials") as $table) {
    $ids = $pdo->query("SELECT id FROM \"" . $table . "\" ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
    echo $table . ": IDs = [" . implode(", ", $ids) . "]\n";
}
