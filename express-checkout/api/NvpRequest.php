<?php
class NvpRequest{

    private $sandbox;
    private $user;
    private $password;
    private $signature;
    private $request;
    private $localecode;
    private $version;
    private $method;
    private $returnUrl;
    private $cancelUrl;
    private $buttonSource;

    /**
     * NvpRequest constructor.
     * @param boolean $sandbox
     * @param string $user
     * @param string $password
     * @param string $signature
     */
    public function __construct($user, $password, $signature, $sandbox=false)
    {
        $this->sandbox = $sandbox;
        $this->user = $user;
        $this->password = $password;
        $this->signature = $signature;
    }


    function __get($name)
    {
        return $this->$name;
    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }

    function setExpressCheckout(){

    }

    function getExpressCheckoutDetails(){

    }

    function doExpressCheckoutPayment(){

    }
    /*
        array(
            'seller'=>array(
                    'PAYMENTACTION'=>'',
                    'AMT'=>'',
                    'CURRENCYCODE'=>'',
                    'ITEMAMT'=>'',
                    'INVNUM'=>'',
                    'items'=>array(
                        'NAME'=>'',
                        'DESC'=>'',
                        'AMT'=>'',
                        'QTY'=>'',
                    )
                )
        )
     */
    function setRequest($params){
        echo "<br>".$params['METHOD']."<br>";
        $this->request = array(
            'LOCALECODE' => $params['LOCALECODE'],
            'USER' => $this->user,
            'PWD' => $this->password,
            'SIGNATURE' => $this->signature,
            'VERSION' => '108.0',
            'METHOD'=> $params['METHOD'],
            'RETURNURL' => $params['RETURNURL'],
            'CANCELURL' => $params['CANCELURL'],
            'BUTTONSOURCE' => $params['BUTTONSOURCE']
        );

        if($params['METHOD'] != 'SetExpressCheckout'){
            $this->request['TOKEN'] = $params['TOKEN'];
        }

        if($params['METHOD'] == 'DoExpressCheckoutPayment'){
            $this->request['PAYERID'] = $params['PAYERID'];
        }

        $countSeller = 0;
        $countItem = 0;
        foreach($params['sellers'] as $seller){

            $this->request['PAYMENTREQUEST_'.$countSeller.'_PAYMENTACTION'] = $seller['PAYMENTACTION'];
            $this->request['PAYMENTREQUEST_'.$countSeller.'_AMT'] = $seller['AMT'];
            $this->request['PAYMENTREQUEST_'.$countSeller.'_CURRENCYCODE'] = $seller['CURRENCYCODE'];
            $this->request['PAYMENTREQUEST_'.$countSeller.'_ITEMAMT'] = $seller['ITEMAMT'];
            $this->request['PAYMENTREQUEST_'.$countSeller.'_INVNUM'] = $seller['INVNUM'];

            foreach($seller['items'] as $item){
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_NAME'.$countItem] = $item['NAME'];
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_DESC'.$countItem] = $item['DESC'];
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_AMT'.$countItem] = $item['AMT'];
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_QTY'.$countItem] = $item['QTY'];
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_ITEMAMT'.$countItem] = $item['AMT'];
                $countItem++;
            }
            $countSeller++;
        }
    }

    public function send($params){
        $this->setRequest($params);
        //echo "<br> Params: <br>";
        //var_dump($this->request);
        //Endpoint da API
        $apiEndpoint  = 'https://api-3t.' . ($this->sandbox ? 'sandbox.': null);
        $apiEndpoint .= 'paypal.com/nvp';

        //Executando a operação
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request));

        $response = urldecode(curl_exec($curl));
        $response = $this->sanitize($response);

        curl_close($curl);
        var_dump($response);
        echo "<br><br>";

        return $response;
    }

    public function sanitize($response){
        $responseNvp = array();
        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $responseNvp[$name] = $matches['value'][$offset];
            }
        }
        return $responseNvp;
    }
}