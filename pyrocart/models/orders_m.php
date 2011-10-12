<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orders_m extends CI_Model
{
	//var $email_from = 'admin@localhost'; // this is set by controller when used
	public function __construct()
	{
		// Call the parent's constructor
		parent::__construct();
		$this->load->library('helpfunctions');

	}

	function countOrders($params = array())
	{
		$s_status = $this->input->get_post('s_status');

		if($s_status){$this->db->where('status',$s_status);}

		$s_name = $this->input->get_post('s_name');
		if($s_name){$this->db->like('firstName',$s_name);$this->db->or_like('lastName',$s_name);}

		$this->db->select('id');
		$query = $this->db->get('product_orders');

		return $query->num_rows();
	}

	function getOrders($params = array())
	{

		if(isset($params['order'])) $this->db->order_by($params['order']);

		// Limit the results based on 1 number or 2 (2nd is offset)
		if(isset($params['limit']) && is_int($params['limit'])) $this->db->limit($params['limit']);
		elseif(isset($params['limit']) && is_array($params['limit'])) $this->db->limit($params['limit'][0], $params['limit'][1]);

		$s_status = $this->input->get_post('s_status');

		if($s_status){$this->db->where('status',$s_status);}

		$s_name = $this->input->get_post('s_name');
	if($s_name!=''){$this->db->like('firstName',$s_name);$this->db->or_like('lastName',$s_name);}


		$query = $this->db->get('product_orders');

		if ($query->num_rows() == 0)
		{
			return array();
		}
		else
		{
			return $query->result();
		}

	}
	function getOrderItems($order_id)
		{
			$query = $this->db->get_where('product_order_items',array('order_id'=>$order_id));
			return $query->result();

		}
	function countOrderItems($order_id)
		{
			$this->db->select('sum(quantity) as total_items');
			$this->db->where('order_id',$order_id);
			$this->db->group_by('order_id');
			$query = $this->db->get('product_order_items');
			return $query->row()->total_items;


		}
	function createOrder()
	{
		$this->load->helper('date');
		$DATA = $this->helpfunctions->make_insert_array('product_orders',$_REQUEST);
		$DATA['created_on']=now();
		$this->db->insert('product_orders', $DATA);
		$insertId = $this->db->insert_id();
		return $insertId;
	}
	function editOrder($id,$input = array())
	{
		$this->load->helper('date');
		$this->load->library('helpfunctions');
		$DATA = $this->helpfunctions->make_insert_array('product_orders',$input);
		$this->db->where('id', $id);
		$this->db->update('product_orders', $DATA);
		return true;

	}

	function getOrder($id = '')
		{
				$query = $this->db->get_where('product_orders', array('id'=>$id));
				if ($query->num_rows() == 0)
				{ return FALSE;	}
				else{ return $query->row(); }
		}



	function process_inventory_stock($v_id,$qty)
		{
			$update_query = "UPDATE products SET stock = stock - $qty WHERE id = $v_id";
			$this->db->query($update_query);
		}

	function process_order()
		{
			$this->load->library('cart');

			$order_id = $this->createOrder();
	 		foreach($this->cart->contents() as $items){
	 			$ORDER_DATA = array();
	 			$ORDER_DATA['order_id'] = $order_id;
	 			$ORDER_DATA['product_id'] =  $items['id'];
	 			$ORDER_DATA['price'] = $items['price'];
	 			$ORDER_DATA['quantity'] = $items['qty'];

				$this->process_inventory_stock($items['id'],$items['qty']);

	 			$ORDER_DATA['subtotal'] = $items['subtotal'];
	 			$ORDER_DATA['description'] = $items['name'];
	 			$this->db->insert('product_order_items',$ORDER_DATA);
	 		}



			$this->cart->destroy();



		}

    function order_status_dropdown($key='')
    	{

    		$statuses = array(
				'orderPlaced' => 'Order Placed',
				'pendingPayment' => 'Pending Payment',
				'processing' => 'Processing',
				'packing' => 'Packing',
				'shipped' => 'Shipped',
			);
			if($key!=''){return $statuses[$key];}
			return $statuses;

    	}



}
?>
