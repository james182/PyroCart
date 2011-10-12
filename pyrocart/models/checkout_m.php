<?php

class Checkout_m extends CI_Model{

    public function __construct()
    {
        // Call the parent's constructor
        parent::__construct();
        $this->load->library('helpfunctions');
        $this->load->library('cart');
    }
    
    function retrieve_countries()
    {
        $query = $this->db->get_where('products_countries');
        return $query->result_array();
    }
    
    function retrieve_zones()
    {
        $query = $this->db->get_where('products_states');
        return $query->result_array();
    }
    
    function retrieve_shipping_fixed($qty)
    {
        if($qty <= 2)
        {
            $total_qty = $qty;
        }else{
            $total_qty = '3';
        }
        
        $query = $this->db->get_where('products_shipping_fixed', array("quantity" => $total_qty));
        return $query->result();
    }
    
    
    function retrieve_shipping_weight($country_id)
    {
        $query = $this->db->get_where('products_shipping_weight', array("country_id" => $country_id));
        return $query->result();
    }
    

}//end class
?>