<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_validation extends CI_Form_validation {
	
	public function error_array() {
        return $this->_error_array;
    }

}