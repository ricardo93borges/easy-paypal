### Easy Paypal

Easy paypal é um SDK para facilitar a integração do Paypal NVP API e Paypal IPN com sua aplicação.

#### Pré-Requisitos
* PHP >= 5.3
* Curl extension 
* Composer

#### Exemplos

##### Criando objeto Request

As requisições a API são feitas por um objeto Request, este objeto deve ser criado com os seguintes parametros obrigatorios:

* $sandbox = boolean, se true usará o endpoint <i><b>sandbox.paypal.com/br/cgi-bin/webscr</b></i> senão <i><b>paypal.com/br/cgi-bin/webscr</b></i>
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
* $notifyUrl = URL para onde será enviado as notificações referentes a transação realizada com este objeto Request

```php
include "autoload.php";

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

Express Checkout é uma solução de pagamento do PayPal indicada para sites e lojas online que tenham integrações de médio e grande porte. Para realizar o express checkout crie um objeto Checkout e atribua um objeto Request a ele. Em cada requisição pode ser criada até 10 transações, isso é feito criando objetos Seller e atribuindo objetos Item a eles. Cada Seller irá gerar uma transação. Os objetos Seller são atribuídos ao Checkout pelo método setParams().

Um Seller é criado usando os seguintes parâmetros opcionais:

* $reference = Número de pedido para controle do comerciante. Esse campo descreve o número do pedido do cliente dentro de sua própria loja. É seu identificador interno, que pode ser utilizado para a PayPal para ajudá-lo a identificar as transações durante as notificações. Se for omitido ou passado null será gerado uma string aleatória.
* $paymentAction = Especifica a ação desta transação, o valor padrão é 'SALE'.
* $currencyCode = Especifica a moeda, o valor padrão é 'BRL'

O método setParams() deve ser chamado antes do método send() para que a requisição seja atualizada com os parâmetros adicionais se necessário.

O limite de pagamentos/seller é 10 por requisição.

```php
include "autoload.php";

//Referencia / Invoice ID: Campo para o acompanhamento e controle interno do comerciante
$ref=null;

$request = new \easyPaypal\Request($sandbox, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Checkout('expressCheckout');
$nvp->setRequest($request);

//Create sellers
$seller = new \easyPaypal\Seller($ref);
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1);
$item2 = new \easyPaypal\Item('Item 2', 'Description', 40.00, 1);

$seller->addItem($item1);
$seller->addItem($item2);

