<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pyrocart module
 *
 * @author James Lawrie
 * @package PyroCMS
 * @subpackage pyrocart module
 * @category Modules
 */
class Cart extends Public_Controller
{

	/**
	 * Constructor method
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::Public_Controller();
		$this->load->model('pyrocart_m');
		$this->load->model('images_m');
		$this->load->model('cart_m');
		$this->lang->load('pyrocart');
		$this->config->load('pyrocart_config');
		$this->load->library('cart');
		$this->data->product_categories = $this->pyrocart_m->get_parent_categories();
		$this->data->cat_breadcrumb = '';
                //$this->template->set_layout('store.html');
		$this->template->set_partial('sidebar', 'partials/sidebar.php',$this->data);
                $this->template->append_metadata( js('cart.js', 'pyrocart') );
                $this->template->append_metadata( css('pyrocart.css', 'pyrocart') );
	}

	/**
	 * List Active products
	 *
	 * @access public
	 * @return void
	 */

	function add_cart_item(){

            if($this->cart_m->validate_add_cart_item() == TRUE){

                // Check if user has javascript enabled
                if($this->input->post('ajax') != '1'){
                        redirect('pyrocart/cart/show_cart'); // If javascript is not enabled, reload the page with new data
                }else{
                        echo 'true'; // If javascript is enabled, return true, so the cart gets updated
                }
            }
	}

	function update_cart(){
		$this->cart_m->validate_update_cart();
		redirect('pyrocart/cart/show_cart');
	}
        
        
        
        
        function delete_cart_item(){
            
            $item_id = trim($this->input->post('item_id'));
            $cart_id = $this->cart_m->remove_cart_item($item_id);
            
            $array = array('results' => $cart_id);
            
            echo json_encode($array);
	}
        
        
        

	function show_cart(){
		$this->template->build('cart/cart', $this->data);
	}

	function empty_cart(){
		$this->cart->destroy();
		redirect('products/cart/show_cart');
	}

	public function cart_checkout()
	{
		if($this->cart->total_items() < 1) {
			$this->session->set_flashdata('error', 'You must have items in your cart to checkout!');
			redirect('cart');
		}
		$this->load->helper('countries');
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->checkout_default_validation_rules);
		/*if(!$this->input->post('same_billing')) $this->form_validation->set_rules($this->checkout_billing_validation_rules);*/
		$this->form_validation->set_rules($this->checkout_billing_validation_rules);
		// Valid form data?
		if ($this->form_validation->run())
		{
			foreach($this->checkout_default_validation_rules  as $rule)
			{
					$_SESSION['checkout'][$rule['field']] = $this->input->post($rule['field']);
			}

			/*if(!$this->input->post('same_billing')) {
				foreach($this->checkout_billing_validation_rules as $rule)
				{
					$_SESSION['checkout'][$rule['field']] = $this->input->post($rule['field']);
				}
			}*/

			foreach($this->checkout_billing_validation_rules as $rule)
			{
				$_SESSION['checkout'][$rule['field']] = $this->input->post($rule['field']);
			}

			$_SESSION['checkout']['page'] = 'overview';
			redirect('cart/overview');
		}

		// Required for validation
		foreach($this->checkout_default_validation_rules as $rule)
		{
				$this->data->form->{$rule['field']} = $this->input->post($rule['field']) ? $this->input->post($rule['field']) : $_SESSION['checkout'][$rule['field']];
		}

		/*if(!$this->input->post('same_billing')) {
			foreach($this->checkout_billing_validation_rules as $rule)
			{
					$this->data->form->{$rule['field']} = $this->input->post($rule['field']) ? $this->input->post($rule['field']) : $_SESSION['checkout'][$rule['field']];
			}
		}*/

		foreach($this->checkout_billing_validation_rules as $rule)
		{
			$this->data->form->{$rule['field']} = $this->input->post($rule['field']) ? $this->input->post($rule['field']) : $_SESSION['checkout'][$rule['field']];
		}

		$this->data->shipping_cost = $this->data->country ? $this->stores_m->get_cart_shipping($this->data->country) : $this->stores_m->get_cart_shipping('US');
		$this->template->build('cart_checkout', $this->data);
	}




}
?>
