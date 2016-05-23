<?php
namespace easyPaypal;

class Checkout{

    private $method;
    private $token;
    private $payerId;
    private $request;
    private $params;

    /**
     * Checkout constructor.
     * @param string $method
     * @param string $headerImage
     */
    public function __construct($method='setExpressCheckout'){
        $this->method = $method;
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

    function exec(){
        $this->request->setParams($this->params);
        return $this->request->exec();
    }

    function setExpressCheckout(){
        $this->params['METHOD'] = 'setExpressCheckout';
        $response = $this->exec();
        return $response;
    }

    function getExpressCheckoutDetails(){
        $this->params['METHOD'] = 'getExpressCheckoutDetails';
        $this->params['TOKEN'] = $this->getToken();
        $response = $this->exec();
        return $response;
    }

    function doExpressCheckoutPayment(){
        $this->params['METHOD'] = 'doExpressCheckoutPayment';
        $this->params['TOKEN'] = $this->getToken();
        $this->params['PAYERID'] = $this->getPayerId();
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
                $url =  $this->request->isSandbox() ? $this->request->getPaypalSandboxUrl() : $this->request->getPaypalUrl();
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

    /**
     * @return Request object
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($sellers)
    {
        if(!is_array($sellers)){
            $sellers = array($sellers);
        }

        /*
        if($this->getNotifyUrl()){
            $this->params['NOTIFYURL'] = $this->getNotifyUrl();
        }*/

        $countSeller = 0;
        $countItem = 0;
        foreach($sellers as $seller){
            $this->params['PAYMENTREQUEST_'.$countSeller.'_PAYMENTACTION'] = $seller->getPaymentAction();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_AMT'] = $seller->getAmount();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_CURRENCYCODE'] = $seller->getCurrencyCode();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_ITEMAMT'] = $seller->getItemAmount();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_INVNUM'] = $seller->getReference();

            foreach($seller->getItems() as $item){
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_NAME'.$countItem] = $item->getName();
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_DESC'.$countItem] = $item->getDescription();
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_AMT'.$countItem] = $item->getAmount();
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_QTY'.$countItem] = $item->getQuantity();
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