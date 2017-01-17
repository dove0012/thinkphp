<?php

namespace Admin\Model;

use Think\Model\RelationModel;

// 用户类 基于ucenter

class MemberInfoModel extends RelationModel {

    protected $_validate = array(
        //array('city', 'require', '所在城市不能为空!'), //默认情况下用正则进行验证
        array('age', '0,200', '请输入正确的年龄!', 2, 'between', 3), //默认情况下用正则进行验证
    );

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'), // 对create_time字段在创建的时候写入当前时间戳
        array('update_time', 'time', 3, 'function'), // 对update_time字段在所有情况都写入当前时间戳
    );
}