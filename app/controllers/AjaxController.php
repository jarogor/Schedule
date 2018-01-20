<?php

class AjaxController
{

    private $_db;
    public $result = [];

    public function __construct($request)
    {
        $this->_db = new AjaxModel();

        if(isset($request['addTask'])) {
            $task = json_decode($request['addTask']);
            $this->checkTask($task);
        }

        if(isset($request['clear'])) {
            $this->result['status'] = 'clearTasks';
            $this->result['data'] = $this->_db->clearTasks();
        }

        if(isset($request['generate'])) {
            $this->result['status'] = 'generate';
            $this->result['data'] = $this->_db->generateData();
        }

        $this->response();
    }


    public function offerTask($courier)
    {
        $this->result['status'] = 'offerTask';
        $this->result['data'] = $this->_db->getOfferTask($courier);
    }


    public function checkTask($task)
    {
        $res = $this->_db->check($task->courier, $task->date_start, $task->date_end);

        if (count($res) > 0) {
            $this->offerTask($task->courier);
        } else {
            $this->setTask($task);
        }
    }



    public function setTask($task)
    {
        $this->result['status'] = 'setTask';
        $this->result['data'] = $this->_db->insertTask($task->courier, $task->region, $task->date_start, $task->date_end);
    }


    public function response()
    {
        header('Content-type: application/json');
        print json_encode($this->result);
    }

}
