<?php

namespace App;

class CurrencySymbol {
	public $symbol;

	public function __construct($code){
      $this->symbol($code);
	}

	public function symbol($code) {
	    $code = strtoupper($code);

	    $currencylist = [
	        'USD' => '$',
	        'NGN' => '₦',
	        'INR' => '₹',
	    ];

	    if(empty($currencylist[$code])) {
	        return $this->symbol = $code;
	    } else {
	        return $this->symbol = $currencylist[$code];
	    }
	}
}
