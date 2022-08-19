<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/2/14
 * Time: 15:33
 */
namespace app\platform\controller;

class Cache extends Base
{
    protected $noNeedLogin = [];

    public function index()
    {
        if ($this->request->isPost()) {
            $tool = new \app\platform\model\Cache();
            $tool->setCache($this->request->param('cache'));
            $tool->setTemp($this->request->param('temp'));
            $tool->setLog($this->request->param('log'));
            return $tool->clear();
        } else {
            return $this->fetch('index');
        }
    }
}