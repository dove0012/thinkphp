<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class GameController extends CommonController
{
    public function take() {
        exit(json_encode(M('order')->select()));
    }
    
    public function add() {
    }
    
    public function edit($id=0){
    }
    
    public function del($id) {
        if ($id > 0) {
            M('order')->where('id='.$id)->save(array('status'=>0)) !== false ? $this->success('删除成功!') : $this->error('删除失败!');
        }
    }
}