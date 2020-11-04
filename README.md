<p align="center">
<a href="https://packagist.org/packages/orhanbhr/iddaa-bot-2020"><img src="https://img.shields.io/packagist/dt/orhanbhr/iddaa-bot-2020" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/orhanbhr/iddaa-bot-2020"><img src="https://img.shields.io/packagist/v/orhanbhr/iddaa-bot-2020" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/orhanbhr/iddaa-bot-2020"><img src="https://img.shields.io/packagist/l/orhanbhr/iddaa-bot-2020" alt="License"></a>
</p>

# Iddaa.com PHP Bot 2020
It is PHP-based, allowing you to receive data from iddaa.com.

## Installation
Installing with the help of Composer:
```
composer require orhanbhr/iddaa-bot-2020
````

#### Laravel Service Provider

If you are using Laravel; `config/app.php` under the providers array in the file

```
orhanbhr\IddaaBot\BotServiceProvider::class
```

## Usage

#### Match List

```php
use orhanbhr\IddaaBot\Controllers\IddaaBotController;

$bot = new IddaaBotController();
$json = $bot->program();
var_dump($json);
```

#### Match Detail

```php
$bot->detail(2, 231998);
```

#### Change Sport

```php
$bot->program(2);
```

#### Change Language

```php
$bot->lang('tr')->program();
```

#### Change Response Type

```php
$bot->responseType(0)->program();
```

#### Sport List

Id | Name
--- | ---
1 | Football
2 | Basketball
20 | Table Tennis
4 | Ice Hockey
6 | Handball
23 | Volleyball
5 | Tennis
11 | Motor Sports
19 | Snooker

#### Language List

Id | Name
--- | ---
tr | Turkish
en | English

#### Response Type List

Id | Name
--- | ---
1 | Json
0 | Array

## Contributing

Thank you for considering contributing to the Iddaa Bot.

## Developer

[Orhan BAHAR](https://www.orhanbhr.com)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
