<?php

namespace MiniappLib\services;
/**
 * Class Miniapp_service
 * @property test_model $test_model
 */
class Miniapp_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('test_model');
    }

    public function test()
    {
        $res = $this->test_model->get();
        var_dump($res);
    }

}