<?php

class DBModel extends DBController
{

    private $_db;

    public function __construct()
    {
        parent::__construct();
        $this->_db = self::getDb();
    }


    public function getScheduleToday()
    {
        $stm = $this->_db->prepare(<<<TAG
SELECT 
    c.name AS courier,
    r.name AS region,
    t.date_start,
    DATE_ADD(t.date_end,
        INTERVAL r.time_shift HOUR) AS date_end,
    t.date_reverse,
    IF(NOW() < date_end, 0, 1) AS state
FROM
    tasks AS t
        JOIN
    couriers AS c
        JOIN
    regions AS r
WHERE
    NOW() BETWEEN date_start AND date_reverse
        AND c.id = t.id_courier
        AND r.id = t.id_region
TAG
        );
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getRegions()
    {
        $stm = $this->_db->prepare("SELECT id, name, time_shift FROM regions");
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCouriers()
    {
        $stm = $this->_db->prepare("SELECT id, name FROM couriers");
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
