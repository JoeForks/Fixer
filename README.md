StyleCI Fixer
=============

StyleCI Fixer was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a code style report builder for [Laravel 5](http://laravel.com). It utilises the [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/StyleCI/Fixer/releases), [license](LICENSE), [api docs](http://docs.grahamjcampbell.co.uk), and [contribution guidelines](CONTRIBUTING.md).

![StyleCI Fixer](https://cloud.githubusercontent.com/assets/2829600/5062952/c2a779c6-6dca-11e4-9fe2-24596822f7a8.PNG)

<p align="center">
<a href="https://travis-ci.org/StyleCI/Fixer"><img src="https://img.shields.io/travis/StyleCI/Fixer/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Fixer/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/StyleCI/Fixer.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Fixer"><img src="https://img.shields.io/scrutinizer/g/StyleCI/Fixer.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-AGPL%203.0-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/StyleCI/Fixer/releases"><img src="https://img.shields.io/github/release/StyleCI/Fixer.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

[PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of StyleCI Fixer, simply add the following line to the require block of your `composer.json` file:

```
"styleci/fixer": "0.1.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once StyleCI Fixer is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'StyleCI\Fixer\FixerServiceProvider'`

You can register the Fixer facade in the `aliases` key of your `config/app.php` file if you like.

* `'Fixer' => 'StyleCI\Fixer\Facades\Fixer'`


## Configuration

StyleCI Fixer supports optional configuration.

To get started, first publish the package config file:

```bash
$ php artisan publish:config styleci/fixer
```

There is one config option:

##### Gitlib Options

This option (`'options'`) defines the options to pass to gitlib. This might include setting your git executable location. Feel free to check out the [documentation](http://gitonomy.com/doc/gitlib/master/api/repository/#repository-options) for this. The default value for this setting is `[]`.


## Usage

StyleCI Fixer is designed to pull down code from github commits, analyse it, and build code style reports. There is currently no real documentation for this package, but feel free to check out the [API Documentation](http://docs.grahamjcampbell.co.uk) for StyleCI Fixer.


## License

GNU AFFERO GENERAL PUBLIC LICENSE

StyleCI Fixer Is A Code Style Report Builder

Copyright (C) 2014-2015 Graham Campbell

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
