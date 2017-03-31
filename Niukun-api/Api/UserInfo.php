<?php

/**
 * 用户信息类
 */
class Api_UserInfo extends PhalApi_Api {

    public function getRules() {
        return array(
            'getBaseInfo' => array(
                'userId' => array('name' => 'user_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            ),
            'signIn' => array(
                'userName' => array('name' => 'userName', 'type' => 'string', 'min' => 3, 'max' => 20,'require' => true, 'desc' => '用户名称'),
                'password' => array('name' => 'password', 'type' => 'string', 'min' => 1, 'max' => 20, 'require' => true, 'desc' => '密码'),
            ),
            'reg' => array(
                'userName' => array('name' => 'userName', 'type' => 'string', 'min' => 3, 'max' => 20, 'require' => true, 'desc' => '用户名称'),
                'password' => array('name' => 'password', 'type' => 'string', 'min' => 1, 'max' => 20, 'require' => true, 'desc' => '密码'),
                'smscode' => array('name' => 'smscode', 'type' => 'int', 'require' => true, 'desc' => '注册码'),
                'regcode' => array('name' => 'regcode', 'type' => 'int', 'require' => false, 'desc' => '邀请码'),
                'pid' => array('name' => 'pid', 'type' => 'int', 'require' => false, 'desc' => '邀请人id'),
            ),
            'upload' => array(
                'id' => array('name' => 'id', 'type' => 'string', 'min' => 1, 'max' => 20, 'require' => true, 'desc' => '用户id'),
                'base64encode' => array('name' => 'base64encode', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '图片base64信息'),
                'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '手机号'),
            ),
        );
    }

    /**
     * 获取用户基本信息
     * @desc 用于获取单个用户基本信息
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return object info 用户信息对象
     * @return int info.id 用户ID
     * @return string info.name 用户名字
     * @return string info.note 用户来源
     * @return string msg 提示信息
     */
    public function getBaseInfo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_User();
        $info = $domain->getBaseInfo($this->userId);

        if (empty($info)) {
            DI()->logger->debug('user not found', $this->userId);

            $rs['code'] = 1;
            $rs['msg'] = T('user not exists');
            return $rs;
        }

        $rs['info'] = $info;

        return $rs;
    }
    /**
     * 登陆
     * @desc 用于登陆用户
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return object info 用户信息对象
     * @return int info.id 用户ID
     * @return string info.namename 用户名字
     * @return string info.password 密码
     * @return string msg 提示信息
     */
    public function signIn(){
        $rs = array('code' => 0, 'msg' => '登陆成功', 'info' => array());
        $domain = new Domain_UserInfo();
        $data = array(
            'username'=>$this->userName,
            'password'=>$this->password
        );
        $info = $domain->getSignIn($data);
        if(empty($info)){
            $rs['code'] = 1;
            $rs['msg'] = '用户名或密码错误';
        }
        $rs['info'] = $info;
        return $rs;
    }
    /**
     * 注册
     * @desc 用于注册用户
     * @return int code 操作码，0表示成功， 1表示失败
     * @return object info 用户信息对象
     * @return int info.id 用户ID
     * @return string info.namename 用户名字
     * @return string info.password 密码
     * @return string info.regcode 注册吗
     * @return string msg 提示信息
     */
    public function reg(){
        $rs = array('code' => 0, 'msg' => '注册成功', 'info' => array());
        $domain = new Domain_UserInfo();
        $model = new Model_UserInfo();
        $data = array(
            'username'=>$this->userName,
            'password'=>$this->password,
            'regcode'=>$this->regcode,
            'pid'=>$this->pid
        );
        $info = $domain->getByUserName($data);
        if(!empty($info)){
            $rs['code'] = 1;
            $rs['msg'] = '用户已经存在';
            return $rs;
        }
        $id = $model->insert($data);
        if(empty($id)){
            $rs['code'] = 1;
            $rs['msg'] = '用户名或密码错误';
        }
        $data['id'] = $id;
        $rs['info'] = $data;
        return $rs;
    }
    /**
     * 发送短信
     * @desc 用于发送短信
     * @return int code 操作码，0表示成功， 1表示失败
     * @return string msg 提示信息
     */
    public function sendMsg(){

    }
    /**
     * 图片上传
     * @desc 图片上传
     * @return int code 操作码，0表示成功， 1表示失败
     * @return string msg 提示信息
     */
    public function upload(){
        DI()->logger->info("base64encode====".$this->base64encode);
        $rs = array('code' => 0, 'msg' => '注册成功', 'info' => array());
//        $img = base64_decode($this->base64encode);
//        $a = file_put_contents('./test.jpg', $img);//返回的是字节数
//        $rs['info'] = $a;
        $model = new Model_Img();
        $model->insert(array(
            'img'=>$this->base64encode,
            'uid'=>$this->id,
            'mobile'=>$this->mobile
        ));
        return $rs;
    }
}
