<?php
final class Smsc extends SmsGate {

	public function send() {
		$results = array();
		$to=$this->to;
		if ($this->copy) {
			$to .=",".$this->copy;
		}

		$data = array(
			//"id" => $this->order_id,
			"login" => $this->username,
			"psw" => $this->password,
			"sender" =>$this->from,
			"charset" => "utf-8",
			"fmt" => "3",
			"phones" => $to,
			"mes" => $this->message
		);

		$results[] = $this->process($data);

		return $results;
	}

	private function process($data) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://smsc.ru/sys/send.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		curl_close($ch);
		return $result;
	}
}
?>