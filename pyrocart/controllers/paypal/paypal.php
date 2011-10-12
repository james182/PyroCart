<?php
/**
 * PayPal_Lib Controller Class (Paypal IPN Class)
 *
 * Paypal controller that provides functionality to the creation for PayPal forms,
 * submissions, success and cancel requests, as well as IPN responses.
 *
 * The class requires the use of the PayPal_Lib library and config files.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Commerce
 * @author      Ran Aroussi <ran@aroussi.com>
 * @copyright   Copyright (c) 2006, http://aroussi.com/ci/
 *
 */

class Paypal extends Public_Controller {

	public function __construct()
	{
		parent::Public_Controller();
		$this->load->library('paypallib');
		$this->load->model('products_m');
		$this->load->model('images_m');
                $this->load->model('checkout_m');
			$this->load->model('cart_m');
		$this->lang->load('products');
		$this->config->load('products_config');

		$this->data->product_categories = $this->products_m->getParentCategories();
		$this->data->cat_breadcrumb = '';
                //$this->template->set_layout('store.html');
		$this->template->set_partial('sidebar', 'partials/sidebar.php',$this->data);
                $this->template->append_metadata( css('products.css', 'products') );
	}

	public function index()
	{
			$this->form();
	}

        public function shipping_prices()
        {
            $shipping_type = trim($this->input->post('shipping_type'));
                        
            switch($shipping_type)
            {
                case '0':
                    // NO
                    $total_qty = trim($this->input->post('total_qty'));           
                    $shipping_prices = $this->checkout_m->retrieve_shipping_fixed($total_qty);

                    $array = array('result' => $shipping_prices);

                    echo json_encode($array);
                    
                    break;
                
                case '1':
                    // YES
                    $country_id = trim($this->input->post('country_id'));           
                    $shipping_prices = $this->checkout_m->retrieve_shipping_weight($country_id);

                    $array = array('result' => $shipping_prices);

                    echo json_encode($array);
                    break;
            }
            
        }
        
        
	public function form()
	{
            $this->load->library('cart');
            $total_amount =  $this->cart->format_number($this->cart->total());
            $this->paypallib->add_field('business', 'tareq._1278301111_biz@gmail.com');
	    
            $this->paypallib->add_field('return', site_url('products/paypal/success'));
	    $this->paypallib->add_field('cancel_return', site_url('products/paypal/cancel'));
	    $this->paypallib->add_field('notify_url', site_url('products/paypal/ipn')); // <-- IPN url
	    $this->paypallib->add_field('custom', '1234567890'); // <-- Verify return

	    $this->paypallib->add_field('item_name', 'Paypal Test Transaction');
	    $this->paypallib->add_field('item_number', '6941');
	    $this->paypallib->add_field('amount', $total_amount);

		// if you want an image button use this:
		$this->paypallib->image('button_03.gif');

		// otherwise, don't write anything or (if you want to
		// change the default button text), write this:
		// $this->paypallib->button('Click to Pay!');

	    $this->data->paypal_form = $this->paypallib->paypal_form();
            $this->data->countries = $this->checkout_m->retrieve_countries();
            $this->data->zones = $this->checkout_m->retrieve_zones();
            $this->data->cart_contents = $this->cart->contents();

            $this->template
                    ->append_metadata( css('smart_wizard.css', 'products') )
                    ->append_metadata( css('checkout.css', 'products') )
                    ->append_metadata( js('jquery.smartWizard-2.0.min.js', 'products') )
                    ->append_metadata( js('jquery.chained.mini.js', 'products') )
                    ->append_metadata( js('checkout.js', 'products') )
                    ->build('paypal/form', $this->data);


	}

	public function auto_form()
	{
		$this->paypallib->add_field('business', 'PAYPAL@EMAIL.COM');
	    $this->paypallib->add_field('return', site_url('products/paypal/success'));
	    $this->paypallib->add_field('cancel_return', site_url('products/paypal/cancel'));
	    $this->paypallib->add_field('notify_url', site_url('products/paypal/ipn')); // <-- IPN url
	    $this->paypallib->add_field('custom', '1234567890'); // <-- Verify return

	    $this->paypallib->add_field('item_name', 'Paypal Test Transaction');
	    $this->paypallib->add_field('item_number', '6941');
	    $this->paypallib->add_field('amount', '197');

	    $this->paypallib->paypal_auto_form();
	}
	public function cancel()
	{
		$this->view('paypal/cancel');
	}

	public function success()
	{
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.

		// You could also simply re-direct them to another page, or your own
		// order status page which presents the user with the status of their
		// order based on a database (which can be modified with the IPN code
		// below).

		$this->data->pp_info = $this->input->post();
		$this->template->build('paypal/success', $this->data);
	}

	public function ipn()
	{
		// Payment has been received and IPN is verified.  This is where you
		// update your database to activate or process the order, or setup
		// the database with the user's order details, email an administrator,
		// etc. You can access a slew of information via the ipn_data() array.

		// Check the paypal documentation for specifics on what information
		// is available in the IPN POST variables.  Basically, all the POST vars
		// which paypal sends, which we send back for validation, are now stored
		// in the ipn_data() array.

		// For this example, we'll just email ourselves ALL the data.
		$to    = 'tareq.mist@gmail.com';    //  your email

		if ($this->paypallib->validate_ipn())
		{
			$body  = 'An instant payment notification was successfully received from ';
			$body .= $this->paypallib->ipn_data['payer_email'] . ' on '.date('m/d/Y') . ' at ' . date('g:i A') . "\n\n";
			$body .= " Details:\n";

			foreach ($this->paypallib->ipn_data as $key=>$value)
				$body .= "\n$key: $value";

			// load email lib and email results
			$this->load->library('email');
			$this->email->to($to);
			$this->email->from($this->paypallib->ipn_data['payer_email'], $this->paypallib->ipn_data['payer_name']);
			$this->email->subject('CI paypallib IPN (Received Payment)');
			$this->email->message($body);
			$this->email->send();
		}
	}
}
?>