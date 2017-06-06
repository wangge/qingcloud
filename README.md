# qingcloud
Operating qingcloud server, switch machine and testing status


# 安装
composer require wangge/qingcloud dev-master

#使用
<?php
$access_key_id = 在青云申请的access_key_id
$secret_access_key = 在青云申请的$secret_access_key
$zone = 青云机房区域 如：北京3区-A(pek3a)

$s = new \Qing\Server\ApiInstruct($access_key_id ,$secret_access_key ,$zone);

$response = $s->describeInstances(['status.0'=>'pending']);

array (size=3)
  'status' => boolean true
  'msg' => string '请求成功' (length=12)
  'data' =>
    array (size=4)
      'action' => string 'DescribeInstancesResponse' (length=25)
      'instance_set' =>// 青云返回的主要数据
        array (size=0)
          empty
      'total_count' => int 0 //总条数
      'ret_code' => int 0 状态码

# data 部分的详细 请见:https://docs.qingcloud.com/api/common/action.html