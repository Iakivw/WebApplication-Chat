<?php
const SERVER = 'localhost';
const USER = 'root';
const PASS = '';
const DBNAME = 'chat_bd';

class classDBManager
{
    private string $server;
    private string $user;
    private string $pass;
    private string $dbname;
    private bool $db;
    private mysqli $conn;

    function __construct($server, $user, $pass, $dbname)
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->db = false;
        $this->openConnection();

//        if ($this->db) {
//            $this->createAndInsertTables();
//        }
    }

    function __destruct()
    {
        mysqli_close($this->conn);
    }

    private function openConnection(): void
    {
        $this->conn = new mysqli($this->server, $this->user, $this->pass);

        if ($this->conn->connect_error) {
            return;
        }

        $selectDB = @mysqli_select_db($this->conn, $this->dbname);

        if (!$selectDB) {
            return;
        }

        $this->db = true;
    }

    public function checkConnection(): bool
    {
        return $this->db;
    }

    public function createAndInsertTables(): bool
    {
        $create_tables = 'create table if not EXISTS users 
(user_id integer primary key, 
login varchar(15) not null, 
password varchar(10) not null,
privilege boolean not null)';

        $res_create = mysqli_query($this->conn, $create_tables);

        if (!$res_create)
        {
            return false;
        }

        $res_insert = 1;
        for ($i = 0; $i < 6; $i++)
        {
            $res_insert = $res_insert * $this->insert('users', [$i, 'alexei'.$i, 'password'.$i, 0]);
        }

        return $res_insert;
    }

    public function select($what, $from, $where = null, $order = null): false|array
    {
        $fetched = array();
        $sql = 'SELECT ' . $what . ' FROM ' . $from;
        if ($where != null) $sql .= ' WHERE ' . $where;
        if ($order != null) $sql .= ' ORDER BY ' . $order;

        $query = mysqli_query($this->conn, $sql);
        if ($query) {
            $rows = mysqli_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $results = mysqli_fetch_assoc($query);
                $key = array_keys($results);
                $numKeys = count($key);
                for ($x = 0; $x < $numKeys; $x++) {
                    $fetched[$i][$key[$x]] = $results[$key[$x]];
                }
            }
            return $fetched;
        } else {
            return false;
        }
    }

    public function insert($table, $values, $rows = null): bool
    {

        $insert = 'INSERT INTO ' . $table;
        if ($rows != null) {
            $insert .= ' (' . $rows . ')';
        }
        $numValues = count($values);
        for ($i = 0; $i < $numValues; $i++) {
            if (is_string($values[$i])) $values[$i] = '"' . $values[$i] . '"';
        }
        $values = implode(',', $values);
        $insert .= ' VALUES (' . $values . ')';
        $ins = mysqli_query($this->conn, $insert);
        return (bool)$ins;
    }

    public function update($table, $values, $where = null): bool
    {

        $update = 'UPDATE ' . $table . ' SET';
        $numValues = count($values);
        $i = 0;
        foreach ($values as $column => $data) {
            $update .= ' ' . $column . '=';
            if (is_string($data)) $data = '"' . $data . '"';
            $update .= $data;
            $i++;
            if ($i < $numValues) {
                $update .= ', ';
            }
        }

        if ($where != null) $update .= ' WHERE ' . $where;

        $ins = mysqli_query($this->conn, $update);
        return (bool)$ins;
    }

    public function delete($table, $where = null): bool
    {

        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        if ($where == null) {
            $sql = 'DELETE ' . $table;
        }
        $deleted = @mysqli_query($this->conn, $sql);
        return (bool)$deleted;
    }

}


