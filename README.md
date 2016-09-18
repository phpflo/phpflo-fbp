# phpflo-fbp: load, parse, dump
Flowbased programming protocol (fbp) config file loader

[![Build Status](https://travis-ci.org/phpflo/phpflo-fbp.svg?branch=master)](https://travis-ci.org/phpflo)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpflo/phpflo-fbp/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpflo/phpflo-fbp/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phpflo/phpflo-fbp/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phpflo/phpflo-fbp/?branch=master)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)


## Introduction

This library allows you to load and parse configuration for your phpflo project. It also works standalone if you want to convert your old json configs to fbp spec.
Supported config formats are json (.json), yaml (.yml) and fbp (.fbp), output is array.

## Code Samples

Basic usage:
```php
// load fbp config
$defintiion = \PhpFlo\Loader\Loader::load('my/fbp/config/file.fbp');
```
You can load json, yml and fbp that way.

Parser by itself:
```php
$myFbpConfig = <<<EOF
'test.file' -> IN ReadFile(ReadFile)
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile ERROR -> IN Display(Output)
SplitbyLines OUT -> IN CountLines(Counter)
CountLines COUNT -> IN Display
EOF;

$parser = new \PhpFlo\Fbp\FbpParser();
$definition = $parser->run($myFbpConfig);
```
Dump your flow to a format:
```php
$json = \PhpFlo\Fbp\FbpDumper::toJson($definition);
$yaml = \PhpFlo\Fbp\FbpDumper::toYaml($definition);
$fbp = \PhpFlo\Fbp\FbpDumper::toFbp($definition);
```

The definition has following schema:
```php
$schema = [
    'properties' => ['name' => '',],
    'initializers' => [
        [
            'data' => '',
            'tgt' => [
                'process' => '',
                'port' => '',
            ],
        ],
    ],
    'processes' => [
        'ReadFile' => [
            'component' => '',
            'metadata' => [
                'label' => '',
            ],
        ],
    ],
    'connections' => [
        [
            'src' => [
                'process' => '',
                'port' => '',
            ],
            'tgt' => [
                'process' => '',
                'port' => '',
            ],
        ],
    ],
]
```

## Installation

Regular install via composer:
```php
composer require phpflo/phpflo-fbp
```
