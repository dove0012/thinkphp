<?php
namespace Common\Controller;
use Common\Controller\CommonController;
class MemberController extends CommonController
{
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
                $this->success('添加成功!');
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
            $model = D('Admin/Member');
            $m['id'] = session('edit_id');
            $m['username'] = trim($_POST['username']);
            $m['password'] = trim($_POST['password']);
            if ($m['password'] == '') {
                unset($m['password']);
            }
            if (!$model->create($m)) {
                $this->error($model->getError());
            }
            M()->startTrans();
            if ($model->save() !== false){
                $i['name'] = trim($_POST['name']);
                $age = trim($_POST['age']);
                $i['age'] = $age ? $age : 0;
                $i['sex'] = trim($_POST['sex']);
                $i['signature'] = trim($_POST['signature']);
                $i['homeplace'] = trim($_POST['homeplace']);
                $i['city'] = trim($_POST['city']);
                $i['picture'] = $_POST['picture'];
                $this->setData($i);
                $model = D('Admin/MemberInfo');
                if (!$model->create($i)) {
                    $this->error($model->getError());
                }
                if ($model->where('member_id='.session('edit_id'))->save() !== false) {
                    M()->commit();
                    $this->success('编辑成功!');
                } else {
                    M()->rollback();
                    @unlink(ROOT_PATH.$_POST['picture']);
                    $this->error('编辑失败!');
                }
            } else {
                M()->rollback();
                @unlink(ROOT_PATH.$_POST['picture']);
                $this->error('编辑失败!');
            }
        } else if ($member_id > 0) {
            session('edit_id', $member_id);
            $data = D('Member')->take($member_id);
            $this->assign('data', $data);
            $this->getData();
            $this->assign('mgames', explode("|", $data['games']));
            $this->assign('moften_go', explode("|", $data['often_go']));
            $this->display('add');
        }
    }

    private function getData() {
        //获取游戏和常去门店分类数据
        $this->assign('games', D('Admin/Category')->cat_list(1, 0, false));
        $this->assign('often_go', D('Admin/Category')->cat_list(12, 0, false));
    }

    private function setData(&$i) {
        //格式化游戏盒常去门店数据存入数据库
        if ($_POST['games'] && is_array($_POST['games']) && count($_POST['games']) <= 3) {
            $i['games'] = implode("|", $_POST['games']);
        }
        if ($_POST['often_go'] && is_array($_POST['often_go']) && count($_POST['often_go']) <= 3) {
            $i['often_go'] = implode("|", $_POST['often_go']);
        }
    }
}