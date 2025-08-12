<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $pdo;
    private $stmt;
    private $error;

    public function __construct()
    {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false 
        );

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            // print_r($this->pdo);
            // echo "Success";
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function routeProcedure($name, $params = [])
    {
        try {
            $placeholders = implode(',', array_fill(0, count($params), '?'));
            $sql = "CALL $name($placeholders)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array_values($params));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function create($table, $data)
    {
        try {
            $columns = array_keys($data);
            // Wrap each column name with backticks to escape reserved words
            $escapedColumns = array_map(function($col) {
                return "`$col`";
            }, $columns);

            $columnSql = implode(', ', $escapedColumns);
            $bindingSql = ':' . implode(',:', $columns);

            $sql = "INSERT INTO $table ($columnSql) VALUES ($bindingSql)";
            $stm = $this->pdo->prepare($sql);

            foreach ($data as $key => $value) {
                $stm->bindValue(':' . $key, $value);
            }

            $status = $stm->execute();
            return ($status) ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function verifyMail($userId)
    {
        try {
            $sql = "UPDATE users SET is_confirmed = 1, token = NULL WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log or handle the error
            error_log("verifyMail error: " . $e->getMessage());
            return false;
        }
    }

    public function getWhere($table, $column, $value)
    {
        $this->query("SELECT * FROM $table WHERE $column = :value");
        $this->bind(':value', $value);
        return $this->resultSet();
    }

    public function update($table, $id, $data)
    {
        // First, we don't want id from category table
        if (isset($data['id'])) {
            unset($data['id']);
        }

        try {
            $columns = array_keys($data);

            // wrap column names in backticks to avoid SQL keywords issues
            function map($item) {
                return '`' . $item . '`=:' . $item;
            }
            $columns = array_map('map', $columns);
            $bindingSql = implode(',', $columns);

            $sql = 'UPDATE `' .  $table . '` SET ' . $bindingSql . ' WHERE `id` =:id';

            $stm = $this->pdo->prepare($sql);

            // Now, we assign id to bind
            $data['id'] = $id;

            foreach ($data as $key => $value) {
                $stm->bindValue(':' . $key, $value);
            }
            return $stm->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete($table, $id)
    {
        $sql = 'DELETE FROM ' . $table . ' WHERE `id` = :id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id);
        $success = $stm->execute();
        return ($success);
    }

    public function deleteByColumn($table, $column, $value)
    {
        $sql = "DELETE FROM {$table} WHERE {$column} = :value";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':value', $value);
        return $stm->execute();
    }
    
    public function columnFilter($table, $column, $value)
    {
        // $sql = 'SELECT * FROM ' . $table . ' WHERE `' . $column . '` = :value';
        $sql = 'SELECT * FROM ' . $table . ' WHERE `' . str_replace('`', '', $column) . '` = :value';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':value', $value);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function loginCheck($email, $password)
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `password` = :password';
        // echo $sql;
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':email', $email);
        $stm->bindValue(':password', $password);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function getByEmail($table, $email)
    {
        $sql = "SELECT * FROM {$table} WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function otpcheck($email,$otp)
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `otp` = :otp';
        // echo $sql;
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':email', $email);
        $stm->bindValue(':otp', $otp);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function setLogin($id)
    {
        $sql = 'UPDATE users SET `is_login` = :value WHERE `id` = :id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':value', 1);
        $stm->bindValue(':id', $id);
        $success = $stm->execute();
        $stm->closeCursor();    // to solve PHP Unbuffered Queries
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function unsetLogin($id)
    {
       try{ 
           $sql        = "UPDATE users SET is_login = :false WHERE id = :id";
           $stm        = $this->pdo->prepare($sql);
           $stm->bindValue(':false','0');
           $stm->bindValue(':id',$id);
           $success    = $stm->execute();
           $row        = $stm->fetch(PDO::FETCH_ASSOC);
           return ($success) ? $row : [];
        }
        catch( Exception $e)
        {
            echo($e);
        }
    }

    public function readAll($table)
    {
        $sql = 'SELECT * FROM ' . $table;
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $success = $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }
    
    public function runQuery($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByRole($table, $roleid)
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE `role_id` = :roleid';
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':roleid', $roleid);
        $success = $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function getById($table, $id)
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE `id` =:id';
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

}

