<?php

class Cart_m extends CI_Model{

 public function __construct()
	{
		// Call the parent's constructor
		parent::__construct();
		$this->load->library('helpfunctions');
		$this->load->library('cart');


	}
// Function to retrieve an array with all product information
	function retrieve_products(){
		$query = $this->db->get('pyrocart');
		return $query->result_array();
	}

    // Updated the shopping cart
    function validate_update_cart(){

        // Get the total number of items in cart
        $total = $this->cart->total_items();

        // Retrieve the posted information
        $item = $this->input->post('rowid');
        $qty = $this->input->post('qty');
        $cart_ids = $this->input->post('cart_ids');


        if(!$cart_ids)
        {
            $cart_ids = array();
        }
        // Cycle true all items and update them
        for($i=0;$i < $total; $i++)
        {
            // Create an array with the products rowid's and quantities.
            if(in_array($item[$i],$cart_ids))
            {
                $data = array(
                    'rowid' => $item[$i],
                    'qty'   => 0
                    );
            }else{
                $data = array(
                    'rowid' => $item[$i],
                    'qty'   => $qty[$i]
                    );

            }

            // Update the cart with the new information
            $this->cart->update($data);
        }
    }
    
    
    
    //Remove cart item
    function remove_cart_item($item_id){
        $data = array('rowid' => $item_id, 'qty' => 0);
        return $this->cart->update($data);
    } 
    
    
    
    // Add an item to the cart
    function validate_add_cart_item(){

		$id = $this->input->post('product_id'); // Assign posted product_id to $id
		$qty = $this->input->post('quantity'); // Assign posted quantity to $qty

		$this->db->where('id', $id); // Select where id matches the posted id
		$query = $this->db->get('pyrocart', 1); // Select the products where a match is found and limit the query by 1




		if($query->num_rows > 0){



			foreach ($query->result() as $row)
			{
			    $data = array(
                                        'id'      => $row->id,
                                        'qty'     => $qty,
                                        'price'   => $row->price,
                                        'product_code'   => $row->product_code,
                                        'weight'   => $row->weight,
                                        'name'    => $row->title
                                        );

				$this->cart->insert($data);

				return TRUE;
			}

		// Nothing found! Return FALSE!
		}else{
			return FALSE;
		}
	}
	function get_gst()
		{
			$total_amount =  $this->cart->total();//$this->cart->format_number($this->cart->total());
			$count1 = $total_amount / 100;
			$count2 = $count1 * 10;
			//$count = number_format($count2, 0);
			return $count2;

		}
	function calculate_total()
		{	$gst = $this->get_gst();
			$total_amount =  $this->cart->total();
			$new_total = $total_amount + $gst;
			return $new_total;

		}
	// Needed?
	//function cart_content(){
	//	return $this->cart->total();
	//}
}//end class
?>