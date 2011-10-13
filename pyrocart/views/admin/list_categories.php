<?php if (!empty($categories)):

 ?>
	<h3><?php echo lang('advertisements.criteria_list_title'); ?></h3>

	<ul>
	<?php foreach ($product_categories as $cat): ?>
		<li><?php echo anchor('/admin/pyrocart/edit_product_category/' . $cat->id,$cat->name); ?>&nbsp;|<a href="<?php echo base_url()?>admin/pyrocart/add_product_category/<?=$cat->id?>">Add child</a>


			<?php $childs = $this->pyrocart_m->get_child_categories($cat->id);?>

			<?php if($childs):?>

				<ul>
				<?php foreach ($childs as $cat2): ?>
					<li><?php echo anchor('/admin/>pyrocart/editProductCategory/' . $cat2->id,$cat2->name); ?>&nbsp;|<a href="<?php echo base_url()?>admin/pyrocart/addProductCategory/<?=$cat2->id?>">Add child</a>


					<?php $childs2 = $this->pyrocart_m->get_child_categories($cat2->id);?>

					<?php if($childs2):?>

						<ul>
						<?php foreach ($childs2 as $cat3): ?>
							<li><?php echo anchor('admin/pyrocart/edit_product_category/' . $cat3->id,$cat3->name); ?></li>
						<?php endforeach; ?>
						</ul>
					<?php endif;?>


					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif;?>


		</li>

	<?php endforeach; ?>
	</ul>
	<!--<table border="0" class="table-list">
	  <thead>
			<tr>

				<th><?php echo lang('advertisements.criteriaId');?></th>
				<th><?php echo lang('advertisements.criteriaName');?></th>
				<th><span><?php echo lang('advertisements.actions');?></span></th>
			</tr>
	  </thead>
		<tbody>
			<?php foreach ($categories as $criteria): ?>
			<tr>
				<td><?php echo $criteria->id; ?></td>
				<td><?php echo $criteria->name; ?></td>

				<td>
					<?php echo anchor('admin/advertisements/editAdvertisementCategory/' . $criteria->id, lang('advertisements.criteriaedit')); ?>


				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table> -->
<?php else: ?>
	<div class="blank-slate">
		<img src="<?php echo base_url().'addons/modules/advertisements/img/news.png' ?>" />

		<h2><?php echo lang('advertisements.no_advertisements_error');?></h2>
	</div>
<?php endif;?>

<p><?php $this->load->view('admin/partials/pagination'); ?></p>
