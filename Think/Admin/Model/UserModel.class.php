<?php
namespace Admin\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel {
    protected $_link = array(
        'role_user'=>array(
            'mapping_type'=> self::HAS_ONE,
            'foreign_key'=>'user_id',
            'as_fields' =>'role_id',
        ),
    );
    protected $_validate  =  array(
        array('account','require','账号不能为空！'),
        array('password','require', '密码不能为空！'),
        array('verify','require','验证码不能为空！',1),
        array('verify','check_verify','验证码错误',0,'function'),
    );
}