<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class IndexController extends CommonController
{
    public function index()
    {
        $this->assign('navTree', $this->navTree());
        $this->display();
    }
	
    public function sysinfo() {
        /* 系统信息 */
        $gd = gd_info();
        $gdTitle = '';
        foreach ($gd as $k => $v) {
            $gdTitle .= $k . ' = ' . $v . '&#13;';
        }
        $model = M();
        $res = $model->query('select VERSION() as ver');
        $sys_info['os'] = PHP_OS;
        $sys_info['ip'] = $_SERVER['SERVER_ADDR'];
        $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['php_ver'] = PHP_VERSION;
        $sys_info['mysql_ver'] = $res[0]['ver'];
        $sys_info['zlib'] = function_exists('gzclose') ? '是' : '否';
        $sys_info['safe_mode'] = (boolean) ini_get('safe_mode') ? '是' : '否';
        $sys_info['safe_mode_gid'] = (boolean) ini_get('safe_mode_gid') ? '是' : '否';
        $sys_info['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : '获取不了默认时区';
        $sys_info['socket'] = function_exists('fsockopen') ? '是' : '否';
        $sys_info['charset'] = C('DEFAULT_CHARSET');
        $sys_info['gd'] = $gd['GD Version'];
        $sys_info['gdTitle'] = $gdTitle;
        $sys_info['max_filesize'] = ini_get('upload_max_filesize');
        $sys_info['session_cache_expire'] = ini_get('session.cache_expire');
        $sys_info['memory_limit'] = ini_get('memory_limit');
        $sys_info['php_sapi_name'] = php_sapi_name();
        $sys_info['date'] = date("Y年n月j日 H:i:s");
        $sys_info['disk_free_space'] = round((@disk_free_space(".")/(1024*1024)),2).'M';
        $sys_info['think_version'] = THINK_VERSION;
        $this->assign('sys_info', $sys_info);
        $this->display();
    }
    
    public function navTree() {
        $menu['系统管理'] = array(
            array('name' => '管理员管理', 'title' => 'Admin'),
            array('name' => '项目权限管理', 'title' => 'Node'),
            array('name' => '角色管理', 'title' => 'Role'),
        );
		$menu['分类管理'] = array(
            array('name' => '分类列表', 'title' => 'Category'),
        );
		$menu['会员管理'] = array(
            array('name' => '会员列表', 'title' => 'Member'),
            array('name' => '游神全部申请列表', 'title' => 'God'),
            array('name' => '游神待审申请列表', 'title' => 'God/index/type/0'),
            array('name' => '会员订单列表', 'title' => 'Order'),
        );

        $accessList = \Org\Util\Rbac::getAccessList(session(C('USER_AUTH_KEY')));
        foreach ($menu as $k => $v) {
            $temp = $temp2 = array();
            $temp['text'] = $k;
            $exists = 0;
            foreach ($v as $y) {
                $temp2['text'] = $y['name'];
                $temp2['attributes'] = array(
                    'url' => $y['title'],
                );
                if (array_key_exists(strtoupper($y['title']), $accessList['ADMIN']) or session('administrator') === TRUE) {
                    $temp['children'][] = $temp2;
                    $exists = 1;
                }
                if($y['title'] == 'Node'){
                    $exists = session('administrator') === TRUE ? 1 : 0;
                }
            }
            if($exists) $value[] = $temp;
        }
        return json_encode($value);
    }
}