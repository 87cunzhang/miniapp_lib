<?php

namespace MiniappLib\services;
/**
 * Class Miniapp_service
 * @property Mini_tmpl_config_model $Mini_tmpl_config_model
 */
class Miniapp_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mini_tmpl_config_model');
        $this->load->model('Mini_tmpl_history_model');
    }


    /**
     * 获取模板配置列表
     * @return array
     */
    public function get_mini_tmpl_config_list()
    {
        return $this->Mini_tmpl_config_model->filter();
    }

    /**
     * 获取实例化列表
     * @param $offset
     * @param $page_size
     * @param $shop_id
     * @param $history_id
     * @param $status
     */
    public function get_instance_list($offset, $page_size, $tmpl_id, $shop_id, $history_id, $status)
    {
        $result = array();
        $where  = array();

        if ($shop_id) {
            $where['shop_id'] = $shop_id;
        }

        if ($history_id) {
            $where['history_id'] = $history_id;
        }

        if ($status !== '') {
            $where['status'] = $status;
        }

        $_tmpl_id_model_map = \miniapp_consts::$_tmpl_id_model_map;
        $tmpl_model_name    = $_tmpl_id_model_map[$tmpl_id];
        $this->load->model($tmpl_model_name);

        $instance_list = $this->$tmpl_model_name->paginate($offset, $page_size, $where, '', array('id' => 'desc'));
        foreach ($instance_list as &$item) {
            $item['update_time'] = date('Y-m-d H:i', $item['update_time']);
            $item['status']      = \miniapp_consts::$miniapp_status[$item['status']];
        }

        $instance_total = $this->$tmpl_model_name->count_results($where);

        if ($instance_total) {
            $result['instance_list']  = $instance_list;
            $result['instance_total'] = $instance_total;
        }

        return $result;
    }


    /**
     * 更新小程序
     * @param $shop_id
     * @param $new_version
     * @param $app_id
     */
    public function update_miniapp($shop_id, $new_version, $app_id)
    {
        $msg_type    = 'miniapp_update_online';
        $exchange    = 'zkds';
        $routing_key = 'zkds';
        AMQP_basic_publish(json_encode(array('data' => compact('shop_id', 'app_id', 'new_version'), 'type' => $msg_type)), $exchange, $routing_key, TRUE);
        return get_success_result('实例化成功');
    }


    /**
     * 更新所有小程序
     * @param $new_version
     */
    public function update_all_shop($new_version)
    {
        AMQP_basic_publish(json_encode(['type' => 'miniapp_update_all', 'data' => array('new_version' => $new_version)]), 'zkds', 'zkds', TRUE);
        return get_success_result('ok');
    }


    /**
     * 添加模板配置
     * @param $tmpl_name
     * @param $tmpl_id
     * @param $new_version
     */
    public function do_add_tmpl_config($tmpl_name, $tmpl_id, $new_version)
    {
        $is_exist = $this->Mini_tmpl_config_model->get(compact('tmpl_id'));
        if ($is_exist) {
            return get_fail_result('请勿重复添加');
        }
        $create_time = $update_time = time();
        $this->Mini_tmpl_config_model->save(compact('tmpl_name', 'tmpl_id', 'new_version', 'update_time', 'create_time'));
        return get_success_result('操作成功');
    }


    /**
     * 根据类目ID获取类目信息
     * @param $id
     * @return array
     */
    public function get_tmpl_config_by_id($id)
    {
        $tmpl_config_info = $this->Mini_tmpl_config_model->get(compact('id'));
        return $tmpl_config_info;
    }


    /**
     * 修改模板配置信息
     * @param $id
     * @param $tmpl_name
     * @param $tmpl_id
     * @param $new_version
     * @return array
     */
    public function do_edit_tmpl_config($id, $tmpl_name, $tmpl_id, $new_version)
    {
        $update_time = time();
        $this->Mini_tmpl_config_model->update(compact('tmpl_name', 'tmpl_id', 'new_version'), compact('id'));
        return get_result(\result_consts::SUCCESS, 'ok');
    }


    /**
     * 获取小程序更新版本记录
     * @param $offset
     * @param $page_size
     * @param $tmpl_id
     * @param $shop_id
     * @param $history_id
     * @param $status
     * @return array
     */
    public function get_history_version_record($offset, $page_size, $tmpl_id, $history_id)
    {
        $result = array();
        $where  = array();

        //模板ID
        if ($tmpl_id) {
            $where['tmpl_id'] = $tmpl_id;
        }

        //批次ID
        if ($history_id) {
            $where['history_id'] = $history_id;
        }

        $list = $this->Mini_tmpl_history_model->paginate($offset, $page_size, $where, '', array('id' => 'desc'));
        foreach ($list as &$item) {
            $item['update_time'] = date('Y-m-d H:i', $item['update_time']);
            $item['status']      = \miniapp_consts::$miniapp_update_status[$item['status']];
        }

        $total = $this->Mini_tmpl_history_model->count_results($where);

        if ($total) {
            $result['list']  = $list;
            $result['total'] = $total;
        }

        return $result;
    }

}