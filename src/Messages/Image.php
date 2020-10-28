<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-21 021
 * Time: 9:36
 */

namespace Xin6841414\WorkWechatRobot\Messages;


use Xin6841414\WorkWechatRobot\Exceptions\InvalidArgumentException;

class Image extends Message
{

    protected  $msgType = 'image';
    protected $md5;
    protected $base64;
    function getMessageType()
    {
        return 'image';
    }

    /**
     * @return array 获取消息主题内容
     */
    public function getBody()
    {
        if (!$this->base64) {
            throw new InvalidArgumentException('图片base64为空！');
        }
        if (!$this->md5) {
            throw new InvalidArgumentException('图片md5值为空！');
        }
        $tmp['base64'] = $this->base64;
        $tmp['md5'] = $this->md5;
        return $tmp;
    }

    /**
     * 设置base64
     * @param $base64
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setBase64($base64)
    {
        //去掉base64头
        if($index = strpos($base64, ';base64,')) {
            $base64 = substr($base64, $index+8);
        }
        if ($base64 != base64_encode(base64_decode($base64))) {
            throw new InvalidArgumentException('图片仅支持base64编码,传参非base64编码');
        }
        $strLength = strlen($base64);
        //计算文件大小 约数，不考虑尾部补位=
        $fileSize =(3/4)*floor($strLength-($strLength/8)*2);
        if ($fileSize > 2*1024*1024) {
            throw new InvalidArgumentException('原图大小不能超过2m');
        }
        $this->base64 = $base64;
        return $this;
    }

    /**
     * 设置md5值(图片转base64前);
     * @param $md5
     * @return $this
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }
}