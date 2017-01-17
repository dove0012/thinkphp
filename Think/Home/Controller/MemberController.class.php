<?php
namespace Home\Controller;
use Common\Controller\CommonController;

class MemberController extends CommonController {
	
    public function take() {
    	$res = D('Admin/Member')->take(S(C('USER_AUTH_KEY')));
        $res ? $this->success($res) : $this->error('获取失败!');
    }

    public function edit() {
    	$_POST['Member_id'] = S(C('USER_AUTH_KEY'));
    	R('Admin/Member/edit');
    }

    public function toOrder() {
    	
    }
}