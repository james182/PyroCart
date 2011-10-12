<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Productitem module
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Productitem module
 * @category Modules
 */
class Admin_orders extends Admin_Controller
{
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	private $validation_rules = array();

	/**
	 * Constructor method
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::Admin_Controller();
		$this->load->model('products_m');
		$this->load->model('images_m');
		$this->load->model('orders_m');
		$this->lang->load('products');
		$this->config->load('products_config');
		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
		$this->template->append_metadata(js('form.js', 'products'));

		// Load and set the validation rules
		$this->load->library('form_validation');
		$this->validation_rules = array(
			array(
				'field' => 'status',
				'label' => 'Order status',
				'rules'	=> 'trim|required'
			),
			array(
				'field' => 'firstName',
				'label' => 'First name',
				'rules' => 'trim'
			),
			array(
				'field' => 'lastName',
				'label' =>'Last name',
				'rules' => 'trim'
			),
				array(
				'field' => 'address1',
				'label' => 'Address 1',
				'rules' => 'trim'
			),
			array(
				'field' => 'address2',
				'label' => 'Address 2',
				'rules' => 'trim'
			),

		 	array(
				'field' => 'city',
				'label' => 'City',
				'rules' => 'trim'
			),

			array(
				'field' => 'state',
				'label' => 'State',
				'rules' => 'trim'
			),

			array(
				'field' => 'zip',
				'label' => 'Zip',
				'rules' => 'trim'
			),

			array(
				'field' => 'amount',
				'label' =>'Amount billed',
				'rules' => 'trim'
			),

			array(
				'field' => 'paymentType',
				'label' =>'Payment type',
				'rules' => 'trim'
			),

			array(
				'field' => 'tax',
				'label' =>'Tax',
				'rules' => 'trim'
			),
			array(
				'field' => 'payment_method',
				'label' =>'Payment method',
				'rules' => 'trim'
			),
		);
		$this->form_validation->set_rules($this->validation_rules);

		$this->data->order_status = $this->orders_m->order_status_dropdown();
	}


	// Admin: Show products
	function index()
	{
		$this->template->set_partial('filters', 'admin/partials/search_order_form');
		// Create pagination links
		$total_rows = $this->orders_m->countOrders();
		$this->data->pagination = create_pagination('products/orders/admin', $total_rows);

		// Using this data, get the relevant results
		$this->data->orders = $this->orders_m->getOrders(array('order'=>'created_on DESC', 'limit' => $this->data->pagination['limit']));
		$this->template
		->append_metadata( js('functions.js', 'products') )
		->build('admin/orders/index', $this->data);
	}

	function product_order_items_details($order_id)
		{
			$this->data->order_items  = $this->orders_m->getOrderItems($order_id);
			$this->template->set_layout(FALSE);
			$this->template->build('admin/orders/order_item_details',$this->data);

		}
// Admin: edit a Productitem
	function manage($id = 0)
		{

			if ($this->form_validation->run())
			{
				if ($this->orders_m->editOrder($id,$_POST))
				{
					$this->session->set_flashdata('success', sprintf('Successfulle edited order from', $this->input->post('firstName')));
					redirect('products/orders/admin');
				}
				else
				{
					$this->session->set_flashdata(array('error'=> 'Error in managing order'));
				}
			}

			$order_status = array(''=>'Select');



			$this->data->order = $this->orders_m->getOrder($id);

			if ($this->data->order)
			{

				// Load WYSIWYG editor
				$this->data->fields=$this->validation_rules;
				//$this->data->productitem =& $productitem;
				$this->template->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) );
				$this->template
				->append_metadata( js('form.js', 'products') )
				->build('admin/orders/manage', $this->data);
			}
			else
			{
				redirect('products/orders/admin');
			}
		}







}
?>
