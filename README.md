## Smart PRO Technology Http Request

### Instalação

```bash
composer require prismo-smartpro/httprequest
```

### Metodo de uso

```php
<?php

require "vendor/autoload.php";

use SmartPRO\Technology\HttpRequest;

/** INICIA A CLASSE HTTP REQUEST*/
$request = new HttpRequest();
/** DEFINE OS HEADERS DA REQUISIÇÃO */
$request->headers([
    "user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) BC3 iOS/3.12.7 (build 538; iPhone 11 Pro Max; iOS 14.7.1)"
]);
/** DEFINE UMA PROXY PARA A REQUISIÇÃO */
$request->proxy("127.0.0.1:8090@username:password");
/** DEFINE UMA URL BASE PARA AS REQUESTS (OPCIONAL) */
$request->urlBase("https://viacep.com.br/ws/");
/** TEMPO LIMITE PARA A REQUISIÇÃO */
$request->timeout(10);
/** CONVERTE O RETORNO DA REQUEST EM UM OBJETO JSON */
$request->json();

$get = $request->send("01001000/json/", "GET");

if ($request->error()) {
    echo "Error: {$request->error()}";
} else {
    var_dump($get);
}
```