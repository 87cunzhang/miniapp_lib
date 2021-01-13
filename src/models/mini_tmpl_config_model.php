<?php
namespace MiniappLib\models;
class Mini_tmpl_config_model extends \MY_Model
{
    protected $db_group_name = 'member';

    public function __construct()
    {
        parent::__construct();
        $this->table = 'txzj_mini_tmpl_config';
    }

    public function get_mini_tmpl_type()
    {
        $this->database->select();
        $this->database->group_by('tmpl_type');
        return $this->database->get($this->table)->result_array();
    }
}