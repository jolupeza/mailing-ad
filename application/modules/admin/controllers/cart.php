<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends MY_Controller {

	public function add()
	{
		$data = array(
			'id'	=>	'42',
			'name'	=>	'Pants',
			'qty'	=>	1,
			'price'	=>	19.99,
			'options'	=>	array('Size' => 'medium')
		);
		$this->cart->insert($data);
		echo "add() called";
	}

	public function show()
	{
		$cart = $this->cart->contents();
		echo '<pre>';
		print_r($cart);
	}

	public function update()
	{
		$data = array(
			'row'		=>	'',
			'qty'		=>	'1'
		);
		$this->cart->update($data);
	}

	public function total()
	{
		echo $this->cart->total();
	}

	public function remove()
	{
		$data = array(
			'row'	=>	'',
			'qty'	=>	'0'
		);
		$this->cart->update($data);
	}

	public function destroy()
	{
		$this->cart->destroy();
	}
}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */ ?>