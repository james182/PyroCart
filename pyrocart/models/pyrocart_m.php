<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pyrocart_m extends CI_Model
{

    public function __construct()
    {
        // Call the parent's constructor
        parent::__construct();

        $this->config->load('pyrocart/pyrocart_config');

        $this->load->library('upload');
        //$this->load->library('image_lib');
        $this->load->library('cart');
    }

    function get_products($params = array(),$front_end = false)
    {
        $s_category = $this->input->get_post('s_category');
        
        if($s_category){
            if(isset($s_category))
            {
                $childs = $this->get_child_categories($s_category);
                $child_in = array($s_category);
                if($childs){
                    foreach($childs as $child)
                    {
                        array_push($child_in,$child->id);
                    }
                }

                foreach($child_in as $cc){
                    $this->db->or_where(array('pyrocart.category_id'=>$cc));
                }
            }
        }

        $s_name = $this->input->get_post('s_name');
        if($s_name)
        {
            $this->db->like('title',$s_name);    
        }

        if(isset($params['category_id']))
        {
            $childs = $this->get_child_categories($params['category_id']);
            $child_in = array($params['category_id']);
            if($childs){
                foreach($childs as $child)
                {
                    array_push($child_in,$child->id);   
                }
            }
            
            foreach($child_in as $cc){
                $this->db->or_where(array('pyrocart.category_id'=>$cc));
            }
        }

        if(isset($params['order'])) $this->db->order_by($params['order']);

        // Limit the results based on 1 number or 2 (2nd is offset)
        if(isset($params['limit']) && is_int($params['limit'])) $this->db->limit($params['limit']);
        elseif(isset($params['limit']) && is_array($params['limit'])) $this->db->limit($params['limit'][0], $params['limit'][1]);

        if(@@$_REQUEST['product_code']!='') $this->db->where(array('pyrocart.product_code'=>$_REQUEST['product_code']));


        if(@@$_REQUEST['price_min']!='' and @@$_REQUEST['price_max']!='')
                {
                        $this->db->where("pyrocart.price BETWEEN ".$_REQUEST['price_min']." AND ".$_REQUEST['price_max']);
                }



        $this->db->select('pyrocart.*');
        $this->db->from('pyrocart');
        //$this->db->join('product_images', 'products.id = product_images.product_id');
        if($front_end==true){
                //$curdate = date('Y-m-d H:i:s');
                //$this->db->where('expire_date >=',$curdate);
                //$this->db->or_where('expire_date',NULL);
                $this->db->where('featured','true');
        }

        $query = $this->db->get();



        if ($query->num_rows() == 0)
        {
                return array();
        }
        else
        {
                return $query->result();
        }
        //return $this->db->get('productitems')->result();
	}


	function get_cat_breadcrumb($catId)
        {

                $cat_id = $catId;
                $result = $this->get_category($cat_id);

                $list = array();
                $count = 0;
                while($result){
                        $count = $count + 1;
                        $list[]='&nbsp;<a href="'.BASE_URL.'pyrocart/search/'.$result->id.'">'.$result->name.'</a>&nbsp;';
                        $result = $this->get_category($result->parent_id);


                }

                return implode("&gt;", array_reverse($list));
        }

	function get_child_categories($parent_id = FALSE)
		{
			if(!$parent_id){return array();}

			$this->db->where(array('parent_id'=>$parent_id));
			$query = $this->db->get('pyrocart_categories');

		     if ($query->num_rows() == 0)
				{
					return FALSE;
				}
				else
				{
					return $query->result();
				}
		}
	function get_categories($params = array())
	{
		if(isset($params['order'])) $this->db->order_by($params['order']);



		$query = $this->db->get('pyrocart_categories');
		if ($query->num_rows() == 0)
		{
			return array();
		}
		else
		{
			return $query->result();
		}
	}
	function count_categories($params = array())
	{
		return $this->db->count_all_results('pyrocart_categories');
	}

	function get_product($id = '')
	{
		$query = $this->db->get_where('pyrocart', array('id'=>$id));
		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return $query->row();
		}
	}

	function get_category($id = '')
	{
		$query = $this->db->get_where('pyrocart_categories', array('id'=>$id));
		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return $query->row();
		}
	}

	function count_products($params = array())
	{

		$s_category = $this->input->get_post('s_category');

		if($s_category){
                    if(isset($s_category))
                    {
                             $childs = $this->getChildCategories($s_category);
                             $child_in = array($s_category);
                             if($childs){
                                    foreach($childs as $child){array_push($child_in,$child->id);}
                             }

                            foreach($child_in as $cc){
                                     $this->db->or_where(array('products.categoryId'=>$cc));
                            }

                    }

                }

		$s_name = $this->input->get_post('s_name');
		if($s_name){$this->db->like('title',$s_name);}

		$this->db->select('id');
		$query = $this->db->get('pyrocart');

		return $query->num_rows();

	}
	function edit_product($id, $input = array())
	{
		$this->load->helper('date');
                
		unset($input['btnAction']);
                
		$this->db->where('id', $id);
		$this->db->update('pyrocart', $input);
		$insert_id = $id;
		//$this->upload_image($input,$insert_id,TRUE);

		return TRUE;
	}
	
	function new_product($input = array())
	{
        $this->load->helper('date');
        
		$input = array_slice($input,1,-1);
        $input['created_on'] = now();
		
        $this->db->insert('pyrocart', $input);
				
        if($insertId = $this->db->insert_id()){
        	//$this->upload_image($input,$insertId);
        	return $insertId;
        }else{
        	return false;
        };
	}


	function new_product_category()
	{
            $set_name       = $this->input->post('name');
            $set_parent_id  = $this->input->post('parent_id');
            
            $this->db->insert('pyrocart_categories', array('name'=>$set_name, 'parent_id'=>$set_parent_id));

            $insert_id = $this->db->insert_id();
            return $insert_id;
	}
        
	function edit_product_category($id)
	{
            $set_name       = $this->input->post('name');
            $set_parent_id  = $this->input->post('parent_id');

            $this->db->where('id', $id);
            $this->db->update('pyrocart_categories', array('name'=>$set_name, 'parent_id'=>$set_parent_id));
            return true;
	}

	function make_categories_dropDown()
        {
            $query = $this->db->get('pyrocart_categories');
            if ($query->num_rows() == 0)
            {
                return array();
            }
            else
            {

                $data  = array('0'=>'Select');
                foreach($query->result() as $row)
                {

                    $data[$row->id] = $row->name;

                }

                return $data;
            }

        }

	function get_product_categories($params = array())
	{
		if(isset($params['order'])) $this->db->order_by($params['order']);



		// Limit the results based on 1 number or 2 (2nd is offset)
		if(isset($params['limit']) && is_int($params['limit'])) $this->db->limit($params['limit']);
		elseif(isset($params['limit']) && is_array($params['limit'])) $this->db->limit($params['limit'][0], $params['limit'][1]);



		$query = $this->db->get('pyrocart_categories');
		if ($query->num_rows() == 0)
		{
			return array();
		}
		else
		{
			return $query->result();
		}
		//return $this->db->get('portfolio')->result();
	}

	function get_parent_categories()
        {

            $this->db->where(array('parent_id'=>'0'));
            $query = $this->db->get('pyrocart_categories');

            if ($query->num_rows() == 0)
            {
                    return array();
            }
            else
            {
                    return $query->result();
            }
        }

	function get_product_category($id = '')
        {
            $query = $this->db->get_where('pyrocart_categories', array('id'=>$id));
            if ($query->num_rows() == 0)
            {
                    return FALSE;
            }
            else
            {
                    return $query->row();
            }
        }

	function isFeasibleForThumb( $filename ){
		    $imageInfo = getimagesize($filename);
		    $MB = 1048576;  // number of bytes in 1M
		    $K64 = 65536;    // number of bytes in 64K
		    $TWEAKFACTOR = 1.5;  // Or whatever works for you
		    $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
		                                           * $imageInfo['bits']
		                                           * $imageInfo['channels'] / 8
		                             + $K64
		                           ) * $TWEAKFACTOR
		                         );
		  //   echo $memoryNeeded.'<br>';
		    //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
		    //Default memory limit is 8MB so well stick with that.
		    //To find out what yours is, view your php.ini file.
		    $memoryLimit = 128 * $MB;
		    $memoryLimitMB = 128000;
		    if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit)
		    {
		        return false;
		    }else{
		        return true;
		    }
	}
	public function width_ratio($src,$ext)
		{
			$base_path = str_replace('system/codeigniter/','',BASEPATH);
				$path_thumb = '/uploads/products/thumbs/'.$src.'_thumb'.$ext;
				$destination	= $base_path.$path_thumb;

			if(!file_exists($destination))
				{
					$path_full = '/uploads/products/full/'.$src.$ext;
					$destination	= $base_path.$path_full;
					$vals = @getimagesize($destination);
					$v['width']			= $vals['0'];
					$v['height']		= $vals['1'];
					$percentage = intval(($vals[0]*100)/intval($this->config->item('image_thumb_width')));
					return $percentage.'%';



				}

		}
	public function upload_image_path($src,$ext)
		{
				//echo BASEPATH;exit;
				//echo base_url();exit;
				$base_path = str_replace('system/codeigniter/','',BASEPATH);
				$path_thumb = '/uploads/products/thumbs/'.$src.'_thumb'.$ext;
				$destination	= $base_path.$path_thumb;

				if(file_exists($destination)){return base_url().$path_thumb;}
				else{
					$path_full = '/uploads/products/full/'.$src.$ext;
					$destination	= $base_path.$path_full;
					if(file_exists($destination)){return base_url().$path_full;}
				}

				return '';

		}

	public function thumbNotExists($src,$ext)
		{
				//echo BASEPATH;exit;
				//echo base_url();exit;
				$base_path = str_replace('system/codeigniter/','',BASEPATH);
				$path_thumb = '/uploads/products/thumbs/'.$src.'_thumb'.$ext;
				$destination	= $base_path.$path_thumb;
				//echo $destination;exit;
				if(!file_exists($destination)){return TRUE;}else{return FALSE;}


		}
	public function upload_image($input,$insertId,$replace=FALSE)
	{
		$this->load->library('helpfunctions');
		// Get the name of the gallery we're uploading the image to
		//$gallery = $this->db->select('slug')
		//					->from('galleries')
		//					->where('id', $input['gallery_id'])
		//					->get()
		//					->row();

		//$gallery_slug 	= $gallery->slug;

		// First we need to upload the image to the server
		$upload_conf['upload_path'] 	= 'uploads/products/full';
		$upload_conf['allowed_types'] 	= $this->config->item('image_allowed_filetypes');
		//$upload_conf['max_size']=120;
		$this->upload->initialize($upload_conf);

		// Let's see if we can upload the file

		$images_list = array('imageOne','imageTwo','imageThree','imageFour','imageFive','imageSix','floorPlan1','floorPlan2','floorPlan3');

		for($i=0;$i<count($images_list);$i++)
		{
			$this->image_lib->clear();
			$imageNo = $images_list[$i];

			if ( $this->upload->do_upload($imageNo) )
			{
				$uploaded_data 	= $this->upload->data();

				// Set the data for creating a thumbnail
				$source			= 'uploads/products/full/' . $uploaded_data['file_name'];
				$destination	= 'uploads/products/thumbs';
				$options		= array();

				// Is the current size larger? If so, resize to a width/height of X pixels (determined by the config file)
				if ( $uploaded_data['image_width'] > $this->config->item('image_thumb_width'))
				{
					$options['width'] = $this->config->item('image_thumb_width');
				}
				if ( $uploaded_data['image_height'] > $this->config->item('image_thumb_height'))
				{
					$options['height'] = $this->config->item('image_thumb_height');
				}
				//$system=explode(".",$uploaded_data['file_name']);
				//$destination = $destination.'/'.$system[0].'_thumb.'.$system[1];

				//$feasible = $this->isFeasibleForThumb($source);
				//if($feasible){
				//	$this->helpfunctions->createthumb($source,$destination,$options['width'],$options['height']);

					 $this->resize('resize', $source, $destination, $options);
				//}

				$DATA[$imageNo]=$uploaded_data['raw_name'];
				$DATA[$imageNo.'Ext']=$uploaded_data['file_ext'];

				if($insertId)
						{
							$this->db->where('id', $insertId);
							$this->db->update('products', $DATA);

						}

				// Great, time to create a thumbnail
				/*if ( $this->resize('resize', $source, $destination, $options) === TRUE )
				{
					$DATA[$imageNo]=$uploaded_data['raw_name'];
					$DATA[$imageNo.'Ext']=$uploaded_data['file_ext'];

					if($insertId)
						{
							$this->db->where('id', $insertId);
							$this->db->update('products', $DATA);

						}
					// Image has been uploaded, thumbnail has been created, time to add it to the DB!
					$to_insert['gallery_id'] = $input['gallery_id'];
					$to_insert['filename']	 = $uploaded_data['raw_name'];
					$to_insert['extension']	 = $uploaded_data['file_ext'];
					$to_insert['title']		 = $input['title'];
					$to_insert['description']= $input['description'];
					$to_insert['uploaded_on']= time();
					$to_insert['updated_on'] = time();

					// Insert it
					if ( is_int(parent::insert($to_insert)) )
					{
						return TRUE;
					}
					else
					{
						return FALSE;
					}
				}*/
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


	function sendToAdmin($id,$input = array())
	{
		$this->load->helper('date');
		$this->load->library('email');

		if($id!=''){
		// Get the nesletter details
		//$product = $this->getProductitem($id);

		}

		$adminMail = $this->settings->item('contact_email');#'tareq.mist@gmail.com';

		//echo $adminMail;exit;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->clear();

		$this->email->from(@$input['email'],@$input['name']);
		$this->email->to($adminMail);
		$this->email->subject(''.@$input['name'] .' | '.$this->settings->item('site_name'));

		$body = '';

		if(@$input['companyName']!=''){
			$body .= '<p>Company Name: '.@$input['companyName'].'</p>';
		}
		if(@$input['name']!=''){
			$body .= '<p>Name: '.@$input['name'].'</p>';;
		}

		if(@$input['companyName']!=''){
			$body .= '<p>Phone: '.@$input['phone'].'</p>';
		}
		if(@$input['billingAddress']!=''){
			$body .= '<p>Bill to address: '.@$input['billingAddress'].'</p>';;
		}

		if(@$input['shipingAddress']!=''){
			$body .= '<p>Ship to : Address: '.@$input['shipingAddress'].'</p>';
		}
		if(@$input['productName']!=''){
			$body .= '<p>Product Name: '.@$input['productName'].'</p>';;
		}

		if(@$input['unit']!=''){
			$body .= '<p>unit: '.@$input['unit'].'</p>';
		}
		if(@$input['quantity']!=''){
			$body .= '<p>quantity: '.@$input['quantity'].'</p>';;
		}



		$body .= '<br>'.@$input['message'];
		$this->email->message($body);
		$this->email->send();
		//echo 'dddddddddd';
		//$this->email->print_debugger();exit;



	}

	function makeSponsored($id)
		{
			$data['featured'] = 'true';
			$this->db->where('id', $id);
			$this->db->update('products', $data);

		}
	function remvoeSponsored($id)
		{
			$data['featured'] = 'false';
			$this->db->where('id', $id);
			$this->db->update('products', $data);

		}
	function getSponsoredProducts()
		{
			$this->db->where('sponsoredBy', 'sponsored');
			$res = $this->db->get('products');
			return $res->result();
		}


	function deductQuantityFromProducts()
		{
			$cart = $this->cart->contents();

			foreach($cart as $item)
				{
					$qty = $item['qty'];
					$id = $item['id'];
					$update_query = "UPDATE products SET stock = stock - $qty WHERE id = $id";
					$this->db->query($update_query);

				}



		}

    function time_left_to_expire($expire_date)
    	{
    						$curdate = date('Y-m-d H:i:s');
							$dateDiff =strtotime($expire_date) - strtotime('now');
							$fullDays = floor($dateDiff/(60*60*24));
							$fullHours = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
							$fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);

							//echo "Differernce is $fullDays days, $fullHours hours and $fullMinutes minutes.";
							$time_difference = $fullDays.':'.$fullHours.':'.$fullMinutes;
							return $time_difference;
    	}



}
?>
