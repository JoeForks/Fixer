StyleCI Fixer
=============

StyleCI Fixer was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a code style report builder. It utilises the [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/StyleCI/Fixer/releases), [license](LICENSE), [api docs](http://docs.grahamjcampbell.co.uk), and [contribution guidelines](CONTRIBUTING.md).

![StyleCI Fixer](https://cloud.githubusercontent.com/assets/2829600/5062952/c2a779c6-6dca-11e4-9fe2-24596822f7a8.PNG)

<p align="center">
<a href="https://travis-ci.org/StyleCI/Fixer"><img src="https://img.shields.io/travis/StyleCI/Fixer/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Fixer/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/StyleCI/Fixer.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Fixer"><img src="https://img.shields.io/scrutinizer/g/StyleCI/Fixer.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/StyleCI/Fixer/releases"><img src="https://img.shields.io/github/release/StyleCI/Fixer.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

[PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of StyleCI Fixer, simply add the following line to the require block of your `composer.json` file:

```
"styleci/fixer": "0.1.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

If you're using Laravel 5, then you can register our service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'StyleCI\Fixer\FixerServiceProvider'`


## Usage

StyleCI Fixer is designed to pull down code from github commits, analyse it, and build code style reports. There is currently no real documentation for this package, but feel free to check out the [API Documentation](http://docs.grahamjcampbell.co.uk) for StyleCI Fixer.


## License

StyleCI Fixer is licensed under [The MIT License (MIT)](LICENSE).
