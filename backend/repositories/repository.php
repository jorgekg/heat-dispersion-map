<?php

abstract class Repository {

    /** @var PDO */
    protected $database;
    protected $table;

    public function create($data) {
        $stmt = $this->database->prepare("INSERT INTO " . $this->table . " (endpoint, millis, browser, platform, createAt) values (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $data->endpoint);
        $stmt->bindValue(2, $data->millis);
        $stmt->bindValue(3, $data->browser);
        $stmt->bindValue(4, $data->platform);
        $stmt->bindValue(5, date('Y-m-d h:i:s'));
        $stmt->execute();
    }

}