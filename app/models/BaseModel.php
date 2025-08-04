<?php
abstract class BaseModel
{
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new Exception("Property '$property' does not exist.");
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        } else {
            throw new Exception("Property '$property' does not exist.");
        }
    }

    public function toArray()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
?>
