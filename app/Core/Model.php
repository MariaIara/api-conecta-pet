<?php

namespace App\Core;

use App\Core\config\Database;
use PDO;

/* ~~~ Base Model 🎩✨ ~~~ */

abstract class Model extends Database
{
    protected $table;


    /**
     * Retrieve all records from the table.
     * 
     * @return array An array of all records in the table.
     */
    protected function all()
    {
        $query = "select * from {$this->table}";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Find a record by its ID.
     * 
     * @param int $id The ID of the record to find.
     * @return array|null The found record as an associative array, or null if not found.
     */
    protected function find($id)
    {
        $query = "select * from {$this->table} where id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new record into the table.
     * 
     * @param array $data An associative array where the keys are column names and the values are the values to insert.
     * @return bool True on success, False on failure.
     */
    protected function save(array $data)
    {
        $fields = array_keys($data);
        $fieldList = implode(", ", $fields);
        $placeholders = ":" . implode(", :", $fields);

        $query = "insert into {$this->table} ($fieldList) values ($placeholders)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    /**
     * Update an existing record in the table.
     * 
     * @param int $id The ID of the record to update.
     * @param array $data An associative array of column names and their new values.
     * @return bool True on success, False on failure.
     */
    protected function update($id, array $data)
    {
        $fields = array_keys($data);
        $fieldList = implode(" = ?, ", $fields) . " = ?";

        $query = "update {$this->table} set $fieldList where id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(array_merge(array_values($data), [$id]));
    }

    /**
     * Delete a record from the table by its ID.
     * 
     * @param int $id The ID of the record to delete.
     * @return bool True on success, False on failure.
     */
    protected function delete($id)
    {
        $query = "delete from {$this->table} where id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
