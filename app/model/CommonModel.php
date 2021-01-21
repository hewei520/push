<?php
/**
 * 公共模型
 * User: 威少
 * Date: 2021/1/20
 * Time: 11:35
 */

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

class CommonModel extends Model
{
    use SoftDelete;
    // 定义时间戳字段名
    protected $createTime = "created_at";
    protected $updateTime = "updated_at";
    protected $deleteTime = "deleted_at";
}