<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Images_m extends CI_Model
{
	//var $email_from = 'admin@localhost'; // this is set by controller when used
	public function __construct()
	{
		// Call the parent's constructor
		parent::__construct();
		$this->config->load('products/products_config');
		$this->load->library('upload');
		$this->load->library('image_lib');
	}



	function uploadProductImage($product_id,$input = array())
	{
		$DATA['name']=$input['name'];
		$DATA['product_id']=$product_id;
		$this->db->insert('product_images', $DATA);
		$insertId = $this->db->insert_id();
		$this->upload_image($input,$insertId);

		return $product_id;
	}

	function updateProductImage($image_id,$input = array())
	{
		$DATA['name']=$input['name'];
		$this->db->where('id',$image_id);
		$this->db->update('product_images', $DATA);
		$this->upload_image($input,$image_id,TRUE);

		return $image_id;
	}
	function get_product_image($image_id)
		{
			$query = $this->db->get_where('product_images',array('id'=>$image_id));

			return $query->first_row();
		}
	function get_admin_product_images($product_id)
		{

			$query = $this->db->get_where('product_images',array('product_id'=>$product_id));
			return $query->result();

		}
	function get_product_images_details($product_id)
		{
			$this->db->select('product_images.*');
			$this->db->from('product_images');
			//$this->db->join('product_designs','product_images.design = product_designs.sku and product_images.product_id = product_designs.product_id');
			$this->db->where('product_images.product_id',$product_id);
			$query = $this->db->get();
			return $query->result();

		}
	function get_product_images($product_id,$main=0)
		{

			$query = $this->db->get_where('product_images',array('product_id'=>$product_id,'mainImage'=>$main));
			if($main==1 and $query->num_rows()==0)
				{
					$query = $this->db->get_where('product_images',array('product_id'=>$product_id,'mainImage'=>0));
				}
			return $query->result();

		}




	public function upload_image($input,$insertId,$overwrite=FALSE)
	{
		$this->load->library('helpfunctions');


		// Let's see if we can upload the file
		if($overwrite){
			$image = $this->get_product_image($insertId);

		}
		$images_list = array('productImage','productImageThumb');

		for($i=0;$i<count($images_list);$i++)
		{
			$imageNo = $images_list[$i];
			$upload_conf['upload_path'] 	= 'uploads/products/full';


			if($imageNo=='productImageThumb'){
				$upload_conf['upload_path'] 	= 'uploads/products/thumbs';
			}



			$upload_conf['allowed_types'] 	= $this->config->item('image_allowed_filetypes');
			$this->upload->initialize($upload_conf);


			$this->image_lib->clear();


			if ( $this->upload->do_upload($imageNo) )
			{
				if($overwrite&&$imageNo=='productImage'){
					@unlink(FCPATH.'/uploads/products/full/'.$image->productImage);
				}
				if($overwrite&&$imageNo=='productImageThumb'){
					@unlink(FCPATH.'/uploads/products/thumbs/'.$image->productImageThumb);
				}

				$uploaded_data 	= $this->upload->data();



				$DATA[$imageNo]=$uploaded_data['raw_name'].$uploaded_data['file_ext'];
				if($insertId)
						{
							$this->db->where('id', $insertId);
							$this->db->update('product_images', $DATA);
						}

			}
		else{
		//echo $imageNo;echo $this->upload->display_errors();
		}
			}

	}
  public function create_thumbnail_of_image($image)
  	{


  		$upload_conf['upload_path'] 	= 'uploads/products/thumbs';
		$upload_conf['allowed_types'] 	= $this->config->item('image_allowed_filetypes');
		$upload_conf['file_name'] = $image;
		$upload_conf['overwrite']=TRUE;
		//$upload_conf['max_size']=120;
		$this->upload->initialize($upload_conf);

		if($this->upload->do_upload('thumb_file'))
			{

				return FALSE;
			}
		else{
			return $this->upload->display_errors();

		}



  	/*	$source			= 'uploads/products/full/' . $image;
		$destination	= 'uploads/products/thumbs';
		//echo $source;exit;
		$options		= array();
		$options['width'] = $this->config->item('image_thumb_width');
		$options['height'] = $this->config->item('image_thumb_height');

		if( $this->resize('resize', $source, $destination, $options)===TRUE)
			{
				return FALSE;
			}
		else
			{
				echo $this->image_lib->display_errors();exit;
			}*/


  	}
  public function resize($mode, $source, $destination, $options = array())
	{
		// Time to resize the image
		$image_conf['image_library'] 	= 'gd2';
		$image_conf['source_image']  	= $source;
		$image_conf['maintain_ratio'] = FALSE;
		// Save a new image somewhere else?
		if ( !empty($destination) )
		{
			$image_conf['new_image']	= $destination;
		}

		$image_conf['thumb_marker']		= '_thumb';
		$image_conf['create_thumb']  	= TRUE;
		$image_conf['quality']			= '80';

		// Optional parameters set?
		if ( !empty($options) )
		{
			// Loop through each option and add it to the $image_conf array
			foreach ( $options as $key => $option )
			{
				$image_conf[$key] = $option;
			}
		}

		$this->image_lib->initialize($image_conf);

		if ( $mode == 'resize' )
		{
			//if(!$this->image_lib->resize())
			//	{
			//		$this->image_lib->display_errors();exit;
			//	}
			return $this->image_lib->resize();
		}
		else if ( $mode == 'crop' )
		{
			return $this->image_lib->crop();
		}

		return FALSE;
	}




}
?>
