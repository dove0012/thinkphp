<?php

namespace Admin\Model;

use Think\Model\RelationModel;

// 用户类 基于ucenter

class ImageModel extends RelationModel {

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'), // 对create_time字段在创建的时候写入当前时间戳
        array('update_time', 'time', 3, 'function'), // 对update_time字段在所有情况都写入当前时间戳
    );

    public function take($data) {
    	$back = $whereStr = $whereArray = array();
        if ($data['id']) {
            $back = $this->where('id=%d and member_id=%d', array($data['id'],$data['member_id']))->find();
        } else {
            $whereStr   = $data['type'] ? "type='%s' and member_id=%d" : 'member_id=%d';
            $whereArray = $data['type'] ? array($data['type'], $data['member_id']) : array($data['member_id']);
            $back = $this->where($whereStr, $whereArray)->order('create_time desc')->mSelect();
        }
        return $back;
    }
}