<?php if (!empty($products)): ?>
	<h3><?php echo lang('products.list_title'); ?></h3>
<?php echo form_open('admin/products/makeSponsored');?>
	<table border="0" class="table-list">
	  <thead>
			<tr>
				<!-- <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?>Featured</th> -->
				<th><?php echo lang('products.title');?></th>
				<th><?php echo lang('products.images');?></th>
				<th><?php echo lang('products.price');?></th>
				<th>Stock</th>
				<th>Expire date</th>
				<th><span><?php echo lang('products.actions');?></span></th>
			</tr>
	  </thead>
		<tbody>
			<?php foreach ($products as $productitem): if($productitem->featured=='true'){$checked=true;}else{$checked=false;} ?>
			<tr>
			    <!-- <td><?php echo form_checkbox('action_to[]', $productitem->id,$checked); ?></td> -->
				<td><?php echo $productitem->title; ?></td>

				<td><?php echo anchor('admin/products/product_image_manage/'. $productitem->id	, 'Manage', 'class="product_image_upload"'); ?></td>
				<td><?php echo $productitem->price;?></td>
				<td><?php echo $productitem->stock;?></td>

				<td><?php  $curdate = date('Y-m-d H:i:s');
							if($productitem->expire_date<$curdate and $productitem->expire_date != ''){
								echo '<font color="red">Product has expired</font>';
							}
							else{ echo $productitem->expire_date;}?></td>
				<td>
					<?php echo anchor('admin/products/edit/' . $productitem->id, 'Edit'); ?>|

					<a href="<?php echo base_url().'products/details/' . $productitem->id; ?>" target="_blank">View</a>|

					<a href="<?php echo base_url().'admin/products/delete/' . $productitem->id; ?>" onclick="return confirm('Are you sure you want to delete this apartment?')">Delete</a>

				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<!--
<button class="button" value="make featured" name="makeSponsored" type="submit">
					<span>Make featured</span>
				</button>


<button class="button" value="Remove from featured" name="removeSponsored" type="submit">
					<span>Remove from featured</span>
				</button>
-->
<?php else: ?>
	<div class="blank-slate">
		<img src="<?php echo base_url().'addons/modules/products/img/news.png' ?>" />

		<h2><?php echo lang('productitem.no_products_error');?></h2>
	</div>
<?php endif;?>

<p><?php $this->load->view('admin/partials/pagination'); ?></p>

<?php echo form_close(); ?>