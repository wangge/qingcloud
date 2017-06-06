# qingcloud
Operating qingcloud server, switch machine and testing status


# 安装  composer require wangge/qingcloud dev-master
<?php
$access_key_id =
$secret_access_key=
$zone =
$s = new \Qing\Server\ApiInstruct($access_key_id ,$secret_access_key ,$zone);
$response = $s->describeInstances(['status.0'=>'pending']);

array (size=3)
  'status' => boolean true
  'msg' => string '请求成功' (length=12)
  'data' =>
    array (size=4)
      'action' => string 'DescribeInstancesResponse' (length=25)
      'instance_set' =>
        array (size=0)
          empty
      'total_count' => int 0
      'ret_code' => int 0