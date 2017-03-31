<?php

class Model_UserInfo extends PhalApi_Model_NotORM {

    public function getByUserId($userId) {
        return $this->getORM()
            ->select('*')
            ->where('id = ?', $userId)
            ->fetch();
    }

    public function getByUserIdWithCache($userId) {
        $key = 'userinfo_' . $userId;
        $rs = DI()->cache->get($key);
        if ($rs === NULL) {
            $rs = $this->getByUserId($userId);
            DI()->cache->set($key, $rs, 600);
        }
        return $rs;
    }

    /**
    protected function getTableName($id) {
        return 'user';
    }
    */

    public function getByParam($param) {
        return $this->getORM()
            ->select('*')
            ->where('username = ?', $param['username'])
            ->where('password = ?', $param['password'])
            ->fetch();
    }

    public function getByName($param) {
        return $this->getORM()
            ->select('*')
            ->where('username = ?', $param['username'])
            ->fetch();
    }
}
