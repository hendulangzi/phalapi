<?php

class Domain_UserInfo {

    public function getBaseInfo($userId) {
        $rs = array();

        $userId = intval($userId);
        if ($userId <= 0) {
            return $rs;
        }

		// 版本1：简单的获取
        $model = new Model_UserInfo();
        $rs = $model->getByUserId($userId);

		// 版本2：使用单点缓存/多级缓存 (应该移至Model层中)
		/**
        $model = new Model_User();
        $rs = $model->getByUserIdWithCache($userId);
		*/

		// 版本3：缓存 + 代理
		/**
		$query = new PhalApi_ModelQuery();
		$query->id = $userId;
		$modelProxy = new ModelProxy_UserBaseInfo();
		$rs = $modelProxy->getData($query);
		*/

        return $rs;
    }

    public function getByUserName($param){
        $mode = new Model_UserInfo();
        return $mode->getByName($param);
    }

    public function getSignIn($param){
        $mode = new Model_UserInfo();
        return $mode->getByParam($param);
    }

    public function insert($param){
        $mode = new Model_UserInfo();
        return $mode->insert($param);
    }
}
