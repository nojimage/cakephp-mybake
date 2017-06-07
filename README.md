# Custom Bake Template for CakePHP 3.x

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require --dev nojimage/cakephp-mybake
```

in `config/bootstrap_cli.php`

```
Plugin::load('MyBake', ['bootstrap' => true]);
```

## Usage

```
bin/cake bake model {all|ModelName} -t MyBake --no-fixture
bin/cake bake fixture {all|ModelName} -t MyBake --schema
```

## License

This software is released under the MIT License.

Copyright (c) 2017 ELASTIC Consultants Inc. [https://elasticconsultants.com/](https://elasticconsultants.com/)

http://opensource.org/licenses/mit-license.php
