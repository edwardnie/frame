<?php

//所有controller的基类
class Framework_BaseController {

    /*
     * 传入的参数
     * @var array
     * */
    protected $inputDatas;

    /*
     * 注册了的变量
     * @var        array(
     *                         {key:string}:mixed
     *                 )
     **/
    private $_assignedVariable = array();

    public $locale = 'zh';

    public function __construct() {
        if (get_magic_quotes_gpc()) {
            //解除$_GET、$_POST 转义
            Common::prepareGPCData($_GET);
            Common::prepareGPCData($_POST);
        }
        $this->locale = Common::getLocale();
        $this->inputDatas = array_merge($_GET, $_POST);
        //打开输出缓冲
        ob_start();
    }

    public function __destruct() {
        //送出缓冲区的内容
        ob_flush();
    }

    /*
     * 注册变量
     * @param string $key 键
     * @param string $value 值
     * */
    protected function assignVariable($key, $value) {
        $this->_assignedVariable[$key] = $value;
    }

    /*
     * 显示页面
     * @param string $pagePath 页面地址
     * */
    protected function disPlayPage($pagePath) {
        //将变量从数组中导入到当前的符号表中
        if(!strstr($pagePath,'.php')){
            $pagePath .= '.php';
        }
        extract($this->_assignedVariable);
        include VIEW_DIR . $pagePath;
    }

    /**
     * 输出Json字符串
     * @param array $data
     * @param int $option
     */
    public function outputJson($data, $option = JSON_FORCE_OBJECT) {
        echo json_encode($data, $option);
    }

    public function redirect($url){
        header("Location: $url");
        exit();
    }

}
