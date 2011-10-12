<?php if (!empty($orders)): ?>
	<h3>Manage orders</h3>
<?php echo form_open('admin/orders/processOrders');?>

	<table border="0" class="table-list">
	  <thead>
			<tr>
				<th>Customer Name</th>
				<th>Total Items</th>
				<th>Amount</th>
				<th>Status</th>
				<th>Actions</th>
			</tr>
	  </thead>
		<tbody>
			<?php foreach ($orders as $order): ?>
			<tr>
				<td><?php echo $order->firstName.' '.$order->lastName; ?></td>
				<td><?php $order_items = $this->orders_m->countOrderItems($order->id); ?>

				<?php echo anchor('products/orders/admin/product_order_items_details/' . $order->id, $order_items,'class="iframe_form"'); ?>
				</td>
				<td><?php echo $order->amount; ?></td>
				<td><?php echo $this->orders_m->order_status_dropdown($order->status); ?></td>
				<td>
					<?php echo anchor('products/orders/admin/manage/' . $order->id, 'Manage'); ?>


				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else: ?>
	<div class="blank-slate">
		<img src="<?php echo base_url().'addons/modules/products/img/news.png' ?>" />

		<h2><?php echo lang('productitem.no_products_error');?></h2>
	</div>
<?php endif;?>

<p><?php $this->load->view('admin/partials/pagination'); ?></p>

<?php echo form_close(); ?>