<?php
namespace easyPaypal;

class Checkout extends Request{

    private $method;
    private $token;
    private $payerId;
    private $headerImage;

    /**
     * Checkout constructor.
     * @param $user
     * @param $password
     * @param $signature
     * @param $sandbox
     * @param string $method
     * @param $returnUrl
     * @param $cancelUrl
     * @param string $headerImage
     * @param string $buttonSource
     * @param string $localecode
     * @param string $version
     */
    public function __construct($user, $password, $signature, $sandbox, $method='setExpressCheckout', $returnUrl, $cancelUrl, $headerImage='', $buttonSource='BR_EC_EMPRESA', $localecode='pt_BR', $version='73.0', $currencyCode='BRL', $countryCode='BR'){
        parent::__construct($sandbox, $user, $password, $signature, $localecode, $returnUrl, $cancelUrl, $buttonSource, $version, $currencyCode, $countryCode);
        $this->method = $method;
        $this->headerImage = $headerImage;
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

        /*
        if($this->getNotifyUrl()){
            $this->request['NOTIFYURL'] = $this->getNotifyUrl();
        }*/

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

}