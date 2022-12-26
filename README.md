# ChampsFramework

[![Maintainer](http://img.shields.io/badge/maintainer-@betocampoy78-blue.svg?style=flat-square)](https://twitter.com/betocampoy78)
[![Source Code](http://img.shields.io/badge/source-betocampoy/champs_framework-blue.svg?style=flat-square)](https://github.com/betocampoy/champs_framework)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/betocampoy/champs_framework.svg?style=flat-square)](https://packagist.org/packages/betocampoy/champs_framework)
[![Latest Version](https://img.shields.io/github/release/betocampoy/champs_framework.svg?style=flat-square)](https://github.com/betocampoy/champs_sao/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/betocampoy/champs_framework.svg?style=flat-square)](https://scrutinizer-ci.com/g/betocampoy/champs_framework)
[![Quality Score](https://img.shields.io/scrutinizer/g/betocampoy/champs_framework.svg?style=flat-square)](https://scrutinizer-ci.com/g/betocampoy/champs_framework)
[![Total Downloads](https://img.shields.io/packagist/dt/betocampoy/champs_framework.svg?style=flat-square)](https://packagist.org/packages/betocampoy/champs_framework)

###### Champs Framework was developed by studying purposes only. DON'T USE IT IN PRODUCTION ENVIRONMENTS.

Champs Framework foi desenvolvido para fins de estudo e aprendizado. NÃO UTILIZE EM AMBIENTES DE PRODUÇÃO.

###### Main Resources [Principais Recursos].

- MVC architecture [Arquitetura MVC]
- Router layer with friendly URLs [Camada Router com URLs amigáveis]
- Model layer to simplify access to the MySql Database Server [Camada Model, para simplicar o acesso ao banco de dados]
- View layer using *league/plates* package [Camada de View utilizando o pacote *league/plates*]
- Controller Layer implements CSRF control and Inputs Validation (using *rakit/validation*
  package) [Camada de controle, já implementado controle de CSRF, Validação de Inputs (utiliza o pacote *
  rakit/validation*)]
- Message Object to standarize messages in all
  aplication [Objeto Message para padronizar as mensagens em toda a aplicação]
- Session Object to standarize session manipulation [Objeto Session para padronizar a manipulação da sessão]
- Authentication based in Users, Roles and Permissions [Autenticação baseado em Usuarios, Perfis e Permissões]

## Installation [Instalação]

ChampsFramework is available via Composer:

Example of a clean ***composer.json*** file.

```bash
{
    "authors": [
        {
            "name": "Creator Author Name",
            "email": "author@email.com",
            "homepage": "url.of.project",
            "role": "Developer"
        }
    ],
    "description": "Description of you project",
    "config": {"vendor-dir": "vendor"},
    "autoload": {
        "psr-4": {"Source\\": "Source/"}
    },
    "require": {
        "betocampoy/champs_framework": "1.0.*"
    }
}
```

```bash
"betocampoy/champs_framework": "1.0.*"
```

or run

```bash
composer require betocampoy/champs_framework
```

## Initial Configurations [Configurações Iniciais]

###### 1. Create ***.htaccess*** file in root project folder [Criar o arquivo ***
.htaccess*** no diretório raiz do projeto]

***.htaccess*** file example [Exemplo de arquvivo ***.htaccess***]

```bash
RewriteEngine On
Options All -Indexes

## ROUTER WWW Redirect.
#RewriteCond %{HTTP_HOST} !^www\. [NC]
#RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

## ROUTER HTTPS Redirect
#RewriteCond %{HTTP:X-Forwarded-Proto} !https
#RewriteCond %{HTTPS} off
#RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# ROUTER URL Rewrite
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteRule ^(.*)$ index.php?route=/$1 [L,QSA]
```

###### 2. Create ***index.php*** file in root project folder [Criar o arquivo ***
index.php*** no diretório raiz do projeto]

***index.php*** file example [Exemplo de arquivo ***index.php***]

```bash
<?php
ob_start();
date_default_timezone_set('America/Sao_Paulo');
require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use BetoCampoy\ChampsFramework\Session;
use BetoCampoy\ChampsFramework\Router\Router;
use function ICanBoogie\pluralize;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * EXAMPLE THEME ROUTES
 */
$route->group(null);
$route->get("/", "WebExample:home");
$route->get("/terms", "WebExample:terms");
$route->get("/contact", "WebExample:contact");

/**
 * CREATE YOUR CUSTOM ROUTES BELOW
 */


/**
 * CREATE YOUR CUSTOM ROUTES ABOVE
 */

/**
 * ROUTE DISPATCH
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect( $route->route("default.error", ["errcode" => $route->error()]));
}

ob_end_flush();
```

## Documentation [Documentação]

###### To open Champs Framework documentation, access the route **/champs-docs**.

Para abrir a documentação do Champs Framework, acesse a rota **/champs-docs**.

## Contributing [Contribuições]

Please see [CONTRIBUTING](https://github.com/betocampoy/champs_framework/blob/master/CONTRIBUTING.md) for details.

Pro favor, veja [CONTRIBUTING](https://github.com/betocampoy/champs_framework/blob/master/CONTRIBUTING.md) para mais
detalhes.

## Support [Suporte]

###### Security: If you discover any security related issues, please email beto.campoy@gmail.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para beto.campoy@gmail.com em vez de usar o
rastreador de problemas.

Thank you [Obrigado]

## Credits

- [Beto Campoy](https://github.com/betocampoy) (Developer)
- [All Contributors](https://github.com/betocampoy/champs_framework/contributors) (This Rock)

## License

The MIT License (MIT). Please see [License File](https://github.com/betocampoy/champs_framework/blob/master/LICENSE) for
more information.