<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Productitems Module - Create and manage products
 *
 * @author 	PyroCMS Development Team
 * @package 	PyroCMS
 * @subpackage 	Productitems
 * @category	Modules
 */
class Products extends Public_Controller
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

		$this->data->product_categories = $this->pyrocart_m->getParentCategories();
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
            if($this->settings->products_featured == 1)
            {
                //Enabled
                $params['order']='created_on DESC';
                $this->data->products = $this->pyrocart_m->getProducts($params,true);
                $this->data->cat_breadcrumb = 'Featured';
                $this->data->total_result = count($this->data->products );
            }else{
                //Disabled
                $categoryid = 1;
                $params['order']='created_on DESC';
                $params['categoryid'] = $categoryid;
                if($categoryid!=''){
                    $this->data->cat_breadcrumb = $this->pyrocart_m->getCatBC($categoryid);
                }

                $this->data->products = $this->products_m->getProducts($params);
                $this->data->total_result = count($this->data->products );
            }
            
            $this->template->build('index', $this->data);

	}
	public function search($categoryid = '')
	{
            $params['order']='created_on DESC';
            $params['categoryid'] = $categoryid;
            if($categoryid!=''){
                $this->data->cat_breadcrumb = $this->products_m->getCatBC($categoryid);
            }

            $this->data->products = $this->products_m->getProducts($params);
            $this->data->total_result = count($this->data->products );
            $this->template->build('index', $this->data);
	}



	public function details($productId=false)
	{
		if(!$productId){redirect('products');}

		$this->data->product = $this->products_m->getProduct($productId);
		$this->data->cat_breadcrumb = $this->products_m->getCatBC($this->data->product->categoryId);

		$this->template
		->append_metadata( js('jquery.countdown.min.js', 'products') )
		->append_metadata( css('jquery.countdown.css', 'products') )
		->append_metadata( js('jquery.colorbox-min.js', 'products') )
		->append_metadata( css('colorbox.css', 'products') )
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
