<?php
/*    $str = "String";
    $str = utf8_encode($str);
    print mb_detect_encoding($str);
    print "\n";
    $str = mb_convert_encoding($str, 'utf-8', mb_detect_encoding($str));
    print mb_detect_encoding($str);
    print "\n";
    die();
*/
    function toUtf8($str){
        return mb_convert_encoding($str, 'utf-8', mb_detect_encoding($str));
    }

	/**
     * Verifica se uma notificação IPN é válida, fazendo a autenticação
     * da mensagem segundo o protocolo de segurança do serviço.
     *
     * @param array $message Um array contendo a notificação recebida.
     * @return boolean TRUE se a notificação for autência, ou FALSE se não for.
     *
     */
	function isIPNValid(array $message){
        $endpoint = 'https://www.paypal.com';
        if (isset($message['test_ipn']) && $message['test_ipn'] == '1') {
            $endpoint = 'https://www.sandbox.paypal.com';
        }

        $endpoint .= '/cgi-bin/webscr?cmd=_notify-validate';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($message));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $errno = curl_errno($curl);

        curl_close($curl);

        return empty($error) && $errno == 0 && $response == 'VERIFIED';
    }

    function test(){
        $endpoint = 'http://test.iporto.com/easy-paypal/ipn.php';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array()));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $errno = curl_errno($curl);

        curl_close($curl);

        print_r($response);
    }

    function ipnLog($data){
        print_r($data);
        print "\n";
        $w = file_put_contents('log.log', utf8_encode($data), FILE_APPEND);
        if(!$w){
            "nothing writen \n";
        }else{
            print $w;
        }
    }

    /*RECEIVE NOTIFICATIONS*/

/**
 * TODO
 * Converter retorno da requisição de windows-1252 para utf-8
 */

    //Email da conta do vendedor, que será utilizada para verificar o destinatário da notificação.
	$receiver_email = 'ricardo_borges26-facilitator_api1.hotmail.com';
    //As notificações sempre serão via HTTP POST, então verificamos o método
	//utilizado na requisição, antes de fazer qualquer coisa.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        ipnLog(mb_detect_encoding($_SERVER['REQUEST_METHOD']));
        ipnLog($_SERVER['REQUEST_METHOD']);
	    //Antes de trabalhar com a notificação, precisamos verificar se ela
	    //é válida e, se não for, descartar.
	    if (!isIPNValid($_POST)) {
            ipnLog("invalid");
            return;
	    }
	    //Se chegamos até aqui, significa que estamos lidando com uma
	    //notificação IPN válida. Agora precisamos verificar se somos o
	    //destinatário dessa notificação, verificando o campo receiver_email.
        ipnLog($receiver_email);
	    if ($_POST['receiver_email'] == $receiver_email) {
            foreach($_POST as $p) {
                ipnLog(toUtf8($p));
            }
	        //Está tudo correto, somos o destinatário da notificação, vamos
	        //gravar um log dessa notificação.
	        /*
	         *
	         *
	         */
	        //if (logIPN($pdo, $_POST)) {
	            //Log gravado, podemos seguir com as regras de negócio para
	            //essa notificação.
	        //}
	    }
	}

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        ipnLog($_SERVER['REQUEST_METHOD']);
        test();
    }