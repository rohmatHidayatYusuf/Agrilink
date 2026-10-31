<?php
/**
 * Base Model
 * AgriLink PHP Native MVC
 */

abstract class Model {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Helper untuk menjalankan query dengan prepared statement
     */
    protected function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Ambil satu baris data
     */
    protected function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Ambil semua baris data
     */
    protected function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Dapatkan ID baris terakhir yang di-insert
     */
    protected function lastInsertId(): string {
        return $this->db->lastInsertId();
    }
}
