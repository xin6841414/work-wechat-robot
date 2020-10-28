<?php

/*
 * This file is part of the xin6841414/weather.
 *
 * (c) xin6841414 <xin6841414@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xin6841414\WorkWechatRobot;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
                dirname(__DIR__).'/config/workwechatrobot.php' => config_path('workwechatrobot.php'), ]
        );
    }

    public function register()
    {
        $this->app->singleton(Robot::class, function ($app) {
            return new Robot($app['config']['workwechatrobot']);
        });
        $this->app->alias(Robot::class, 'workwechatrobot');
    }

    public function provides()
    {
        return [Robot::class, 'workwechatrobot'];
    }
}
