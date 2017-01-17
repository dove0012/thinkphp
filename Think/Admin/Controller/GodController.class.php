<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class GodController extends CommonController
{
	public function index() {
		$this->assign('type', $_GET['type']);
		$this->display();
	}

    public function take($type='all') {
        exit(json_encode(D('God')->take($type)));
    }

    public function checkApply($id=0, $member_id=0) {
    	$model = D('Admin/God');
    	if ($_POST) {
    		$id = session('edit_id');
    		$member_id = session('edit_member_id');
	    	$data = array('id'=>$id,'member_id'=>$member_id,'status'=>$_POST['status'],'remark'=>$_POST['remark']);
	    	$model->checkApply($data) !== false ? $this->success('操作成功!') : $this->error($model->getError());
    	} else {
    		session('edit_id', $id);
    		session('edit_member_id', $member_id);
    		$data = $model->where('id=%d', array($id))->find();
    		$this->assign('data', $data);
    		$this->display();
    	}
    }
}