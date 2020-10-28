<?php

namespace Xin6841414\WorkWechatRobot\Tests;

use PHPUnit\Framework\TestCase;
use Xin6841414\WorkWechatRobot\Exceptions\InvalidArgumentException;
use Xin6841414\WorkWechatRobot\Robot;

/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-27 027
 * Time: 9:59.
 */
class RobotTest extends TestCase
{
    protected $robot;
    protected $config;

    public function setUp()
    {
        $config = [
            'default' => [
                'enabled'         => true,
                'key'             => 'mock_key',
                'notify_user_ids' => '',
                'notify_mobiles'  => '',
            ],
            'noKey' => [
                'enabled'         => true,
                'key'             => '',
                'notify_user_ids' => '',
                'notify_mobiles'  => '',
            ],
       ];
        $this->config = $config;
        $this->robot = new Robot($config);
    }

    public function testWith()
    {
        $this->assertInstanceOf('Xin6841414\\WorkWechatRobot\\Robot', $this->robot->with());
    }

    public function testWithWithInvalidRobot()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('未知的robot配置名称【foo】');
        $this->robot->with('foo');
        $this->fail('Failed to assert with throw exception with invalid argument.');
    }

    public function testWithWithInvalidKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('【noKey】机器人的key不能为空');
        $this->robot->with('noKey');
        $this->fail('Failed to assert with throw exception with invalid argument.');
    }

    public function testSetMessageWithInvalidMessage()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('不允许的消息类型【foo】');
        $this->robot->setMessage('foo');
        $this->fail('Failed to assert with throw exception with invalid argument.');
    }

    public function testGetMessage()
    {
        $this->assertInstanceOf('Xin6841414\\WorkWechatRobot\\Messages\\Text', $this->robot->with('default')->setMessage('text')->getMessage());
        $this->assertInstanceOf('Xin6841414\\WorkWechatRobot\\Messages\\Markdown', $this->robot->with('default')->setMessage('markdown')->getMessage());
        $this->assertInstanceOf('Xin6841414\\WorkWechatRobot\\Messages\\Image', $this->robot->with('default')->setMessage('image')->getMessage());
        $this->assertInstanceOf('Xin6841414\\WorkWechatRobot\\Messages\\News', $this->robot->with('default')->setMessage('news')->getMessage());
        $this->assertInstanceOf('Xin6841414\\WorkWechatRobot\\Messages\\File', $this->robot->with('default')->setMessage('file')->getMessage());
    }

    public function testContentAt()
    {
        $content = $this->robot->text('测试内容')->contentAt('foo')->getMessage()->getContent();
        $this->assertStringEndsWith('<@foo>', $content);
    }

    public function testSend()
    {
        $message = $this->robot->text('hello word!')->getMessage();
        $r = \Mockery::mock($this->robot)->makePartial();
        $r->allows([
            'send' => ['errcode' => 0, 'errmsg' => 'ok'],
        ]);
        $this->assertSame(['errcode' => 0, 'errmsg' => 'ok'], $r->send($message));
    }
}
