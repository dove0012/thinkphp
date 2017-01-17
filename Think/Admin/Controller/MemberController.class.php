<?php
namespace Admin\Controller;
use Common\Controller\CommonController;

class MemberController extends CommonController
{
	public function take($god=0) {
        $god == 1 ? exit(json_encode(D('Admin/Member')->takeGod())) : exit(json_encode(D('Admin/Member')->take()));
    }

    public function add() {
        if ($_POST) {
            if ($_FILES) {
                $res = uploadImage();
                if ($res['error'] == 1) {
                    $this->error($res['msg']);
                }
                $_POST['picture'] = $res['fullpath'];
            }
            $model = D('Admin/Member');
            if ($model->register($_POST)) {
                $this->success('注册成功!');
            } else {
                @unlink(ROOT_PATH.$_POST['picture']);
                $this->error($model->getError());
            }
        } else {
            $this->getData();
            $this->display();
        }
    }
    
    public function edit($member_id=0){
        if ($_POST) {
            if ($_FILES) {
                $res = uploadImage();
                if ($res['error'] == 1) {
                    $this->error($res['msg']);
                }
                $_POST['picture'] = $res['fullpath'];
            }
            $_POST['member_id'] = session('edit_id');
            $model = D('Admin/Member');
            if ($model->edit($_POST)) {
                $this->success('更新成功!');
            } else {
                @unlink(ROOT_PATH.$_POST['picture']);
                $this->error($model->getError());
            }
        } else if ($member_id > 0) {
            session('edit_id', $member_id);
            $data = D('Admin/Member')->take($member_id);
            $this->assign('data', $data);
            $this->getData();
            $this->assign('mgames', explode("|", $data['games']));
            $this->assign('moften_go', explode("|", $data['often_go']));
            $this->display('add');
        }
    }

    public function applyGod($id) {
        if ($id > 0) {
            D('Admin/God')->apply(array('member_id'=>$id)) !== false ? $this->success('申请成功!') : $this->error(D('Admin/God')->getError());
        }
    }
	
    public function del($id) {
        if ($id > 0) {
            M('member')->where('id='.$id)->save(array('status'=>0)) !== false ? $this->success('删除成功!') : $this->error('删除失败!');
        }
    }

    public function getData() {
        //获取游戏和常去门店分类数据
        $this->assign('games', D('Admin/Category')->cat_list(1, 0, false));
        $this->assign('often_go', D('Admin/Category')->cat_list(12, 0, false));
    }
}