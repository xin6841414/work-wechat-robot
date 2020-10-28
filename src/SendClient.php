<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-19 019
 * Time: 13:50.
 */

namespace Xin6841414\WorkWechatRobot;

interface SendClient
{
    public function send($params): array;
}
