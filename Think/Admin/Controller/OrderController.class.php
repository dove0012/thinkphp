<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class OrderController extends CommonController
{
    public function take() {
        exit(json_encode(D('Admin/Order')->take()));
    }
    
    public function add() {
        if ($_POST) {
            $model = D('Admin/Order');
            $model->addOrder($_POST) ? $this->success('订单生成成功!') : $this->error($model->getError());
        } else {
            A('Admin/Member')->getData();
            $this->display();
        }
    }
    
    public function edit($id=0){
        $model = D('Admin/Order');
        if ($_POST) {
            $_POST['id'] = session('edit_id');
            $model->edit($_POST) ? $this->success('编辑订单成功!') : $this->error($model->getError());
        } else {
            session('edit_id', $id);
            $this->assign('data', $model->take(array('id'=>$id)));
            A('Admin/Member')->getData();
            $this->display('add');
        }
    }
    
    public function del($id) {
        if ($id > 0) {
            M('order')->where('id='.$id)->save(array('status'=>0)) !== false ? $this->success('删除成功!') : $this->error('删除失败!');
        }
    }
}