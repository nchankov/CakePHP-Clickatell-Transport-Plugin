<?php
/**
 * https://github.com/nchankov/CakePHP-Clickatell-Transport-Plugin
 * 
 * Transport class for sending message using clickatell.com SMS gateway.
 * Applies to CakePHP 2.4+ since CakeEmail support custom validation since then.
 * If you still using version <2.4 update only the CakeEmail Class if necessary. 
 * 
 * Resources:
 * http://book.cakephp.org/2.0/en/core-utility-libraries/email.html#using-transports
 * https://cakephp.lighthouseapp.com/projects/42648/tickets/3891-cakeemail-cant-set-invalid-rfc-email-address
 * https://raw.github.com/cakephp/cakephp/2.4/lib/Cake/Network/Email/CakeEmail.php
 * 
 * Make sure that the phone which you are sending to is without leading zero and containing country prefix. For example for UK phone should be like: 447711223344
 * 
 * @author Nik Chankov
 */
class ClickatellTransport extends AbstractTransport {
/**
 * Send mail
 *
 * @param CakeEmail $email CakeEmail
 * @return boolean
 */
	public function send(CakeEmail $email) {
		$eol = PHP_EOL;
		if (isset($this->_config['eol'])) {
			$eol = $this->_config['eol'];
		}
		$to = implode(',', array_keys($email->to()));
		$message = implode($eol, $email->message());

		$params = isset($this->_config['additionalParameters']) ? $this->_config['additionalParameters'] : null;
		
		if($this->_clickatell($to, urlencode($message), $email->config())){
			return true;
		} else {
			return false;
		}
	}

/**
 * Internal method which handling the actual send
 * @param  string $phone   phone number. Make sure that it contain only digits, doesn't have leading 0 and has country prefix.
 * @param  string $message message which need to be send. In case of log messages clickatell support concatenation
 * @return bollean true if the message has been send sucessfully or false on error.
 */
	protected function _clickatell($phone, $message, $options=array()){
	    $concat = (isset($options['concatenate']) ? 'concat='.$options['concatenate'].'&' : '');
	    // auth url
	    $url = $options['baseurl'].'/http/auth?user='.$options['username'].'&password='.$options['password'].'&api_id='.$options['api_id'];
	 	try{
		    // do auth call
		    $ret = file($url);
		    $sess = explode(":",$ret[0]);
		    if($sess[0] == "OK") {
		        $sess_id = trim($sess[1]);
		        $url = $options['baseurl'].'/http/sendmsg?'.$concat.'session_id='.$sess_id.'&to='.$phone.'&text='.$message;	 
		        if($options['debug'] == true){
		        	echo $url;
		        	return true;
		        } else {
		        	// do sendmsg call
		            $ret = file($url);
			        $send = explode(":",$ret[0]);
			        if ($send[0] == "ID") {
			            return true;
			        } else {
			        	return false;
			        }
		        }
		    } else {
		        return false;
		    }
		} catch (Exception $e) {
			error_log('It couldn\'t send the clickatell message');
			return false;
		}
	}
}