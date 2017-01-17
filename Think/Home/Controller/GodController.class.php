<?php
namespace Home\Controller;
use Common\Controller\CommonController;

class GodController extends CommonController {
	
    public function take() {
    	$res = D('Admin/God')->take(1);
        $res ? $this->success($res) : $this->error('获取失败!');
    }

    public function shopAdress() {
    	$res = D('Home/God')->shopAdress(13);
        $res ? $this->success($res) : $this->success('该地区没有门店!');
    }

    public function games() {
    	$res = D('Home/God')->games(1);
        $res ? $this->success($res) : $this->success('该地区没有游戏!');
    }

    public function photos() {
    	$res = D('Home/God')->photos($_POST['member_id']);
    	$res ? $this->success($res) : $this->success('该游神没有相册!');
    }

    public function toOrder() {
        $data['shop_id'] = trim($_POST['shopId']);
        $data['game_id'] = trim($_POST['gameId']);
        $data['order_time'] = strtotime(trim($_POST['time']));
        $data['hour'] = trim($_POST['hour']);
        $data['member_id'] = session(C('USER_AUTH_KEY'));
        $data['to_member_id'] = trim($_POST['to_member_id']);
        $data['remark'] = trim($_POST['remark']);
        $shop = M('category')->where('id=%d', array($data['shop_id']))->find();
        if (!$shop) {
            $this->error('该门店不存在!');
        }
        $data['shop_name'] = $shop['name'];
        $data['game_name'] = M('category')->where('id=%d', array($data['game_id']))->getField('name');
        if (!$data['game_name']) {
            $this->error('该游戏不存在!');
        }
        $god = D('Admin/Member')->take($data['to_member_id']);
        if (!$god) {
            $this->error('该游神不存在!');
        }
        $member = D('Admin/Member')->take($data['member_id']);
        if (!$member) {
            $this->error('请先登陆!');
        }
        $area = M('category')->where('id=%d', array($shop['pid']))->find();
        if (!$area) {
            $this->error('该城市不存在!');
        }
        $city = M('category')->where('id=%d', array($area['pid']))->getField('name');
        if (!$city) {
            $this->error('该城市不存在!');
        }
        $data['order_city'] = array('x', $city, $area['name']);
        $data['price'] = $god['cost'];
        $data['to_member_name'] = $god['name'];
        $data['to_member_city'] = explode('|', $god['city']);
        $data['member_city'] = explode('|', $member['city']);
        $data['member_name'] = $member['name'];
        $data['total_price'] = $god['cost'] * $data['hour'];
        false === D('Admin/Order')->addOrder($data) ? $this->error(D('Admin/Order')->getError()) : $this->success('订单生成成功!');
    }
}