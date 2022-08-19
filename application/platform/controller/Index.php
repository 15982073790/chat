<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/28
 * Time: 16:33
 */
namespace app\platform\controller;
use app\common\model\Report;

class Index extends Base
{
    protected $noNeedLogin = [];

    public function index()
    {

        $list = Report::with(['business' => function ($query) {
            $query->where(['admin_id' => $this->admin['id']]);
        }])->paginate();
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('reporttype', config('setting.reporttype'));
        $this->assign('list', $list);
        return $this->fetch();
    }

}