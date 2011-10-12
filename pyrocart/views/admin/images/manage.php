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
			<label for="nothing">Name</label>
			<?php echo form_input('name', $image->name); ?>
		</li>
		<li>
			<label for="nothing">Thumbnail</label>
			<?php echo form_upload('productImageThumb'); ?>
		</li>
		<li>
			<label for="nothing">Full Image</label>
			<?php echo form_upload('productImage'); ?>
		</li>
		<li class="even">
			<span class="align-right buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('upload') )); ?>

			</span>
		</li>

	</ol>


</fieldset>
<?php echo form_close(); ?>

	<div style="height:180px;overflow:auto;">
		<table class="table-list">
		<thead><tr><th>Name</th><th>Actions</th></tr></thead>
		<?php foreach($product_images as $image):?>

		<tr>
		<td><?php echo $image->	name;?></td>
		<td><?php echo anchor('admin/products/product_image_manage/' .$product_id.'/'. $image->id, 'Edit'); ?>&nbsp;|&nbsp;
		<?php echo anchor('admin/products/product_image_delete/' .$product_id.'/'. $image->id, 'Delete'); ?>
		</td>
		</tr>
		<?php endforeach;?>
		</table>
	</div>

</div>
</body>
</html>