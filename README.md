### Easy Paypal

Easy paypal é um SDK para facilitar a integração do Paypal NVP API e Paypal IPN com sua aplicação.

#### Pré-Requisitos
* PHP >= 5.3
* Curl extension 
* Composer

#### Exemplos

##### Express checkout

```php
//Referencia / Invoice ID: Campo para o acompanhamento e controle interno do comerciante
$ref=null;

$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;

$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Checkout('expressCheckout');
$nvp->setRequest($request);

//Create sellers
$seller = new \easyPaypal\Seller('SALE', $ref);
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1);
$item2 = new \easyPaypal\Item('Item 2', 'Description', 40.00, 1);

$seller->addItem($item1);
$seller->addItem($item2);

//Set request
$nvp->setParams($seller);
$response = $nvp->send($seller);
```

O exemplo de express checkout passo a passo pode ser encontrado em examples/checkout_step_by_step.php
