<?php

class IndexController
{

    public $app_name;
    public $db;
    public $view;

    public function __construct()
    {
        $this->app_name = 'Расписание';
        $this->db = new DBModel();
        $this->view = new ViewModel();

        $this->indexAction();
    }


    public function indexAction()
    {
        $data['couriersList'] = $this->db->getCouriers();
        $data['regionsList'] = $this->db->getRegions();
        $data['scheduleTodayList'] = $this->db->getScheduleToday();

        $this->view->render('header', $this->app_name);
        $this->view->render('body', $data);
        $this->view->render('footer');
    }

}
