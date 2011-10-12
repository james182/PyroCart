<html>
<head>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/text.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/buttons.css">

<style>
body{background:#fff;}
form.crud li {
    padding: 2px;
}

</style>
</head>
<body>
<h2>Upload image</h2>
<?php if (validation_errors()): ?>
                        <div class="closable notification error">
                        <?php echo validation_errors(); ?>
                        </div>
<?php endif; ?>
<?php echo form_open_multipart(uri_string(), array('class' => 'crud', 'id' => 'files_crud')); ?>
<fieldset>
	<ol>
		<li class="even">
			<label for="nothing">Name of image</label>
			<?php echo form_input('name', $image->name); ?>
		</li>
		<li>
			<div style="float:left;">
			<label for="nothing">Thumbnail</label>
			<br/>
			<img id="thumbnail" src="<?php echo base_url();?>uploads/products/thumbs/<?php echo $image->productImageThumb?>" alt="" />
			<br/>
			<?php echo form_upload('productImageThumb',$image->productImageThumb,'size=15'); ?>
			</div>
			<div style="float:left;">
			<label for="nothing">Large image</label>
			<br/>
			<img id="fullimage"  src="<?php echo base_url();?>uploads/products/full/<?php echo $image->productImage?>" alt="" />
			<br/>
			<?php echo form_upload('productImage',$image->productImage,'size=15'); ?>
			</div>
		</li>
		<li>

		</li>

		<div class="align-left buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save') )); ?>
				<?php echo anchor('admin/products/product_image_manage/'.$image->product_id,'Cancel');?>

			</div>

	</ol>


</fieldset>
<?php echo form_close(); ?>



</div>
</body>
</html>