//Set request
$nvp->setParams($seller);
$response = $nvp->send();
```

O exemplo de express checkout passo a passo pode ser encontrado em examples/express_checkout/checkout_step_by_step.php

##### Criar perfil de recorrência

Para criar um perfil de recorrência, um objeto Recurring é usado, este objeto é criado com os seguintes paramentros:

* $profileStartDate = Data de início do perfil, valor padrão: data atual + 1 hora
* $billingPeriod = Periodicidade, aceita os valores: Day, Week, Month, Year, valor padrão: Month
* $billingFrequency = Número de períodos que formam 1 ciclo de pagamento, valor padrão: 1
* $amount = O valor que será cobrado em cada ciclo de pagamento. Parâmetro obrigatório.

Parâmetros opcionais:

* $totalBillingCycles = Número total de ciclos de pagamento antes do perfil de recorrência ser encerrado, se não for informado o perfil existirá por tempo indeterminado.
* $maxFailedPayments = Número máximo de pagamentos que podem falhar, antes do perfil ser cancelado automaticamente
* $autobillAmt = Valor a pagar no proximo ciclo se o pagamento atual falhar, o PayPal será instruído a cobrar automaticamente o “montante a pagar” no próximo ciclo. Sempre que um pagamento recorrente, ou o pagamento inicial, falha, o valor que deveria ser cobrado é adicionado em um montante a pagar.

<b>Pagamento inicial não recorrente</b>

Além da definição do perfil de pagamento recorrente e do período de experiência, podemos definir um pagamento que deverá ser efetuado no momento da criação do perfil. Para isso deve ser definido os seguintes parametros:

* $initAmt = Valor inicial que deverá ser cobrado.
* $failedInitAmtAction = A ação que deverá ser tomada, caso o pagamento inicial falhe, valores aceitos <b>ContinueOnFailure</b> ou <b>CancelOnFailure</b>

* ContinueOnFailure – Caso o pagamento falhe, o perfil de pagamento recorrente será criado e o valor inicial será colocado no montante a pagar do perfil.
* CancelOnFailure – Caso o pagamento inicial falhe, o perfil de pagamento recorrente não será criado.

Exemplo de caso de uso:

Cliente compra a assinatura mensal de uma revista, com valor de R$ 10.00 e com validade de 1 ano. A cobrança acontecerá a cada 3 meses:

* $billingPeriod = Month
* $billingFrequency = 3
* $amount = 10.00
* $totalBillingCycles = 4

Isso criará um perfil de pagamento mensal, onde a cada três meses, o cliente pagará o equivalente a R$ 30,00 pela assinatura pela assinatura mensal, que terá duração de 1 ano.

O limite de pagamentos/seller é 10 por requisição.

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
$seller = new \easyPaypal\Seller();
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
$seller = new \easyPaypal\Seller();
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

##### Integração com IPN

Instant Payment Notification (IPN) é um serviço de mensagens que notifica sua aplicação sobre eventos relacionados às transações PayPal. Pode ser utilizado para automatizar seu back-office ou funções administrativas.

Fluxo:

* 1- O serviço no notificações da PayPal envia um HTTP POST para sua aplicação, contendo uma mensagem IPN;
* 2- Seu manipulador de notificações retorna um status HTTP 200 sem conteúdo;
* 3- Seu manipulador de notificações faz um HTTP POST, na mesma ordem, com os mesmos campos e codificação recebidos, de volta para a PayPal;
* 4- PayPal retorna uma string simples, contendo apenas VERIFIED, caso a mensagem seja válida, ou INVALID, caso a mensagem seja inválida;

A classe Ipn possui um handler (o manipulador descrito acima) que simplifica este fluxo.

Configurar a URL de notificação:

* Acesse sua conta de Paypal
* Acesse Perfil > Mais opções
* Acesse Preferências de Notificação
* Informe a url do handler

As ações tomadas quando se recebe uma notificação, são bastante específicas da aplicação. 

Em examples/ipn/ há um exemplo de manipulador que armazena os dados das notificações recebidas em um banco de dados, no arquivo ipn.php é aguardado um HTTP POST que será enviado para o método <b>handleIpn()</b> da classe <b>Ipn</b>, neste método é realizado todos os passos do fluxo descrito acima se ocorrer algum erro o mesmo é retornado caso contrario é devolvido um array com os objetos <b>Notification, Customer e Transaction</b> que são armazenado no banco de dados.

##### Obter detalhes de um perfil recorrênte

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Recurring();
$nvp->setRequest($request);

$response = $nvp->getRecurringProfileDetails($profileId);
var_dump($response);
```

##### Obter transações

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
//Search transaction
$response = $transaction->transactionSearch();

foreach($response as $t){
    echo $t->getCustomer()->getFirstName()."<br/>";
    echo $t->getCustomer()->getLastName()."<br/>";
    echo $t->getCustomer()->getEmail()."<br/>";
    echo $t->getPaymentDate()."<br/>";
    echo $t->getTxnId()."<br/>";
    echo $t->getPaymentStatus()."<br/>";
    echo $t->getTxnType()."<br/>";
    echo $t->getGross()."<br/>";
    echo $t->getCurrencyCode()."<br/>";
    echo $t->getFee()."<br/>";
    echo "<br/><br/>";
}
```

##### Obter detalhes de uma transação

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
//Search transaction
$transactions = $transaction->transactionSearch();

//Get transaction details
$details = $transaction->getTransactionDetails($transactions[0]->getTxnId());

echo $details->getCustomer()->getFirstName()."<br/>";
echo $details->getCustomer()->getLastName()."<br/>";
echo $details->getCustomer()->getEmail()."<br/>";
echo $details->getPaymentDate()."<br/>";
echo $details->getTxnId()."<br/>";
echo $details->getTxnType()."<br/>";
echo $details->getPaymentStatus()."<br/>";
echo $details->getGross()."<br/>";
echo $details->getCurrencyCode()."<br/>";
echo $details->getFee()."<br/>";
```












