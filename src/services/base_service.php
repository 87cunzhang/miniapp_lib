<?php
namespace MiniappLib\services;
/**
 * Class Test_service
 * @property test_model $test_model
 */
//加载service子类


class Base_service extends \MY_Service
{

    public function __construct()
    {
        parent::__construct();
        load_class('MY_Service', 'core', '');
        include_once 'system/core/Loader.php';
        $this->load->add_package_path(__DIR__ . '/miniapp_lib/');
    }

    public function test()
    {
        echo 111;
        $res = $this->test_model->get();
        var_dump($res);
    }

}