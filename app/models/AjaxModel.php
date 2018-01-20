<?php

class AjaxModel extends DBController
{

    private $_db;

    public function __construct()
    {
        parent::__construct();
        $this->_db = self::getDb();
    }


    public function check($idCourier, $dateStart, $dateEnd)
    {
        $stm = $this->_db->prepare(<<<TAG
SELECT 
    id_courier, id_region, date_start, date_end, date_reverse 
FROM 
    tasks 
WHERE 
    id_courier = :id_courier 
        AND (((:date_start BETWEEN date_start AND date_reverse) 
        AND (:date_end BETWEEN date_start AND date_reverse))
        OR  (date_start BETWEEN :date_start AND :date_end) 
        AND (date_reverse BETWEEN :date_start AND :date_end))
TAG
        );
        $stm->bindValue(':id_courier', $idCourier, PDO::PARAM_INT);
        $stm->bindValue(':date_start', $dateStart, PDO::PARAM_STR);
        $stm->bindValue(':date_end', $dateEnd, PDO::PARAM_STR);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOfferTask($idCourier)
    {
        $stm = $this->_db->prepare(<<<TAG
SELECT 
    a.id_courier, 
    a.date_reverse AS date_start, 
    DATE_SUB(b.date_start, INTERVAL (DATEDIFF(b.date_start, a.date_reverse) / 2) DAY) AS date_end
FROM
    tasks a
        INNER JOIN
    tasks b ON a.id = (b.id - 1)
        AND a.id_courier = b.id_courier
WHERE
    a.date_reverse < b.date_start
        AND a.date_reverse > NOW()
        AND a.id_courier = :id_courier 
LIMIT 3;
TAG
        );
        $stm->bindValue(':id_courier', $idCourier, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertTask($idCourier, $idRegion, $dateStart, $dateEnd)
    {
        $dateStartObject = new DateTime($dateStart);
        $dateEndObject = new DateTime($dateEnd);
        $dateReverseObject = $dateEndObject->add($dateStartObject->diff($dateEndObject));

        $stm = $this->_db->prepare("INSERT INTO tasks (id_courier, id_region, date_start, date_end, date_reverse) VALUE (:id_courier, :id_region, :date_start, :date_end, :date_reverse)");
        $stm->bindValue(':id_courier', $idCourier, PDO::PARAM_INT);
        $stm->bindValue(':id_region', $idRegion, PDO::PARAM_INT);
        $stm->bindValue(':date_start', $dateStartObject->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stm->bindValue(':date_end', $dateEnd, PDO::PARAM_STR);
        $stm->bindValue(':date_reverse', $dateReverseObject->format("Y-m-d H:i:s"), PDO::PARAM_STR);

        return $stm->execute();
    }


    public function clearTasks()
    {
        return $this->_db->exec("truncate tasks");
    }


    public function generateData()
    {

        $count = count($this->getCouriers());

        for ($cr = 1; $cr <= $count; $cr++) {

            $dateBegin = '2015-06-01';

            for ($i = 1; $i <= 100; $i++) {

                $randomDays = rand(1, 10);
                $dateEnd = date('Y-m-d H:i:s', strtotime($dateBegin .' +'. $randomDays .' Day'));
                $dateReverse = date('Y-m-d H:i:s', strtotime($dateBegin .' +'. ($randomDays * 2) .' Day'));

                $stm = $this->_db->prepare("INSERT INTO tasks (id_courier, id_region, date_start, date_end, date_reverse) VALUES (:id_courier, :id_region, :date_start :date_end, :date_reverse)");
                $stm->execute([
                    ':id_courier' => $cr,
                    ':id_region' => rand(1, 10),
                    ':date_start' => date('Y-m-d H:i:s', strtotime($dateBegin)),
                    ':date_end' => date('Y-m-d H:i:s', strtotime($dateEnd)),
                    ':date_reverse' => date('Y-m-d H:i:s', strtotime($dateReverse)),
                ]);
                $dateBegin = date('Y-m-d', strtotime($dateReverse .' +'. rand(0, 10) .' Day'));
            }
        }

        return true;
    }


    public function getCouriers()
    {
        $stm = $this->_db->prepare("SELECT id, name FROM couriers");
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
