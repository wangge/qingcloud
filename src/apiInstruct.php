<?php
namespace Qing\Server;
use Qing\Server\Qingsdk;
class ApiInstruct
{
    public $server;
    private $err_code = 1100;
    public function __construct()
    {
        $this->server = new Qingsdk();
        $this->setConfig();
    }
    /**
     * 获取一个或多个主机
     * @param $param
     * @return array
     */
    public function describeInstances($param){
       try{
           $this->validDesc($param);
       }catch(\Exception $e){
           return $this->formatErr($e);
       }
        $this->server->action = 'DescribeInstances';
        return $this->server->request($param);
    }

    /**
     * 验证describeInstances参数
     * @param $param
     * @throws \Exception
     */
    private function validDesc($param){
       /* if(!isset($param['zone'])||empty($param['zone'])){
            throw new \Exception('zone参数必传',$this->err_code);
        }*/
    }

    /**
     * 创建指定配置，指定数量的主机
     * @param $param
     * @return array
     */
    public function runInstances($param){

        try{
            $this->validRun($param);
        }catch(\Exception $e){
            return $this->formatErr($e);
        }
        $this->server->action = 'RunInstances';
        return $this->server->request($param);
    }

    /**
     * 验证runInstances必传参数
     * @param $param
     * @throws \Exception
     */
    private function validRun($param)
    {
        if(!isset($param['image_id'])||empty($param['image_id'])){
            throw new \Exception('image_id,映像ID参数必传',$this->err_code);
        }
        if(!isset($param['login_mode'])||empty($param['login_mode'])){
            throw new \Exception('login_mode,登录方式必传',$this->err_code);
        }
    }
    /**
     * 销毁一台或多台主机
     * @param $param
     * @return array
     */
    public function terminateInstances($param){
        $this->validCommon($param);
        $this->server->action = 'TerminateInstances';
        return $this->server->request($param);
    }

    private function validCommon($param){

        if (count($param) == 0) {
            throw new \Exception('参数不能为空', $this->err_code);
        }
        if (strpos(join("", array_keys($param)), 'instances') === false) {
            throw new \Exception('主机 ID 列表必填', $this->err_code);
        }
        foreach (array_keys($param) as $key) {
            if ($key != 'zone' && empty($param[$key])) {
                throw new \Exception($key . '参数不能为空', $this->err_code);
            }
        }
    }
    /**
     * 启动一台或多台关闭状态的主机
     * @param $param
     * @return array
     */
    public function startInstances($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'StartInstances';
        return $this->server->request($param);
    }

    /**
     * 关闭一台或多台运行状态的主机
     * @param $param
     * @return array
     */
    public function stopInstances($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'StopInstances';
        return $this->server->request($param);
    }

    /**
     * 重启一台或多台运行状态的主机
     * @param $param
     * @return array
     */
    public function restartInstances($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'RestartInstances';
        return $this->server->request($param);
    }

    /**
     * 将一台或多台主机的系统盘重置到初始状态。 被重置的主机必须处于运行（ running ）或关闭（ stopped ）状态
     * @param $param
     * @return array|string
     */
    public function resetInstances($param)
    {

        try {
            $this->validCommon($param);
                if (!isset($param['login_mode']) || empty($param['login_mode'])){
                    throw new \Exception('login_mode参数不能为空', $this->err_code);
                }
        }catch(\Exception $e){
            return $this->formatErr($e);
        }
        $this->server->action = 'ResetInstances';
        return $this->server->request($param);
    }

    /**
     * 修改主机配置，包括 CPU 和内存。主机状态必须是关闭的 stopped ，不然会返回错误。
     * 如果使用预设的 instance_type ，参数中就不需再指定 CPU 或内存，配置列表请参考 Instance Types 。
     * 如果参数中没有指定 instance_type ，则必须指定 cpu 和 memory。
     * 如果参数中既指定 instance_type ，又指定了 cpu 和 memory ， 则以指定的 cpu 和 memory 为准。
     * @param $param
     * @return array
     */
    public function resizeInstances($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'ResizeInstances';
        return $this->server->request($param);
    }

    /**
     * 修改一台主机的名称和描述
     * 修改时不受主机状态限制。一次只能修改一台主机
     * @param $param
     * @return array
     */
    public function modifyInstanceAttributes($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'ModifyInstanceAttributes';
        return $this->server->request($param);
    }

    /**
     * 获取区域支持的主机类型
     * @param $param
     * @return array
     */
    public function describeInstanceTypes($param)
    {
        $this->server->action = 'DescribeInstanceTypes';
        return $this->server->request($param);
    }

    /**
     * 为指定的主机创建远程桌面代理
     * @param $param
     * @return array
     */
    public function createBrokers($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'CreateBrokers';
        return $this->server->request($param);
    }

    /**
     * 删除指定主机的远程桌面代理
     * @param $param
     * @return array
     */
    public function deleteBrokers($param)
    {
        try {
            $this->validCommon($param);
        }catch(\Exception $e){

            return $this->formatErr($e);
        }
        $this->server->action = 'DeleteBrokers';
        return $this->server->request($param);
    }
    private function formatErr($e)
    {
        return ['ret_code'=>$e->getCode(),'message'=>$e->getMessage()];
    }
    private function setConfig()
    {
        if(file_exists(__DIR__.'/../../../../config//qingcloud.php'))
        {
            $this->server->config = require_once(__DIR__.'/../../../../config//qingcloud.php'));
        }else{
            $this->server->config = require_once(__DIR__.'/../config/config.php');
        }
    }
}