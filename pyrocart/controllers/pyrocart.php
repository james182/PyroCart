<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pyrocart module
 *
 * @author James Lawrie
 * @package PyroCMS
 * @subpackage pyrocart module
 * @category Modules
 */

class Pyrocart extends Public_Controller
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
		$this->lang->load('pyrocart');
		$this->config->load('pyrocart_config');

		$this->data->product_categories = $this->pyrocart_m->get_parent_categories();
		$this->data->cat_breadcrumb = '';
                //$this->template->set_layout('store.html');
		$this->template->set_partial('sidebar', 'partials/sidebar.php',$this->data);
		$this->template->append_metadata( css('pyrocart.css', 'pyrocart') );

	}

	/**
	 * List Active products
	 *
	 * @access public
	 * @return void
	 */

	public function index()
	{
            if($this->settings->pyrocart_featured == 1)
            {
                //Enabled
                $params['order']='created_on DESC';
                $this->data->products = $this->pyrocart_m->get_products($params,true);
                $this->data->cat_breadcrumb = 'Featured';
                $this->data->total_result = count($this->data->products );
            }else{
                //Disabled
                $categoryid = 1;
                $params['order']='created_on DESC';
                $params['categoryid'] = $categoryid;
                if($categoryid!=''){
                    $this->data->cat_breadcrumb = $this->pyrocart_m->get_cat_breadcrumb($categoryid);
                }

                $this->data->products = $this->pyrocart_m->get_products($params);
                $this->data->total_result = count($this->data->products );
            }
            
            $this->template->build('index', $this->data);

	}
	public function search($categoryid = '')
	{
            $params['order']='created_on DESC';
            $params['categoryid'] = $categoryid;
            if($categoryid!=''){
                $this->data->cat_breadcrumb = $this->pyrocart_m->get_cat_breadcrumb($category_id);
            }

            $this->data->products = $this->pyrocart_m->get_products($params);
            $this->data->total_result = count($this->data->products );
            $this->template->build('index', $this->data);
	}



	public function details($product_id=false)
	{
		if(!$product_id){redirect('pyrocart');}

		$this->data->product = $this->pyrocart_m->get_product($product_id);
		$this->data->cat_breadcrumb = $this->pyrocart_m->get_cat_breadcrumb($this->data->product->category_id);

		$this->template
		->append_metadata( js('jquery.countdown.min.js', 'pyrocart') )
		->append_metadata( css('jquery.countdown.css', 'pyrocart') )
		->append_metadata( js('jquery.colorbox-min.js', 'pyrocart') )
		->append_metadata( css('colorbox.css', 'pyrocart') )
		->build('details', $this->data);

	}

	public function rate($productId='',$rate='')
	{

		$update = "update vote set counter = counter + 1, value = value + ".$rate." where product_id = $productId";

		$res = $this->db->query($update);
		//print_r($res);exit;
		if($res==1){
			$insert = "insert into vote (counter,value,product_id) values ('1','".$rate."',$productId)";
			$result =$this->db->query($insert);
		}

	}

	public function getrate($productId='')
	{

		$sql= "select * from vote where product_id = $productId";
		$query=$this->db->query($sql);

		// set width of star
		$rating = 0;
		if($query->num_rows()>0){
			$row = $query->first_row();
			$rating =  (@round($row->value/ $row->counter,1)) * 20;
		}

		echo $rating;
		exit;

	}




}
?>
