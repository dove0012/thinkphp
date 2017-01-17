<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class CategoryController extends CommonController
{
	public function _initialize() {
        $this->model = D('Category');
		if (!$_POST['sort']) unset($_POST['sort']);
    }
	
    public function take() {
        exit(json_encode($this->model->cat_list(0, 0, false)));
    }
	
    public function del($id){
        if($this->model->where('pid='.$id)->select()){
            $this->error('删除失败,当前分类有子分类,请先删除子分类!');
        }
        $this->model->where('id ='.$id)->delete() ? $this->success('删除成功!') : $this->error('删除失败!');
    }
    
    public function add($id=0){
        if($_POST){
            if (!$this->model->create()) {
                $this->error($this->model->getError());
            }
            $this->model->add() ? $this->success('添加成功!') : $this->error('添加失败!');
        }else{
			$id = $id > 0 ? $id : 0;
            $this->assign('cat_list', $this->model->cat_list(0, $id));
            $this->display();
        }
    }
    
    public function edit($id=0){
        if ($_POST) {
            $_POST['id'] = session(__URL__ . '_edit_id');
            
            /* 判断上级目录是否合法 */
            $children = array_keys($this->model->cat_list($_POST['id'], 0, false));     // 获得当前分类的所有下级分类
            if (in_array($_POST['pid'], $children)){
                /* 选定的父类是当前分类或当前分类的下级分类 */
               $this->error('编辑失败,上级分类不合法!');
            }
            
            if (!$this->model->create()) {
                $this->error($this->model->getError());
            }
            $res = $this->model->save();
            session(__URL__ . '_edit_id', null);
            $res ? $this->success('编辑成功!') : $this->error('编辑失败!');
        } else {
            session(__URL__ . '_edit_id', $id); //记录要编辑的ID
            $this->assign('res', $this->model->where('id=' . $id)->find());
            $this->assign('cat_list', $this->model->cat_list(0, $this->model->where('id='.$id)->getField('pid')));
            $this->display('add');
        }
    }
	
	public function edit_value() {
        $value = $_POST['value'] == 1 ? 1 : 0;
        $data[$_POST['type']] = $value;
        $this->model->where('id=' . $_POST['id'])->save($data) ? $this->success() : $this->error();
    }
}