<?php
/*
 * O TOKEN retornado após a chamada da operação SetExpressCheckout expira em três horas após gerado.
 * Isso significa que, caso o cliente não seja redirecionado para a página de pagamento,
 * um novo TOKEN deverá ser gerado.
 * armazenar esse token em base de dados para eventuais atividades de rastreamento de erros
 */
//Incluindo o arquivo que contém a função sendNvpRequest
require 'sendNvpRequest.php';
 
//Vai usar o Sandbox, ou produção?
$sandbox = true;
 
//Baseado no ambiente, sandbox ou produção, definimos as credenciais
//e URLs da API.
if ($sandbox) {
    //credenciais da API para o Sandbox
    $user = 'ricardo_borges26-facilitator_api1.hotmail.com';
    $pswd = '53BTEC9TEFQMXUEB';
    $signature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AtMJGOkep0UQaULI7-wxg9S7MPtb';
 
    //URL da PayPal para redirecionamento, não deve ser modificada
    $paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
} else {
    //credenciais da API para produção
    $user = 'usuario';
    $pswd = 'senha';
    $signature = 'assinatura';
 
    //URL da PayPal para redirecionamento, não deve ser modificada
    $paypalURL = 'https://www.paypal.com/cgi-bin/webscr';
}
 
//Campos da requisição da operação SetExpressCheckout, como ilustrado acima.
$requestNvp = array(
    'USER' => $user,
    'PWD' => $pswd,
    'SIGNATURE' => $signature,
 
    'VERSION' => '108.0',
    'METHOD'=> 'SetExpressCheckout',
    //o primeiro valor numérico indica quem é o vendedor e o segundo é um contador para cada item do carrinho. Ex 'L_PAYMENTREQUEST_{vendedor}_NAME{item}
    'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
    'PAYMENTREQUEST_0_AMT' => '22.00',
    'PAYMENTREQUEST_0_CURRENCYCODE' => 'BRL',
    'PAYMENTREQUEST_0_ITEMAMT' => '22.00',
    'PAYMENTREQUEST_0_INVNUM' => '1234',//Numero do pedido
 
    'L_PAYMENTREQUEST_0_NAME0' => 'Item A',
    'L_PAYMENTREQUEST_0_DESC0' => 'Produto A – 110V',
    'L_PAYMENTREQUEST_0_AMT0' => '11.00',
    'L_PAYMENTREQUEST_0_QTY0' => '1',
    'L_PAYMENTREQUEST_0_ITEMAMT' => '11.00',
    'L_PAYMENTREQUEST_0_NAME1' => 'Item B',
    'L_PAYMENTREQUEST_0_DESC1' => 'Produto B – 220V',
    'L_PAYMENTREQUEST_0_AMT1' => '11.00',
    'L_PAYMENTREQUEST_0_QTY1' => '1',
 
    'RETURNURL' => 'http://PayPalPartner.com.br/VendeFrete?return=1',
    'CANCELURL' => 'http://PayPalPartner.com.br/CancelaFrete',
    'BUTTONSOURCE' => 'BR_EC_EMPRESA'
);
 
//Envia a requisição e obtém a resposta da PayPal
$responseNvp = sendNvpRequest($requestNvp, $sandbox);
 
//Se a operação tiver sido bem sucedida, redirecionamos o cliente para o
//ambiente de pagamento.
if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {
    $query = array(
        'cmd'    => '_express-checkout',
        'token'  => $responseNvp['TOKEN']
    );
 
    $redirectURL = sprintf('%s?%s', $paypalURL, http_build_query($query));
 
    header('Location: ' . $redirectURL);
} else {
    //Opz, alguma coisa deu errada.
    //Verifique os logs de erro para depuração.
}
