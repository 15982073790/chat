<?php

/**
 * 获取已上传的文件列表
 */
include "Uploader.class.php";

/* 判断类型 */
switch ($_GET['action']) {
    /* 列出文件 */
    case 'listvideo':
        $allowFiles = $CONFIG['videoAllowFiles'];
        $listSize = $CONFIG['fileManagerListSize'];
//        $path = $CONFIG['fileManagerListPath'];
        break;
    /* 列出文件 */
    case 'listfile':
        $allowFiles = $CONFIG['fileManagerAllowFiles'];
        $listSize = $CONFIG['fileManagerListSize'];
//        $path = $CONFIG['fileManagerListPath'];
        break;
    /* 列出图片 */
    case 'listimage':
    default:
        $allowFiles = $CONFIG['imageManagerAllowFiles'];
        $listSize = $CONFIG['imageManagerListSize'];
//        $path = $CONFIG['imageManagerListPath'];
}
$allowFiles = explode('.', join("", $allowFiles));
if (!$allowFiles[0]) {
    unset($allowFiles[0]);
}

/* 获取参数 */
$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
$end = $start + $size;

/* 获取我的列表 */
$where = [];
$service_id = input('param.service_id', 0);
if ($service_id) {
    $where['service_id'] = $service_id;
}
$admin_id = input('param.admin_id', 0);
if ($admin_id) {
    $where['admin_id'] = $admin_id;
}
$attachmentData = \think\Db::name('attachment_data');
$data = $attachmentData->where($where)->whereIn('fileext', $allowFiles)->order('inputtime desc')->paginate(100);
$files = [];
if ($data) {
    foreach ($data as $t) {
        $t['filename'] = strstr($t['filename'], '.', true);
        $files[] = array(
            'url' => $t['url'],
            'name' => $t['filename'],
            'original' => $t['filename'],
            'mtime' => $t['inputtime']
        );
    }
}

if (!count($files)) {
    return json_encode(array(
        "state" => "no match file",
        "list" => array(),
        "start" => $start,
        "total" => count($files)
    ));
}

/* 返回数据 */
$result = json_encode(array(
    "state" => "SUCCESS",
    "list" => $files,
    "start" => $start,
    "total" => count($files)
));

return $result;