Laravel Fixer
=============

Laravel Fixer was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a code style report builder for [Laravel 5](http://laravel.com). It utilises the [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Fixer/releases), [license](LICENSE.md), [api docs](http://docs.grahamjcampbell.co.uk), and [contribution guidelines](CONTRIBUTING.md).

![Laravel Fixer](https://cloud.githubusercontent.com/assets/2829600/5062952/c2a779c6-6dca-11e4-9fe2-24596822f7a8.PNG)

<p align="center">
<a href="https://travis-ci.org/GrahamCampbell/Laravel-Fixer"><img src="https://img.shields.io/travis/GrahamCampbell/Laravel-Fixer/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Fixer/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/GrahamCampbell/Laravel-Fixer.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Fixer"><img src="https://img.shields.io/scrutinizer/g/GrahamCampbell/Laravel-Fixer.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE.md"><img src="https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/GrahamCampbell/Laravel-Fixer/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Laravel-Fixer.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Fixer, simply add the following line to the require block of your `composer.json` file:

```
"graham-campbell/fixer": "0.1.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Fixer is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'GrahamCampbell\Fixer\FixerServiceProvider'`

You can register the Fixer facade in the `aliases` key of your `config/app.php` file if you like.

* `'Fixer' => 'GrahamCampbell\Fixer\Facades\Fixer'`


## Configuration

Laravel Fixer requires no configuration. Just follow the simple install instructions and go!


## Usage

Laravel fixer is designed to pull down code from github commits, analyse it, and build code style reports. There is currently no real documentation for this package, but feel free to check out the [API Documentation](http://docs.grahamjcampbell.co.uk) for Laravel Fixer.


## License

Apache License

Copyright 2014 Graham Campbell

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
