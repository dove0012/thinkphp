<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class RoleModel extends RelationModel {

    protected $_validate = array(
        array('name', 'require', '角色名不能为空!'), //默认情况下用正则进行验证
        array('name', '', '角色名已经存在!', 0, 'unique'), // 在新增的时候验证name字段是否唯一
        array('status', array(0, 1), '值的范围不正确!', 2, 'in'), // 当值不为空的时候判断是否在一个范围内
    );
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'), // 对create_time字段在创建的时候写入当前时间戳
        array('update_time', 'time', 3, 'function'), // 对update_time字段在所有情况都写入当前时间戳
    );

}