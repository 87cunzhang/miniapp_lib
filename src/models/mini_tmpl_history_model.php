<?php
namespace MiniappLib\models;
class Mini_tmpl_history_model extends \MY_Model
{
    protected $db_group_name = 'member';

    public function __construct()
    {
        parent::__construct();
        $this->table = 'txzj_mini_tmpl_history';
    }

    public function get_last_history($where)
    {
        $this->database->select();
        $this->database->where($where);
        $this->database->order_by('id', 'DESC');
        $this->database->limit(1, 0);
        return $this->database->get($this->table)->row_array();
    }
}