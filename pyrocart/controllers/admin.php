<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pyrocart module
 *
 * @author James Lawrie
 * @package PyroCMS
 * @subpackage pyrocart module
 * @category Modules
 */

class Admin extends Admin_Controller
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
		$this->load->model('pyrocart_m');
		$this->load->model('images_m');
		$this->lang->load('pyrocart');
		$this->config->load('pyrocart_config');

		// Load and set the validation rules
		$this->load->library('form_validation');
		$this->validation_rules = array(
			array(
				'field' => 'title',
				'label' => lang('pyrocart.title'),
				'rules'	=> 'trim|required'
			),
			array(
				'field' => 'refNo',
				'label' => lang('pyrocart.refNo'),
				'rules' => 'trim'
			),
			array(
				'field' => 'price',
				'label' => lang('pyrocart.price'),
				'rules' => 'trim|required'
			),
			array(
				'field' => 'weight',
				'label' => lang('pyrocart.weight'),
				'rules' => 'trim'
			),
                        array(
				'field' => 'stock',
				'label' => lang('pyrocart.stock'),
				'rules' => 'trim|required'
			),
			array(
				'field' => 'currency',
				'label' => lang('pyrocart.currency'),
				'rules' => 'trim'
			),

		 	array(
				'field' => 'expire_date',
				'label' => lang('pyrocart.expire_date'),
				'rules' => 'trim'
			),

			array(
				'field' => 'description',
				'label' => lang('pyrocart.description'),
				'rules' => 'trim|required'
			),

			array(
				'field' => 'categoryId',
				'label' => lang('pyrocart.categoryId'),
				'rules' => 'trim|required'
			),

			array(
				'field' => 'featured',
				'label' => lang('pyrocart.featured'),
				'rules' => 'trim'
			),
                        array(
				'field' => 'external_url',
				'label' => lang('pyrocart.external_url'),
				'rules' => 'trim'
			)

		);
		$this->form_validation->set_rules($this->validation_rules);

		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
	}


	// Admin: Show pyrocart
	function index()
	{

		$criterias = array(''=>'Select');
			foreach($this->pyrocart_m->getCategories() as $criteria)
			{
				$criterias[$criteria->id] = $criteria->name;
			}
		$this->data->product_categories = $criterias;

		$this->template->set_partial('filters', 'admin/partials/search_product_form');
		// Create pagination links
		$total_rows = $this->pyrocart_m->countpyrocart();
		$this->data->pagination = create_pagination('admin/pyrocart/index', $total_rows);

		// Using this data, get the relevant results
		$this->data->pyrocart = $this->pyrocart_m->getpyrocart(array('order'=>'created_on DESC', 'limit' => $this->data->pagination['limit']));
		$this->template
		->append_metadata( js('functions.js', 'pyrocart') )
		->build('admin/index', $this->data);
	}

	function make_main_image($product_id,$image_id){

		$this->db->where('product_id',$product_id);
		$data['mainImage']=0;
		$this->db->update('product_images',$data);

		$this->db->where('id',$image_id);
		$data['mainImage']=1;
		$this->db->update('product_images',$data);


		$this->session->set_flashdata('success', sprintf('Successfully deleted image'));
		redirect('admin/pyrocart/product_image_manage/'.$product_id);
	}
	function product_image_delete($product_id,$image_id){

		$photo = $this->db->get_where('pyrocart_images',array('id'=>$image_id))->first_row();

		$this->db->where('id',$image_id);
		$this->db->delete('product_images');
		@unlink(FCPATH.'/uploads/pyrocart/full/'.$photo->productImage);
		@unlink(FCPATH.'/uploads/pyrocart/thumbs/'.$photo->productImageThumb);
		$this->session->set_flashdata('success', sprintf('Successfully deleted image'));
		redirect('admin/pyrocart/product_image_manage/'.$product_id);
	}
	function product_image_manage($product_id,$image_id='')
		{
			$this->form_validation->_field_data=array();
			$this->validation_rules = array(
				array(
					'field' => 'name',
					'label' => 'Name',
					'rules'	=> 'trim|required'
				),

				array(
					'field' => 'productImage',
					'label' => 'Image',
					'rules'	=> 'trim'
				),
				array(
					'field' => 'color',
					'label' => 'Color',
					'rules'	=> 'trim'
				)
				);

			$this->form_validation->set_rules($this->validation_rules);
			if ($this->form_validation->run())
				{
					if($image_id==''){
						if ($this->images_m->uploadProductImage($product_id,$_POST))
							{
								$this->session->set_flashdata('success', sprintf('Successfully uploaded image %s', $this->input->post('name')));
								redirect('admin/pyrocart/product_image_manage/'.$product_id);
							}
					}
					if($image_id!=''){
						if ($this->images_m->updateProductImage($image_id,$_POST))
							{
								$this->session->set_flashdata('success', sprintf('Successfully uploaded image %s', $this->input->post('name')));
								redirect('admin/pyrocart/product_image_manage/'.$product_id);
							}

					}


				}


			foreach($this->validation_rules as $rule)
			{
				$image->{$rule['field']} = $this->input->post($rule['field']);
			}

			if($image_id!=''){

				$image = $this->images_m->get_product_image($image_id);
			}
			$this->data->image = & $image;

			$this->data->product_id = $product_id;
			$this->data->product_images = $this->images_m->get_admin_product_images($product_id);


			$this->template->set_layout(FALSE);
			if($image_id==''){
			$this->template
			->title('')
			->build('admin/images/manage', $this->data);
			}
			if($image_id!=''){
			$this->template
			->title('')
			->build('admin/images/edit', $this->data);

			}

		}





