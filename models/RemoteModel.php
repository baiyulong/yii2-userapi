<?php
namespace uapi\userapi\models;

use Yii;
use yii\helpers\StringHelper;
use yii\base\Object;

class RemoteModel extends Object
{
    protected $_modelName;
    public static $timeout = 10;
    protected $_config;
    protected $_debug;
    protected $_header = array();

    public function init()
    {
        $params = require_once(__DIR__ . '/../config/params.php');
        $this->_modelName = StringHelper::basename(get_called_class());
        $this->_config = $params['api'];
        $this->_debug = $params['apiDebug'];
        $authHeader = isset($params['requestAuth']['header']) ? $params['requestAuth']['header'] : false;
        if ($authHeader) {
            foreach ($authHeader as $k => $v) {
                $this->_header[] = "{$k}: {$v}";
            }
        }
        parent::init();
    }
    /**
     * 接管所有对Model方法的调用，直接转至远程API
     */
    public function __call($name, $params)
    {
        return $this->requestApi($name, current($params));
    }

    /**
     * 请求远程API
     * @param type $action
     * @param type $params
     * @param type $assoc
     * @return type
     */
    protected function requestApi($action, $params, $assoc = true)
    {
        $clientName = strtolower($this->_modelName) . '::' . $action;
        $result = json_decode($this->send($params, $clientName), $assoc);
        if (method_exists($this, '_filterResultData')) {
            $result = $this->_filterResultData($result);
        }
        return $result;
    }

    /**
     * 格式化API输出数据
     * @param type $rawData 数据
     * @param type $errorCode 错误编码
     * @param type $errorMsg 错误提示消息
     * @return array 格式化后的数据
     */
    protected function _formatApiData($rawData, $errorCode = 0, $errorMsg = '')
    {
        $data['errorCode'] = 0;
        $data['errorMsg'] = '';
        $data['data'] = $rawData;
        if ($errorCode) {
            if (is_array($errorCode)) {
                $data['errorCode'] = $errorCode['errorCode'];
                $data['errorMsg'] = $errorCode['errorMsg'];
            } else {
                $data['errorCode'] = $errorCode;
                $data['errorMsg'] = $errorMsg;
            }
        }
        return $data;
    }
    /**
     * 取得毫秒级时间戳
     *
     * @return mixed This is the return value description
     *
     */
    private function _getMicroTime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (double) $usec + (double) $sec;
    }
    /**
     * 发送请求并返回数据
     *
     * @param array $arguments 请求的数据
     * @param array|string $clientInfo 接口信息或接口名
     * @return string 返回接口返回的数据
     *
     */
    public function send($arguments, $clientInfo)
    {
        if ($this->_debug) {
            $startTime = $this->_getmicrotime();
        }
        $apiConfig = array();
        if (is_string($clientInfo)) {
            $clientInfo = $this->_config[$clientInfo];
        }
        if (!isset($clientInfo['url'])) {
            throw new \yii\base\Exception('调用的接口无配置信息');
        }
        $apiConfig['url'] = $clientInfo['url'];
        $apiConfig['method'] = isset($clientInfo['method']) ? $clientInfo['method'] : 'post';
        $apiConfig['charset'] = isset($clientInfo['charset']) ? $clientInfo['charset'] : 'utf-8';
        $returnValue = false;
        $requestUrl = $apiConfig['url'];
        //post get 分别处理
        if ($apiConfig['method'] == 'get') {
            //请求的URL处理，如果是GET，需要将参数变成get字串拼进URL
            $concatChar = '?';
            if (strpos($requestUrl, '?')) {
                $concatChar = '&';
            }
            if (is_array($arguments)) {
                $arguments = http_build_query($arguments);
            }
            $requestUrl = $requestUrl . $concatChar . $arguments;
            $returnValue = self::get($requestUrl, $apiConfig['charset'], $this->_header);
        } else {
            $returnValue = self::post($requestUrl, $arguments, $apiConfig['charset'], $this->_header);
        }
        if ($this->_debug) {
            $endTime = $this->_getmicrotime();
            $costTime = $endTime - $startTime;
            $new_line = '
';
            $str = '开始时间:' . date('Y-m-d H:i:s', $startTime) . $new_line;
            $str .= '完成时间:' . date('Y-m-d H:i:s', $endTime) . $new_line;
            $str .= '请求URL:' . urldecode($requestUrl) . $new_line;
            $str .= '请求方法:' . $apiConfig['method'] . $new_line;
            $str .= '数据编码:' . $apiConfig['charset'] . $new_line;
            $str .= '花费时间:' . $costTime . '秒' . $new_line;
            $str .= '发送数据:' . json_encode($arguments) . $new_line;
            $str .= '返回信息:' . json_encode($returnValue) . $new_line;
            $str .= $new_line;
            Yii::info($str, 'api');
        }
        return $returnValue;
    }
    /**
     * 直接发送get请求并返回请求结果
     *
     * @param string $url 请求Url
     * @param string $charset 编码
     * @return string 返回内容
     *
     */
    public static function get($url, $charset = 'utf-8', $header = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$timeout);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $returnValue = curl_exec($ch);
        curl_close($ch);
        if ($charset != 'utf-8') {
            $returnValue = iconv($charset, 'utf-8', $returnValue);
        }
        return $returnValue;
    }
    /**
     * 直接发送post请求并返回请求结果
     *
     * @param string $url 请求Url
     * @param array $arguments 参数，数组
     * @param string $charset 编码
     * @return string 返回内容
     *
     */
    public static function post($url, $arguments, $charset = 'utf-8', $header = null)
    {
        if (is_array($arguments)) {
            $postData = http_build_query($arguments);
        } else {
            $postData = $arguments;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$timeout);
        $returnValue = curl_exec($ch);
        curl_close($ch);
        if ($charset != 'utf-8') {
            $returnValue = iconv($charset, 'utf-8', $returnValue);
        }
        return $returnValue;
    }
}