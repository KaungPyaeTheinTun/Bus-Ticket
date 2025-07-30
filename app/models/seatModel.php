<?php

class SeatModel
{
    private $id;
    private $route_id;
    private $seat_number;
    private $is_booked;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRoute_id($route_id)
    {
        $this->route_id = $route_id;
    }

    public function getRoute_id()
    {
        return $this->route_id;
    }

    public function setSeat_number($seat_number)
    {
        $this->seat_number = $seat_number;
    }

    public function getSeat_number()
    {
        return $this->seat_number;
    }

    public function setIs_booked($is_booked)
    {
        $this->is_booked = $is_booked;
    }

    public function getIs_booked()
    {
        return $this->is_booked;
    }

    public function toArray()
    {
        return [
            'route_id'     => $this->getRoute_id(),
            'seat_number'  => $this->getSeat_number(),
            'is_booked'    => $this->getIs_booked(),
        ];
    }
}

?>