// Admin: edit a Productitem
	function edit($id = 0)
		{

			if ($this->form_validation->run())
			{
				if ($this->pyrocart_m->editProduct($id,$_POST))
				{
					$this->session->set_flashdata('success', sprintf('Successfulle added product', $this->input->post('title')));
					redirect('admin/pyrocart/index');
				}
				else
				{
					$this->session->set_flashdata(array('error'=> lang('product_add_error')));
				}
			}

			$criterias = array(''=>'Select');
			foreach($this->pyrocart_m->getCategories() as $criteria)
			{
				$criterias[$criteria->id] = $criteria->name;
			}
			$this->data->categories = $criterias;
			$this->data->currency = $this->config->item('currency_list');
			$this->data->product = $this->pyrocart_m->getProduct($id);

			if ($this->data->product)
			{

				// Load WYSIWYG editor
				$this->data->fields=$this->validation_rules;
				//$this->data->productitem =& $productitem;
				$this->template->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) );
				$this->template->build('admin/edit', $this->data);
			}
			else
			{
				redirect('admin/pyrocart');
			}
		}

	// Admin: Create a new Product
	function create()
	{

		if ($this->form_validation->run())
		{
			if ($this->pyrocart_m->newProduct($_POST))
			{

				$this->session->set_flashdata('success', sprintf(lang('product_add_success'), $this->input->post('title')));
				redirect('admin/pyrocart/index');
			}
			else
			{

				$this->session->set_flashdata(array('error'=> lang('product_add_error')));
			}
		}

		// Loop through each rule
		foreach($this->validation_rules as $rule)
		{
			$product->{$rule['field']} = $this->input->post($rule['field']);
		}



		$categories = array(''=>'Select');
		foreach($this->pyrocart_m->getCategories() as $criteria)
		{
			$categories[$criteria->id] = $criteria->name;
		}
		$this->data->categories = $categories;

		$this->data->currency = $this->config->item('currency_list');


		// Load WYSIWYG editor
		$this->data->fields=$this->validation_rules;
		$this->data->product =& $product;
		$this->template->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) );
		$this->template->build('admin/create', $this->data);
	}


	public function makeSponsored($id = NULL)
	{
		$id_array = array();

		// Multiple IDs or just a single one?
		if ( $_POST )
		{
			$id_array = @$_POST['action_to'];
		}
		else
		{
			if ( $id !== NULL )
			{
				$id_array[0] = $id;
			}
		}

		if ( empty($id_array) )
		{
			$this->session->set_flashdata('error', 'Please select advertisement first');
			redirect('admin/pyrocart');
		}

		// Loop through each ID
		foreach ( $id_array as $id)
		{
			if($this->input->post('makeSponsored')!=''){
				$this->pyrocart_m->makeSponsored($id);
			}

			if($this->input->post('removeSponsored')!=''){
				$this->pyrocart_m->remvoeSponsored($id);
			}


		}

		$this->session->set_flashdata('success', lang('Successfully made the advertisement(s) sponsored'));
		redirect('admin/pyrocart');
	}







	function delete($productid='')
	{
		if($productid==''){redirect('admin/pyrocart');}

		$this->db->where('id', $productid);
		$this->db->delete('pyrocart');

		redirect('admin/pyrocart');
	}

	function addProductCategory($id=FALSE)
	{
		$this->load->library('form_validation');
		$this->form_validation->_field_data=array();
		$fields = array(
			array(
				'field' => 'name',
				'label' => 'Category Name',
				'rules'	=> 'trim|required'
			),

			array(
				'field' => 'parentid',
				'label' => lang('pyrocart.parentid'),
				'rules'	=> 'trim'
			));

		$this->form_validation->set_rules($fields);


		if ($this->form_validation->run())
		{
			if ($this->pyrocart_m->newProductCategory($_POST))
			{

				$this->session->set_flashdata('success', sprintf(lang('category_add_success'), $this->input->post('name')));
				redirect('admin/pyrocart/listCategories');
			}
			else
			{

				$this->session->set_flashdata(array('error'=> lang('letter_add_error')));
			}
		}

		if($id){$this->data->parentid=$id;}else{$this->data->parentid='';}
		$this->data->categories = $this->pyrocart_m->makeCategoriesDropDown();


		$this->template->build('admin/addProductCategory', $this->data);
	}

	function listCategories()
		{

			$total_rows = $this->pyrocart_m->countCategories();
			$this->data->pagination = create_pagination('admin/pyrocart/listCategories', $total_rows);

			// Using this data, get the relevant results
			$this->data->categories = $this->pyrocart_m->getProductCategories(array('limit' => $this->data->pagination['limit']));
			$this->data->product_categories = $this->pyrocart_m->getParentCategories();
			$this->template->build('admin/listCategories', $this->data);

		}

	function editProductCategory($criteriaId=0)
	{

		$this->data->criteriaId = $criteriaId;
		$this->load->library('form_validation');
		$this->form_validation->_field_data=array();

		$fields = array(
			array(
				'field' => 'name',
				'label' => lang('pyrocart.title'),
				'rules'	=> 'trim|required'
			),

			array(
				'field' => 'parentid',
				'label' => lang('pyrocart.parentid'),
				'rules'	=> 'trim'
			));

		$this->form_validation->set_rules($fields);
		if ($this->form_validation->run())
		{
			if ($this->pyrocart_m->editProductCategory($criteriaId,$_POST))
			{

				$this->session->set_flashdata('success', sprintf(lang('criteria_edit_success'), $this->input->post('name')));
				redirect('admin/pyrocart/listCategories');
			}
			else
			{

				$this->session->set_flashdata(array('error'=> lang('criteria_edit_error')));
			}
		}

		$this->data->categories = $this->pyrocart_m->makeCategoriesDropDown();

		$this->data->criteria = $this->pyrocart_m->getProductCategory($criteriaId);

		$this->template->build('admin/editProductCategory', $this->data);
	}

















}
?>
