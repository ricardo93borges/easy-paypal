<?php
namespace easyPaypal;

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
    private $token;
    private $payerId;
    private $paypalSandboxUrl;
    private $paypalUrl;
    private $headerImage;

    /**
     * NvpRequest constructor.
     * @param $sandbox
     * @param $user
     * @param $password
     * @param $signature
     * @param $localecode
     * @param $version
     * @param $method
     * @param $returnUrl
     * @param $cancelUrl
     * @param $buttonSource
     */
    public function __construct($user, $password, $signature, $sandbox, $method='setExpressCheckout', $returnUrl, $cancelUrl, $headerImage='', $buttonSource='BR_EC_EMPRESA', $localecode='pt_BR', $version='73.0'){
        $this->sandbox = $sandbox;
        $this->user = $user;
        $this->password = $password;
        $this->signature = $signature;
        $this->localecode = $localecode;
        $this->version = $version;
        $this->method = $method;
        $this->returnUrl = $returnUrl;
        $this->cancelUrl = $cancelUrl;
        $this->buttonSource = $buttonSource;
        $this->headerImage = $headerImage;
        $this->paypalSandboxUrl = 'https://www.sandbox.paypal.com/br/cgi-bin/webscr';
        $this->paypalUrl = 'https://www.paypal.com/br/cgi-bin/webscr';
    }

    /**
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param boolean $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getLocalecode()
    {
        return $this->localecode;
    }

    /**
     * @param mixed $localecode
     */
    public function setLocalecode($localecode)
    {
        $this->localecode = $localecode;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return mixed
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param mixed $cancelUrl
     */
    public function setCancelUrl($cancelUrl)
    {
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * @return mixed
     */
    public function getButtonSource()
    {
        return $this->buttonSource;
    }

    /**
     * @param mixed $buttonSource
     */
    public function setButtonSource($buttonSource)
    {
        $this->buttonSource = $buttonSource;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getPayerId()
    {
        return $this->payerId;
    }

    /**
     * @param mixed $payerId
     */
    public function setPayerId($payerId)
    {
        $this->payerId = $payerId;
    }

    /**
     * @return string
     */
    public function getPaypalSandboxUrl()
    {
        return $this->paypalSandboxUrl;
    }

    /**
     * @param string $paypalSandboxUrl
     */
    public function setPaypalSandboxUrl($paypalSandboxUrl)
    {
        $this->paypalSandboxUrl = $paypalSandboxUrl;
    }

    /**
     * @return string
     */
    public function getPaypalUrl()
    {
        return $this->paypalUrl;
    }

    /**
     * @param string $paypalUrl
     */
    public function setPaypalUrl($paypalUrl)
    {
        $this->paypalUrl = $paypalUrl;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;
    }

    function setExpressCheckout(){
        $this->request['METHOD'] = 'setExpressCheckout';
        $response = $this->exec();
        return $response;
    }

    function getExpressCheckoutDetails(){
        $this->request['METHOD'] = 'getExpressCheckoutDetails';
        $this->request['TOKEN'] = $this->getToken();
        $response = $this->exec();
        return $response;
    }

    function doExpressCheckoutPayment(){
        $this->request['METHOD'] = 'doExpressCheckoutPayment';
        $this->request['TOKEN'] = $this->getToken();
        $this->request['PAYERID'] = $this->getPayerId();
        $response = $this->exec();
        return $response;
    }

    function expressCheckout(){
        if(isset($_GET['token'])){
            //2-getExpressCheckoutDetails
            $this->setToken($_GET['token']);
            $response = $this->getExpressCheckoutDetails();

            if (isset($response['TOKEN']) && $response['ACK'] == 'Success') {
                $this->payerId = $response['PAYERID'];
                //3-doExpressCheckoutPayment
                $response = $this->doExpressCheckoutPayment();
                return $response;
            } else{
                return array('error'=>$response);
            }
        }else {
            //1-setExpressCheckout
            $response = $this->setExpressCheckout();
            if (isset($response['ACK']) && $response['ACK'] == 'Success') {
                $url =  $this->isSandbox() ? $this->getPaypalSandboxUrl() : $this->getPaypalUrl();
                $this->setToken($response['TOKEN']);
                $query = array('cmd' => '_express-checkout', 'useraction' => 'commit', 'token' => $this->getToken());
                //header('Location: ' . $url . '?' . http_build_query($query));
                $redirectURL = sprintf('%s?%s', $url, http_build_query($query));
                //carrega a página de redirecionamento
	            require 'redirect.php';
            } else{
                return array('error'=>$response);
            }
        }
    }

    function getRequest(){
        return $this->request;
    }
    /**
     * @param Seller array $sellers
     */
    function setRequest($sellers){
        if(!is_array($sellers)){
            $sellers = array($sellers);
        }

        $this->request = array(
            'HDRIMG' =>$this->getHeaderImage(),
            'LOCALECODE' => $this->getLocalecode(),
            'USER' => $this->getUser(),
            'PWD' => $this->getPassword(),
            'SIGNATURE' => $this->getSignature(),
            'VERSION' => $this->getVersion(),
            'METHOD'=> $this->getMethod(),
            'RETURNURL' => $this->getReturnUrl(),
            'CANCELURL' => $this->getCancelUrl(),
            'BUTTONSOURCE' => $this->getButtonSource()
        );

        $countSeller = 0;
        $countItem = 0;
        foreach($sellers as $seller){
            $this->request['PAYMENTREQUEST_'.$countSeller.'_PAYMENTACTION'] = $seller->getPaymentAction();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_AMT'] = $seller->getAmount();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_CURRENCYCODE'] = $seller->getCurrencyCode();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_ITEMAMT'] = $seller->getItemAmount();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_INVNUM'] = $seller->getReference();

            foreach($seller->getItems() as $item){
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_NAME'.$countItem] = $item->getName();
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_DESC'.$countItem] = $item->getDescription();
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_AMT'.$countItem] = $item->getAmount();
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_QTY'.$countItem] = $item->getQuantity();
                $countItem++;
            }
            $countSeller++;
        }
    }

    public function send(){
        $response = null;
        switch($this->getMethod()){
            case 'setExpressCheckout':
                $response = $this->setExpressCheckout();
                break;
            case 'getExpressCheckoutDetails':
                $response = $this->getExpressCheckoutDetails();
                break;
            case 'doExpressCheckoutPayment':
                $response = $this->doExpressCheckoutPayment();
                break;
            case 'expressCheckout':
                $response = $this->expressCheckout();
                break;
        }
        return $response;
    }

    public function exec(){
        $apiEndpoint  = 'https://api-3t.' . ($this->isSandbox() ? 'sandbox.': null);
        $apiEndpoint .= 'paypal.com/nvp';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request));

        $response = urldecode(curl_exec($curl));
        $response = $this->sanitize($response);

        curl_close($curl);
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