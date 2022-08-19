<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/3/19
 * Time: 9:25
 */

namespace app\platform\controller;


use app\common\lib\cloud\CloudUpdate;
use think\Exception;

class Update extends Base
{
    protected $noNeedLogin = [];

    /**
     * 获取更新信息
     */
    public function index()
    {
        $update = new CloudUpdate();
        $updateinfo = $update->info();
        $this->assign('data', $updateinfo['data']);
        return $this->fetch();
    }

    /**
     * 执行更新操作
     */
    public function update()
    {
        if ($this->request->isPost()) {
            $update = new CloudUpdate();
            try {
                $result = $update->update();
                return [
                    'code' => 0,
                    'msg' => '更新成功。',
                    'data' => $result,
                ];
            } catch (Exception $exception) {
                return [
                    'code' => 1,
                    'msg' => $exception->getMessage(),
                ];
            }
        }
    }
}