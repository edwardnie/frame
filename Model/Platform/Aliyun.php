<?php
/**
 * 同步到阿里云的OSS
 */
require_once ROOT_DIR . '/Lib/aliyun/aliyun.php';
use Aliyun\OSS\OSSClient;

class Platform_Aliyun {


    private static $_singletonObjects = null;
    private $_client;
    private $_config;

    public function  __construct() {
        $this->_config = Common::getConfig('aliyun');
        $this->_client = OSSClient::factory(array(
            'AccessKeyId' => $this->_config['AccessKeyId'],
            'AccessKeySecret' => $this->_config['AccessKeySecret'],
        ));
    }

    /**
     * @param bool $exit
     * @return null|Platform_WebChat
     */
    public static function getInstance() {
        if (self::$_singletonObjects == null) {
            self::$_singletonObjects = new self();
        }
        return self::$_singletonObjects;
    }


    /**
     * 上传文件到阿里云
     * @param $fileName
     * @param $aliName
     */
    public function uploadObject($fileName, $aliName) {
        $this->_client->putObject(array(
            'Bucket' => $this->_config['Bucket'],
            'Key' => $aliName,
            'Content' => fopen($fileName, 'r'),
            'ContentLength' => filesize($fileName),
        ));
    }

    /**
     * 删除阿里云上的文件
     * @param $object
     */
    public function deleteObject($object) {
        $this->_client->deleteObject(array(
            'Bucket' => $this->_config['Bucket'],
            'Key' => $object,
        ));
    }

    /**
     * 获取阿里云列表
     * @param string $bucket
     * @return array
     */
    public function getObjectList($bucket = 'odfang') {
        $object = array();
        $objectListing = $this->_client->listObjects(array(
            'Bucket' => $bucket,
        ));

        foreach ($objectListing->getObjectSummarys() as $objectSummary) {
            $object[] = $objectSummary->getKey();
        }
        return $object;
    }
} 