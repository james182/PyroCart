<h3><?php echo lang('products.add_criteria'); $this->load->model('products_m'); ?></h3>

<?php echo form_open_multipart('admin/products/editProductCategory/'.$criteriaId, 'class="crud"'); ?>

	<ol>

		<li class="even">
			<label for="title"><?php echo lang('label_criteriaName');?></label>
			<input type="text" id="name" name="name" maxlength="100" value="<?php echo $criteria->name?>" class="text" />
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
		</li>

		<li class="even">
				<label for="parentid">Parent category</label>
				<?php echo form_dropdown('parentid', $categories, $criteria->parentid); ?>
	    </li>
	</ol>
	<div class="float-right">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
