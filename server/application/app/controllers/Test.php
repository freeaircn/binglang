<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 17:59:33
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-06 19:04:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->data['a'] = 'Hello World';
		$t = time();
		$d = date("Y-m-d H:i:s",$t);
		$this->data['t'] = $t;
		$this->data['d'] = $d;
		$this->load->view('test_message', $this->data);
	}
}
