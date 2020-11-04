<?php
/**
 * Created by PhpStorm.
 * Filename: BotServiceProvider.php
 * Description:
 * User: orhanbhr
 * Date: 4.11.2020
 * Time: 15:10
 */

namespace orhanbhr\IddaaBot;

use Illuminate\Support\ServiceProvider;

class BotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'iddaabot');
    }

    public function register()
    {
    }
}
