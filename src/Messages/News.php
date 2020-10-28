<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-21 021
 * Time: 14:59
 */

namespace Xin6841414\WorkWechatRobot\Messages;


use Xin6841414\WorkWechatRobot\Exceptions\InvalidArgumentException;

class News extends Message
{

    protected $msgType = 'news';

    protected $articles = [];

    function getMessageType()
    {
       return $this->msgType;
    }

    public function getBody()
    {
        $content['articles'] = $this->articles;
        if (empty($content)) {
            throw new InvalidArgumentException('图文消息至少需要一条图文');
        }
        return $content;
    }

    /**
     * 添加图文
     * @param $title
     * @param $url
     * @param string $description
     * @param string $picUrl
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addArticle($title, $url, $description = '', $picUrl = '')
    {
        if(count($this->articles) >=8) {
            throw new InvalidArgumentException('图文消息最大支持8条图文');
        }
        $content = [
          'title' => $title,
          'description' => $description,
          'url' => $url,
          'picurl' => $picUrl
        ];
        $this->articles[] = $content;
        return $this;
    }
}