<?php

namespace UmnLib\Core;

class MySQLiResultIterator implements \Iterator
{
    protected $mysqli;
    public function mysqli()
    {
        return $this->mysqli;
    }
    public function setMysqli($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function query()
    {
        return $this->query;
    }
    public function setQuery($query)
    {
        $this->query = $query;
    }

    protected $result;
    public function result()
    {
        return $this->result;
    }
    public function setResult($result)
    {
        $this->result = $result;
    }

    protected $position;  
    public function position()
    {
        return $this->position;
    }
    public function setPosition($position)
    {
        $this->position = $position;
    }

    protected $row;  
    public function row()
    {
        return $this->row;
    }
    public function setRow($row)
    {
        $this->row = $row;
    }
    
    /** 
     * Constructor 
     * @param MySQLi $mysqli 
     * @param string $query
     */  
    function __construct($mysqli, $query)
    {
        $this->setMysqli($mysqli);
        $this->setQuery($query);

        $result = $mysqli->query($query);
        if (false === $result) {
            throw new Exception("MySQL Error: {$mysqli->errno}: {$mysqli->error}");
        }
        $this->setResult($result);
    }
    
    /** 
     * Destructor 
     * Frees the result object. 
     */  
    public function __destruct()  
    {  
        $this->result()->free();  
    }  
    
    /** 
     * Rewinds the internal pointer. 
     */  
    public function rewind()  
    {  
        $result = $this->result();

        // data_seek moves the results internal pointer  
        $this->setPosition(0);
        $result->data_seek($this->position());
    
        // prefetch the current row  
        // note that this advances the results internal pointer.  
        $this->setRow($result->fetch_assoc());
    }  
    
    /** 
     * Moves the internal pointer one step forward. 
     */  
    public function next()  
    {  
        // Prefetch the current row. 
        $this->setRow($this->result()->fetch_assoc());
        $this->setPosition($this->position() + 1);
    }  
    
    /** 
     * Returns true if the current position is valid, false otherwise. 
     * @return bool 
     */  
    public function valid()  
    {  
        return $this->position() < $this->result()->num_rows;  
    }  
    
    /** 
     * Returns the nid from the row that matches the current position. 
     * @return int
     */  
    public function current()  
    {  
        return $this->row();  
    }  
    
    /** 
     * Returns the current position. 
     * @return int 
     */  
    public function key()  
    {  
        return $this->position();  
    }  
}
