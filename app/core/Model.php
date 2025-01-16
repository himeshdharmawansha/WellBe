<?php

class Model extends Database
{

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "asc";
    protected $order_column = "id";
    public $errors = [];
    protected $table;
    protected $allowedColumns;

    public function findAll()
    {
        $query = "select * from $this->table order by $this->order_column $this->order_type limit $this->limit offset $this->offset";

        //show($query);
        return $this->query($query);
    }

    public function where($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }

        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        $query = trim($query, " && ");

        $query .= " order by $this->order_column $this->order_type limit $this->limit offset $this->offset";

        $data = array_merge($data, $data_not);
        //show($query);
        return $this->query($query, $data);
    }

    public function first($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }

        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        $query = trim($query, " && ");

        $query .= " limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);

        $result = $this->query($query, $data);

        if ($result)
            return $result[0];

        return false;
    }

    public function insert($data)
    {
        /** remove unwanted data */
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {

                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);

        $query = "insert into $this->table (" . implode(",", $keys) . ") values (:" . implode(",:", $keys) . ")";

        $this->query($query, $data);

        return false;
    }

    public function update($id, $data, $id_column = 'id')
    {
        /** remove unwanted data */
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {

                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "update $this->table set ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        $query = trim($query, ", ");

        $data[$id_column] = $id;
        $query .= " where $id_column = :$id_column";

        //echo $query;
        $this->query($query, $data);
        return false;
    }

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "delete from $this->table where $id_column = :$id_column";
        echo $query;
        $this->query($query, $data);

        return false;
    }

    public function loggedin()
    {
       $DB = new Database();
       // Update user state to 1 (logged in)
       $updateStateQuery = "UPDATE user_profile SET state = 1 WHERE id = :userid";
       $DB->write($updateStateQuery, ['userid' => $_SESSION['userid']]);
 
       // Update messages as received
       $updateQuery = "UPDATE message SET received = 1 WHERE receiver = :receiver AND received = 0";
       $DB->write($updateQuery, ['receiver' => $_SESSION['userid']]);
    }

    public function getAll()
    {
        $query = "select * from $this->table ORDER BY id DESC";

        //show($query);
        return $this->query($query);
    }

}
