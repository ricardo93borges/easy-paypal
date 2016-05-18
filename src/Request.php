<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 18/05/16
 * Time: 14:28
 */

namespace easyPaypal;


class Request
{

    private $sandbox;
    private $user;
    private $password;
    private $signature;
    private $localecode;
    private $returnUrl;
    private $cancelUrl;
    private $buttonSource;
    private $version;
    private $currencyCode;
    private $countryCode;
    private $paypalSandboxUrl;
    private $paypalUrl;

    /**
     * Request constructor.
     * @param $sandbox
     * @param $user
     * @param $password
     * @param $signature
     * @param $request
     * @param $localecode
     * @param $returnUrl
     * @param $cancelUrl
     * @param $buttonSource
     */
    public function __construct($sandbox, $user, $password, $signature, $localecode, $returnUrl, $cancelUrl, $buttonSource, $version, $currencyCode, $countryCode)
    {
        $this->sandbox = $sandbox;
        $this->user = $user;
        $this->password = $password;
        $this->signature = $signature;
        $this->localecode = $localecode;
        $this->returnUrl = $returnUrl;
        $this->cancelUrl = $cancelUrl;
        $this->buttonSource = $buttonSource;
        $this->version = $version;
        $this->currencyCode = $currencyCode;
        $this->countryCode = $countryCode;
        $this->paypalSandboxUrl = 'https://www.sandbox.paypal.com/br/cgi-bin/webscr';
        $this->paypalUrl = 'https://www.paypal.com/br/cgi-bin/webscr';
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
     * @return mixed
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param mixed $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
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
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param mixed $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
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