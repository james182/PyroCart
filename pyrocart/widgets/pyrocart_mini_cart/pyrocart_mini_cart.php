<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package 		PyroCMS
 * @subpackage 		Shop Mini Cart Widget
 * @author          James Lawrie
 *
 * Show mini shopping in your site
 */

class Widget_Products_mini_cart extends Widgets
{
	public $title = 'Products Mini Cart';
	public $description = 'Display Shopping cart.';
	public $author = 'James Lawrie';
	public $website = 'unbornmedia.com.au';
	public $version = '1.0';


        public function run()
        {
            $this->load->library('cart');		
			$mini_cart_contents = $this->cart->contents();			
					
			return array('mini_cart_contents' => $mini_cart_contents);
			
        }
}