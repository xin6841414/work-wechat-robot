<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-19 019
 * Time: 17:10.
 */

namespace Xin6841414\WorkWechatRobot\Messages;

use Xin6841414\WorkWechatRobot\Exceptions\InvalidArgumentException;

class Markdown extends Message
{
    protected $msgType = 'markdown';
    protected $content;
    protected $canContentAt = true;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->configNotify($config);
    }

    public function getMessageType()
    {
        return $this->msgType = 'markdown';
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function sendAt($type, $list = [], $atAll = false)
    {
        return $this;
    }

    /**
     * @return array 获取消息主题内容
     */
    public function getBody()
    {
        $tmp['content'] = $this->content;
        $tmp['mentioned_list'] = $this->mentionedList;
        $tmp['mentioned_mobile_list'] = $this->mentionedMobileList;

        return array_filter($tmp);
    }

    /**
     * 转标题.
     *
     * @param string $content 内容
     * @param int    $level   支持1-6级标题
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function setTitleContent($content, $level = 1)
    {
        if (!in_array($level, [1, 2, 3, 4, 5, 6])) {
            throw new InvalidArgumentException('标题支持1-6级标题');
        }
        $content = ltrim($content);

        return str_pad(' '.$content, $level + 1 + strlen($content), '#', STR_PAD_LEFT);
    }

    /**
     * 内容加粗.
     *
     * @param string $content
     *
     * @return string
     */
    public function setBoldContent($content)
    {
        return '**'.$content.'**';
    }

    /**
     * 添加换行符.
     *
     * @return string
     */
    public function addWrap()
    {
        return PHP_EOL;
    }

    /**
     * 转链接.
     *
     * @param $content
     * @param $url
     *
     * @return string
     */
    public function setUrlContent($content, $url)
    {
        return '['.$content.']('.$url.')';
    }

    /**
     * 转代码段， 不支持跨行.
     *
     * @param $content
     *
     * @return string
     */
    public function setOneLineCodeContent($content)
    {
        $content = str_replace('\n', '', $content);

        return '`'.$content.'`';
    }

    /**
     * 转引用.
     *
     * @param $content
     *
     * @return string
     */
    public function setQuoteContent($content)
    {
        return '> '.ltrim($content);
    }

    /**
     * 设置字体颜色.
     *
     * @param string $content 内容
     * @param int    $color   颜色， 仅支持绿(1),灰(2),橙红(3)
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function setTextColor($content, $color = 1)
    {
        if (!in_array($color, [1, 2, 3])) {
            throw new InvalidArgumentException('字体颜色仅支持绿(1),灰(2),橙红(3)');
        }
        $colors = [
            1 => 'info',
            2 => 'comment',
            3 => 'warning',
        ];

        return '<font color="'.$colors[$color].'">'.$content.'</font>';
    }
}
