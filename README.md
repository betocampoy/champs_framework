# ChampsFramework Session As Object @ChampsSao

[![Maintainer](http://img.shields.io/badge/maintainer-@betocampoy78-blue.svg?style=flat-square)](https://twitter.com/betocampoy78)
[![Source Code](http://img.shields.io/badge/source-betocampoy/champs_sao-blue.svg?style=flat-square)](https://github.com/betocampoy/champs_sao)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/betocampoy/champs_sao.svg?style=flat-square)](https://packagist.org/packages/betocampoy/champs_sao)
[![Latest Version](https://img.shields.io/github/release/betocampoy/champs_sao.svg?style=flat-square)](https://github.com/betocampoy/champs_sao/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/betocampoy/champs_sao.svg?style=flat-square)](https://scrutinizer-ci.com/g/betocampoy/champs_sao)
[![Quality Score](https://img.shields.io/scrutinizer/g/betocampoy/champs_sao.svg?style=flat-square)](https://scrutinizer-ci.com/g/betocampoy/champs_sao)
[![Total Downloads](https://img.shields.io/packagist/dt/betocampoy/champs_sao.svg?style=flat-square)](https://packagist.org/packages/betocampoy/champs_sao)

###### Champs Framework was developd by studing purposes only. DON'T USE IT IN PRODUCTION ENVIRONMENT.

Champs Framework foi desenvolvido como base de estudo e aprendisado. NÃO UTILIZE EM AMBIENTES DE PRODUÇÃO.

###### Simplify session manipulation converting session into an object.

Simplifique a manipulação da session, convertendo-a em objeto.

## Installation

ChampsSao is available via Composer:

```bash
"betocampoy/champs_sao": "1.0.*"
```

or run

```bash
composer require betocampoy/champs_sao
```

## Documentation

###### See example folder for more information about package's usage:

Consulte o diretório examplo para mais informações sobre como utilizar esse pacote:


##### Instance session

```php
<?php
$session = new \BetoCampoy\ChampsSao\Session();
```

##### Set values into session

```php
<?php
$session->set("key_name", "key_value");
```

###### Access values saved in session

```php
$session->key_name;
```

###### Verify if a key exists in session
````php
$session->has("key_name"); // return bool
````

###### Unset a key from session
````php
$session->unset("key_name");
````

###### Use of Session Flash
````php
// save flash value
$session->set("flash", "Value to save in session");

// get flash value
$value = $session->flash();

````


## Contributing

Please see [CONTRIBUTING](https://github.com/betocampoy/champs_sao/blob/master/CONTRIBUTING.md) for details.

## Support

###### Security: If you discover any security related issues, please email beto.campoy@gmail.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para beto.campoy@gmail.com em vez de usar o rastreador de problemas.

Thank you

## Credits

- [Beto Campoy](https://github.com/betocampoy) (Developer)
- [All Contributors](https://github.com/betocampoy/champs_sao/contributors) (This Rock)

## License

The MIT License (MIT). Please see [License File](https://github.com/betocampoy/champs_sao/blob/master/LICENSE) for more information.