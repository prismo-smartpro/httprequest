## Smart PRO Technology Http Request

### Instalação

```bash
composer require prismo-smartpro/httprequest
```

### Método de uso

```php
<?php

require "vendor/autoload.php";

use SmartPRO\Technology\Request;

$searchCEP = Request::GET("https://viacep.com.br/ws/01001000/json/", [
    "auth" => "username:password",
    "proxy" => "127.0.0.1:8090@username:password",
    "body" => http_build_query([
        "version" => "1.7"
    ]),
    "headers" => [
        "Content-Type: application/json; charset=utf-8"
    ]
]);

if (Request::error()) {
    var_dump(Request::error());
} else {
    var_dump($searchCEP);
}
```