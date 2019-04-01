<?php
namespace Models;

class Storage {
    // DB:
    private $conn;
    private $table = 'storage';

    //Properties:
    public $id;
    public $value;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllValues()
    {
        $query = ' SELECT * 
                   FROM ' . $this->table .
                 ' ORDER BY id DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getValueByKey()
    {
        $query = " SELECT * 
                   FROM " . $this->table .
                 " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->value = $row['value'];
        return $stmt;
    }

    public function setValueToKey()
    {
        $rows_quantity = $this->conn->query('SELECT count(*) FROM '.$this->table)->fetchColumn();
        if ($rows_quantity >= 1024) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Storage limit is reached (1024 keys)'
            ]);
            exit();
        }
        $this->id = htmlspecialchars(strip_tags(trim($this->id)));
        $this->value = htmlspecialchars(strip_tags(trim($this->value)));
        if (strlen($this->id) > 16 or strlen($this->value) > 512) {
            http_response_code(400);
            echo json_encode([
                'error' => 'key or value is too long (16 and 512 bytes respectively'
            ]);
            exit();
        }
        $query = 'INSERT INTO ' .$this->table . '
         SET 
            id = :id,
            value = :value';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':value', $this->value);

        try {
            $stmt->execute();
        }
        catch (\PDOException $e) {
            http_response_code(400);
            echo json_encode([
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function deleteByKey()
    {
        $this->id = htmlspecialchars(strip_tags(trim($this->id)));
        $query = 'DELETE FROM ' .$this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count === 0) {
            http_response_code(404);
            echo json_encode([
                'error' => 'not found'
            ]);
            exit;
        }
        return true;
    }
}