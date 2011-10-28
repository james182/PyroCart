<h3><?php echo lang('products.add_title'); ?></h3>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$("#datepicker").datepicker();
		$("#price").change(function(){
			this.value = this.value.replace(/[^0-9\.,]+/g,'');
		});
	});
})(jQuery);
</script>
<?php echo form_open_multipart('admin/pyrocart/create', 'class="crud"'); ?>
	<div class="float-right">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
	
	<ol>
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="title">Product name</label>
			<input type="text" id="title" name="title" maxlength="100" value="<?php echo $product->title; ?>" class="text" />
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
		</li>

	    <li class="<?php echo alternator('', 'even'); ?>">
				<label for="criteria_id">Category</label>
				<?php echo form_dropdown('category_id', $categories, array($product->category_id),'id="category_id"'); ?>
	    </li>

	    <li class="<?php echo alternator('', 'even'); ?>">
				<label for="product_code">Product Code</label>
				<input type="text" id="product_code" name="product_code" maxlength="100" value="<?php echo $product->product_code; ?>" class="text" />
	    </li>
            
            <?php if($this->settings->products_featured == 1)
            {?>
            <li class="<?php echo alternator('', 'even'); ?>">
				<label for="refNo">Expire Date </label>
				<input type="text" id="datepicker" name="expire_date" maxlength="100" value="<?php echo $product->expire_date; ?>" class="text" />
	    </li>
            <?php } ?>
            
	    <li class="<?php echo alternator('', 'even'); ?>">
				<label for="price">Price $ <?php echo $this->settings->products_currency; ?></label>
				<input type="text" id="price" name="price" maxlength="100" value="<?php echo $product->price; ?>" class="text" />
	    </li>
            
            <?php if($this->settings->products_weight == 1)
            {?>
            <li class="<?php echo alternator('', 'even'); ?>">
				<label for="weight">Weight</label>
				<input type="text" id="weight" name="weight" maxlength="100" value="<?php echo $product->weight; ?>" class="text" />
	    </li>
            <?php } ?>
            
	    <li class="<?php echo alternator('', 'even'); ?>">
				<label for="price">Stock</label>
				<input type="text" id="stock" name="stock" maxlength="100" value="<?php echo $product->stock; ?>" class="text" />
	    </li>
            
            <li class="<?php echo alternator('', 'even'); ?>">
				<label for="external_url">External URL</label>
				<input type="text" id="external_url" name="external_url" value="<?php echo $product->external_url; ?>" class="text" />
	    </li>
            
            <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="title">Description</label><br /><br />
                    <?php echo form_textarea(array('id'=>'description', 'name'=>'description', 'value' => html_entity_decode($product->description), 'rows' => 50, 'class'=>'wysiwyg-advanced')); ?>
            </li>
	</ol>

	<div class="buttons align-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
