<h3>Add category</h3>

<?php echo form_open_multipart('admin/products/addProductCategory', 'class="crud"'); ?>

	<ol>
		<li class="even">
			<label for="title">Category Name</label>
			<input type="text" id="name" name="name" maxlength="100" value="" class="text" />
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
		</li>
		<li class="even">
				<label for="parentid">Parent category</label>
				<?php echo form_dropdown('parentid', $categories, $parentid); ?>
	    </li>
	</ol>
	<div class="float-right">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
