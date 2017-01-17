<?php
namespace Common\Controller;
use Think\Controller;
class PublicController extends Controller
{
    public function getArea($id = 0) {
        if (strtolower($id) == 'all') {
            $where = '';
        } else if ($id == 0) {
            $where['pid'] = '0';
        } else if (is_numeric($id) && $id > 0) {
            $where['pid'] = "{$id}";
        }
        $data = M('Area')->where($where)->select();
        if ($data) {
            $this->success($data);
        } else {
            $this->error('获取数据失败!');
        }
    }
}