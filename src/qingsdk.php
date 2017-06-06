<?php
namespace Qing\Server;
class Qingsdk
{
    public $config = null;
    public $action;
    private $time_stamp;
    private $request_url = "https://api.qingcloud.com/iaas/";
    public function __construct()
    {
        $this->time_stamp = $this->getDate();
    }
    /**
     * 生产签名，将公告参数进行拼接生产签名
     * @return mixed|string
     */
    private function sign()
    {
        $sign_arr = $this->getCommonParam();
        //按参数名进行升序排列
        ksort($sign_arr);

        //构造URL请求
        $url_query = \GuzzleHttp\Psr7\build_query($sign_arr);
        //构造被签名串
        $sign = "GET"."\n"."/iaas/"."\n".$url_query;

        //将API密钥的私钥 ( secret_access_key ) 作为key，生成被签名串的 HMAC-SHA256 或者 HMAC-SHA1 签名
        $sign = hash_hmac('sha256', $sign, $this->config['secret_access_key'], true);
        //将签名进行 Base64 编码
        $sign_b64 = base64_encode($sign);

        //警告 当 Base64 编码后存在空格时，不要对空格进行 URL 编码，而要直接将空格转为 “+”

        //urlencode把空格编码为 '+',rawurlencode把空格编码为 '%20'
        return urlencode($sign_b64);
    }
    /**
     * 获取公共参数
     * @return string
     */
    private function getCommonParam(){
        $param = [
            'access_key_id'=>$this->config['access_key_id'],
            'action'       => $this->action,
            'signature_method' =>'HmacSHA256',
            'signature_version' => 1,
            'time_stamp'  => $this->time_stamp,
            'version'     => 1,
            "zone"        => $this->config['zone']
        ];
        return $param;
    }

    /**
     * 获取UTC+0 时间
     * 这个时间为 UTC 时间，假设您的本地时间为北京时间 UTC+8 ，您需要将其转化为 UTC+0 的时间。
     * @return string
     */
    private function getDate()
    {
        date_default_timezone_set('UTC');//
        return gmdate('Y-m-d').'T'.gmdate('H:i:s').'Z';
    }

    /**
     * http 请求
     * @param $param
     * @param string $method
     * @return array
     */
    public function request($param ,$method="GET")
    {
        $param_common = $this->getCommonParam();
        $param_common = array_merge($param_common,$param);
        $query =  $this->request_url.'?'.\GuzzleHttp\Psr7\build_query($param_common)."&signature=".$this->sign();
        $client = new \GuzzleHttp\Client();
        $res = $client->request($method,$query);
        return $this->response($res);
    }

    /**
     * 处理返回结果
     * @param $res
     * @return array
     */
    private function response($res)
    {

        if($res->getStatusCode()==200){
            $response = json_decode((string)$res->getBody(),true);
            if($response['ret_code']){
                return $this->getErr($response['ret_code']);
            }else{
                return ['status'=>true,'msg'=>'请求成功','data'=>$response];
            }
        }else{
            return ['status'=>false,'msg'=>'请求失败','data'=>''];
        }
    }

    /**
     * 处理错误信息
     * @param $code
     * @return array
     */
    private function getErr($code){
        $msg = '';
        switch($code){

            case '1100':
                $msg = '消息格式错误';
                break;
            case '1200':
                $msg = '身份验证失败';
                break;
            case '1300':
                $msg = '消息已过期';
                break;
            case '1400':
                $msg = '访问被拒绝';
                break;
            case '2100':
                $msg = '找不到资源';
                break;
            case '2400':
                $msg = '余额不足';
                break;
            case '2500':
                $msg = '超过配额';
                break;
            case '5000':
                $msg = '青云内部错误';
                break;
            case '5100':
                $msg = '服务器繁忙';
                break;
            case '5200':
                $msg = '资源不足';
                break;
            case '5300':
                $msg = '服务更新中';
                break;
            default:
                $msg = '其他错误';
                break;

        }
        return ['status'=>false,'msg'=>$msg,'data'=>''];
    }
}