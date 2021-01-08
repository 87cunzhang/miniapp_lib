<?php

namespace miniapp\services;

/**
 * Class Test_service
 * @property test_model $test_model
 */
//加载service子类
load_class('MY_Service', 'core', '');
include 'system/core/Loader.php';

class Test_service extends \MY_Service
{

    public function __construct()
    {
        parent::__construct();
        $this->load->add_package_path(__DIR__ . '/miniapp_lib/');
        $this->load->model('test_model');
    }

    public function test()
    {
        echo 111;
        $res = $this->test_model->get();
        var_dump($res);
    }

}