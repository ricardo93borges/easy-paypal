### Easy Paypal

Easy paypal é um SDK para facilitar a integração do Paypal NVP API e Paypal IPN com sua aplicação.

#### Pré-Requisitos
* PHP >= 5.3
* Curl extension 
* Composer

#### Exemplos

##### Criando objeto Request

As requisições a API são feitas por um objeto Request, este objeto deve ser criado com os seguintes parametros obrigatorios:

* $sandbox = boolean, se true usará o endpoint sandbox.paypal.com/br/cgi-bin/webscr senão paypal.com/br/cgi-bin/webscr
* $user; 
* $password; 
* $signature; 
* $returnUrl = URL de retorno após o comprador confirmar a compra

Parametros opcionais:

* $cancelUrl = URL de retorno após o comprador cancelar a compra
* $headerImage = Imagem para apacer na página de confirmação, aqui pode-se usar o logotipo da sua loja.
* $buttonSource;
* $localecode = Idioma, padrão: 'pt_BR';
* $version = versão da API, padrão: '73.0';
* $currencyCode = Moeda, padrão: 'BRL'
* $countryCode = País, padrão: 'BR'

```php
$sandbox = true;
$username = "conta-business_api1.test.com";
$password = "123456";
$signature = "AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx";

$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;

$request = new \easyPaypal\Request($sandbox, $username, $password, $signature, $returnUrl, $cancelUrl);
$request->setHeaderImage('path/to/my/image');
$request->setLocalecode('pt_BR');
```

##### Express checkout

Express Checkout é uma solução de pagamento do PayPal indicada para sites e lojas online que tenham integrações de médio e grande porte.

```php
include "autoload.php";

//Referencia / Invoice ID: Campo para o acompanhamento e controle interno do comerciante
$ref=null;

$request = new \easyPaypal\Request($sandbox, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
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

##### Criar perfil de recorrência

Para criar um perfil de recorrência, um objeto Recurring é usado, este objeto é criado com os seguintes paramentros:

* $profileStartDate = Data de início do perfil, valor padrão: data atual + 1 hora
* $billingPeriod = Periodicidade, aceita os valores: Day, Week, Month, Year, valor padrão: Month
* $billingFrequency = Número de períodos que formam 1 ciclo de pagamento, valor padrão: 1
* $amount = O valor que será cobrado em cada ciclo de pagamento. Parâmetro obrogatório.

Parâmetros opcionais:

* $totalBillingCycles = Número total de ciclos de pagamento antes do perfil de recorrência ser encerrado, se não for informado o perfil existirá por tempo indeterminado.
* $maxFailedPayments = Número máximo de pagamentos que podem falhar, antes do perfil ser cancelado automaticamente
* $autobillAmt = Valor a pagar no proximo ciclo se o pagamento atual falhar, o PayPal será instruído a cobrar automaticamente o “montante a pagar” no próximo ciclo. Sempre que um pagamento recorrente, ou o pagamento inicial, falha, o valor que deveria ser cobrado é adicionado em um montante a pagar.

Exemplo de caso de uso:

Cliente compra a assinatura mensal de uma revista, com valor de R$ 10.00 e com validade de 1 ano. A cobrança acontecerá a cada 3 meses:

* $billingPeriod = Month
* $billingFrequency = 3
* $amount = 10.00
* $totalBillingCycles = 4

Isso criará um perfil de pagamento mensal, onde a cada três meses, o cliente pagará o equivalente a R$ 30,00 pela assinatura pela assinatura mensal, que terá duração de 1 ano.

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);

//Criando objeto Recurring usando parâmetros default
$method = 'expressCheckout';
$amount = 100;
$description = 'Recurring payment test';
$nvp = new \easyPaypal\Recurring($method, $amount, $description);

//Alterando parâmetro do objeto Recurring
$nvp->setTotalBillingCycles(12);
$nvp->setBillingPeriod('Week');
$nvp->setBillingFrequency(4);

$nvp->setRequest($request);
//Create sellers
$seller = new \easyPaypal\Seller('SALE', null, 'BRL');
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$seller->addItem($item1);
//Set request
$nvp->setParams($seller);
$response = $nvp->send($seller);
```

##### Perfil de recorrência com periodo de experiência

Para este tipo de perfil é necessário especificar mais 4 parâmetro:

* $trialBillingPeriod = Periodicidade do periodo de experiência, valores aceitos: Day, Week, Month, Year.
* $trialBillingFrequency = Número de períodos que formam 1 ciclo.
* $trialAmt = Valor que será cobrado durante o período de experiência.
* $trialTotalBillingCycles = Número total de ciclos que terá o período de experiência. Ao contrário da criação do perfil regular, este parâmetro é obrigatório.

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Recurring('expressCheckout', 100, 'Recurring payment test');
$nvp->setRequest($request);
//Trial
$nvp->setTrialAmt(0);
$nvp->setTrialBillingFrequency(1);
$nvp->setTrialBillingPeriod('Month');//(Month|Day|Week|Year)
$nvp->setTrialTotalBillingCycles(1);
//Create sellers
$seller = new \easyPaypal\Seller('SALE', null, 'BRL');
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$seller->addItem($item1);
//Set request
$nvp->setParams($seller);
$response = $nvp->send($seller);
```

##### Reembolso

O reembolso total ou parcial pode ser realizado apenas para transações criadas a menos de 60 dias. O reembolso é realizado utilizando o método refundTransaction da classe Transaction, este método aceita os seguintes parâmetros:

* $transactionId = ID da transação, parâmetro obrigatório.
* $refundType = Tipo de reembolso, parâmetro obrigatório, valores aceitos: Full ou Partial
* $amount = Valor a ser reembolsado, obrigatório para reembolsos parciais (Partial).
* $currencyCode = Moeda utilizada no reembolso, obrigatório para reembolsos parciais (Partial). Ex.: BRL
* $note = Razão para o reembolso, opcional.
* $payerId = ID do comprador, opcional.
* $invoiceId = Identificador interno da compra (Referência), opcional.

No exemplo abaixo é buscado todas as transações realizadas nos últimos 30 dias, e então realizado um reembolso total em uma e um reembolso parcial em outra.

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
//Create Transaction Object
$transaction = new \easyPaypal\Transaction();
//Set start and end date
$startDate = new \DateTime();
$endtDate = new \DateTime();
$startDate->sub(new DateInterval('P30D'));
$transaction->setStartDate($startDate);
$transaction->setEndDate($endtDate);
//Set request
$transaction->setRequest($request);

//Search transactions by start and end dates
$transactions = $transaction->transactionSearch();

//Get transaction details by ID
$details = $transaction->getTransactionDetails($transactions[0]->getTxnId());
//Full Refund
$response = $transaction->refundTransaction($details->getTxnId(), 'Full');
if(isset($response['ACK']) && $response['ACK'] == "Success"){
    echo "Success on Full Refund transaction. REFUNDTRANSACTIONID: ".$response['REFUNDTRANSACTIONID']."<br/><br/>";
}

//Get transaction details
$details = $transaction->getTransactionDetails($transactions[1]->getTxnId());
//Partial Refund
$response = $transaction->refundTransaction($details->getTxnId(), 'Partial', 1, $details->getCurrencyCode(), "Partial Refund test", $details->getCustomer()->getPaypalId());
if(isset($response['ACK']) && $response['ACK'] == "Success"){
    echo "Success on Full Refund transaction. REFUNDTRANSACTIONID: ".$response['REFUNDTRANSACTIONID']."<br/><br/>";
}
```


