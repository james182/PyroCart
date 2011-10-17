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
				'field' => 'product_code',
				'label' => lang('pyrocart.product_code'),
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
				'field' => 'category_id',
				'label' => lang('pyrocart.category_id'),
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


	// Admin: Show Products
	function index()
	{
            $criterias = array(''=>'Select Category');
            
            foreach($this->pyrocart_m->get_categories() as $criteria)
            {
                $criterias[$criteria->id] = $criteria->name;
            }
            
            $this->data->product_categories = $criterias;

            $this->template->set_partial('filters', 'admin/partials/search_product_form');
            
            // Create pagination links
            $total_rows = $this->pyrocart_m->count_products();
            $this->data->pagination = create_pagination('admin/pyrocart/index', $total_rows);

            // Using this data, get the relevant results
            $this->data->products = $this->pyrocart_m->get_products(array('order'=>'created_on DESC', 'limit' => $this->data->pagination['limit']));

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





// Admin: Edit a product
	function edit($id = 0)
        {
            if ($this->form_validation->run())
            {
                if ($this->pyrocart_m->edit_product($id,$_POST))
                {
                    $this->session->set_flashdata('success', sprintf('Successfully added product', $this->input->post('title')));
                    redirect('admin/pyrocart/index');
                }
                else
                {
                    $this->session->set_flashdata(array('error'=> lang('product_add_error')));
                }
            }

            $criterias = array(''=>'Select');
            foreach($this->pyrocart_m->get_categories() as $criteria)
            {
                $criterias[$criteria->id] = $criteria->name;
            }
            $this->data->categories = $criterias;
            $this->data->currency   = $this->config->item('currency_list');
            $this->data->product    = $this->pyrocart_m->get_product($id);

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
                if ($this->pyrocart_m->new_product($_POST))
                {
                    $this->session->set_flashdata('success', sprintf($this->lang->line('product_create_success'), $this->input->post('title')));
                    redirect('admin/pyrocart');
                }
                else
                {
                    $this->session->set_flashdata(array('error'=> lang('pyrocart_add_error')));
                }
            }

            // Loop through each rule
            foreach($this->validation_rules as $rule)
            {
                    $product->{$rule['field']} = $this->input->post($rule['field']);
            }

            $categories = array(''=>'Select Category');
            foreach($this->pyrocart_m->get_categories() as $criteria)
            {
                    $categories[$criteria->id] = $criteria->name;
            }
            
            $this->data->categories = $categories;
            $this->data->currency   = $this->settings->pyrocart_currency;
            $this->data->fields     = $this->validation_rules;
            $this->data->product    =& $product;
            
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







	function delete($product_id='')
	{
		if($productid==''){redirect('admin/pyrocart');}

		$this->db->where('id', $product_id);
		$this->db->delete('pyrocart');

		redirect('admin/pyrocart');
	}

	function add_product_category($id = FALSE)
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
				'field' => 'parent_id',
				'label' => lang('pyrocart.parent_id'),
				'rules'	=> 'trim'
			));

		$this->form_validation->set_rules($fields);


		if ($this->form_validation->run())
		{
                    if ($this->pyrocart_m->new_product_category($_POST))
                    {
                        $this->session->set_flashdata('success', sprintf(lang('category_add_success'), $this->input->post('name')));
                            redirect('admin/pyrocart/list_categories');
                    }
                    else
                    {
                        $this->session->set_flashdata(array('error'=> lang('pyrocart_add_error')));
                    }
		}

		if($id){$this->data->parent_id = $id;}else{$this->data->parent_id = '';}
		$this->data->categories = $this->pyrocart_m->make_categories_dropDown();

		$this->template->build('admin/add_product_category', $this->data);
	}

	function list_categories()
		{

			$total_rows = $this->pyrocart_m->count_categories();
			$this->data->pagination = create_pagination('admin/pyrocart/listCategories', $total_rows);

			// Using this data, get the relevant results
			$this->data->categories = $this->pyrocart_m->get_product_categories(array('limit' => $this->data->pagination['limit']));
			$this->data->product_categories = $this->pyrocart_m->get_parent_categories();
			$this->template->build('admin/list_categories', $this->data);

		}

	function edit_product_category($criteriaId=0)
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
				'field' => 'parent_id',
				'label' => lang('pyrocart.parent_id'),
				'rules'	=> 'trim'
			));

		$this->form_validation->set_rules($fields);
		if ($this->form_validation->run())
		{
			if ($this->pyrocart_m->edit_product_category($criteriaId,$_POST))
			{

				$this->session->set_flashdata('success', sprintf(lang('criteria_edit_success'), $this->input->post('name')));
				redirect('admin/pyrocart/list_categories');
			}
			else
			{

				$this->session->set_flashdata(array('error'=> lang('criteria_edit_error')));
			}
		}

		$this->data->categories = $this->pyrocart_m->make_categories_dropDown();

		$this->data->criteria = $this->pyrocart_m->get_product_category($criteriaId);

		$this->template->build('admin/edit_product_category', $this->data);
	}

















}
?>
