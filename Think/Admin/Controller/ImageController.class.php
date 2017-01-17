<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class ImageController extends CommonController
{
    public function _initialize() {
        $this->member_id = session('Image_member_id');
        $this->Image_type = session('Image_type');
    }
    
    public function index($member_id, $type=NULL) {
        if ($member_id > 0) {
            session('Image_member_id',$member_id);
            session('Image_type',$type);
        }
        $this->display();
    }
    
    public function take() {
		if ($this->member_id > 0) $data['member_id'] = $this->member_id;
		if ($this->Image_type) $data['type'] = $this->Image_type;
		exit(json_encode(D('Admin/Image')->take($data)));
    }
    
    public function add() {
        if ($this->member_id > 0 && $this->Image_type) {
			if ($_FILES) {
				$res = uploadImage();
				if ($res['error'] == 1) {
					$this->error($res['msg']);
				}
				$_POST['member_id'] = $this->member_id;
				$_POST['type'] = $this->Image_type;
				$_POST['path'] = $res['fullpath'];
				$model = D('Image');
				if (!$model->create()) {
					$this->error($model->getError());
				}
				if ($model->add()) {
					 $this->success('添加成功!');
				} else {
					@unlink(ROOT_PATH.$_POST['path']);
					$this->error('添加失败!');
				}
			} else {
				$this->display();
			}
		} else {
			$this->error('参数丢失,请关闭此窗口重新打开!');
		}
    }
    
    public function edit($id=0){
		if ($this->member_id > 0 && $this->Image_type) {
			if ($_FILES) {
				$res = uploadImage();
				if ($res['error'] == 1) {
					$this->error($res['msg']);
				}
				$_POST['path'] = $res['fullpath'];
				$model = D('Image');
				if (!$model->create()) {
					$this->error($model->getError());
				}
				$model->where(array('id'=>session('edit_id')))->save() ? $this->success('编辑成功!') : $this->error('编辑失败!');
			} else if ($id > 0) {
				session('edit_id', $id);
				$this->assign('data', D('Image')->where(array('id'=>$id))->find());
				$this->display('add');
			}
		} else {
			$this->error('参数丢失,请关闭此窗口重新打开!');
		}
    }
    
    public function del($id) {
		if ($this->member_id > 0 && $this->Image_type) {
			if ($id > 0) {
				$model = D('Image');
				$path = $model->where('id='.$id)->getField('path');
				if ($path && $model->where('id='.$id)->delete() !== false) {
					@unlink(ROOT_PATH.$path);
					$this->success('删除成功!');
				} else {
					$this->error('删除失败!');
				}
			}
		} else {
			$this->error('参数丢失,请关闭此窗口重新打开!');
		}
    }
}