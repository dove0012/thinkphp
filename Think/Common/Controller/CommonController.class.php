<?php
namespace Common\Controller;
use Think;
use Think\Controller;
use Org\Util\Rbac;

class CommonController extends Controller {

    public function _initialize() {
        // 用户权限检查
        if (strtolower(MODULE_NAME) == 'home' && $_REQUEST['session_id']) {
            $session_id = $_POST['session_id'] ? $_POST['session_id'] : $_GET['session_id'];
            $res = session_id($session_id);
            session_start();
            if (!session(C('USER_AUTH_KEY'))) {
                $data = M('Member')->where("sessionid='{$session_id}'")->find();
                if (!$data) {
                    $this->error('没有权限');
                }
                session(C('USER_AUTH_KEY'), $data['id']);
            }
        } else {
            if (C('USER_AUTH_ON') && !in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
                if (!RBAC::AccessDecision()) {
                    //检查认证识别号
                    if (!session(C('USER_AUTH_KEY'))) {
                        //跳转到认证网关
                        redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    $this->error('没有权限');
                }
            }
        }
    }
    
    public function index() {
        $this->display();
    }
}