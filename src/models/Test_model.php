<?php
namespace MiniappLib\models;
class Test_model extends \MY_Model
{
    protected $db_group_name = 'member';

    public function __construct()
    {
        parent::__construct();
        $this->table = 'txzj_mini_tmpl_C3413';
    }
}