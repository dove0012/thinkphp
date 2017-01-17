<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class RoleModel extends RelationModel {

    protected $_validate = array(
        array('name', 'require', '项目名不能为空!'), //默认情况下用正则进行验证
        array('title', 'require', '项目昵称不能为空!'), //默认情况下用正则进行验证
        array('name', '', '项目名已经存在!', 0, 'unique'), // 在新增的时候验证name字段是否唯一
        array('status', array(0, 1), '状态值的范围不正确!', 2, 'in'), // 当值不为空的时候判断是否在一个范围内
        array('level', array(1, 2, 3), '等级值的范围不正确!', 2, 'in'), // 当值不为空的时候判断是否在一个范围内
    );

}