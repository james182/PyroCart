<h3>Manage orders</h3>

<?php echo form_open_multipart('products/orders/admin/manage/'.$order->id, 'class="crud"'); ?>
<ol>

		 <li class="even">
				<label for="criteriaId">Order status</label>
				<?php echo form_dropdown('status', $order_status, $order->status); ?>
	    </li>
		<li class="even">
			<label for="title">Customer first name</label>
			<input type="text" id="firstName" name="firstName" maxlength="100" value="<?php echo $order->firstName; ?>" class="text" />
		</li>

		<li class="even">
			<label for="title">Last name</label>
			<input type="text" id="lastName" name="lastName" maxlength="100" value="<?php echo $order->lastName; ?>" class="text" />
		</li>

	    <li class="odd">
				<label for="refNo">ADDRESS 1</label>
				<input type="text" id="address1" name="address1" maxlength="100" value="<?php echo $order->address1; ?>" class="text" />
	    </li>

	    <li class="even">
				<label for="price">ADDRESS 2</label>
				<input type="text" id="address2" name="address2" maxlength="100" value="<?php echo $order->address2; ?>" class="text" />
	    </li>

	    <li class="even">
				<label for="price">City</label>
				<input type="text" id="city" name="city" maxlength="100" value="<?php echo $order->city; ?>" class="text" />
	    </li>

	     <li class="even">
				<label for="price">ZIP</label>
				<input type="text" id="zip" name="zip" maxlength="100" value="<?php echo $order->zip; ?>" class="text" />
	    </li>

	     <li class="even">
				<label for="price">amount</label>
				<input type="text" id="amount" name="amount" maxlength="100" value="<?php echo $order->amount; ?>" class="text" />
	    </li>

	     <li class="even">
				<label for="price">amount</label>
				<input type="text" id="amount" name="amount" maxlength="100" value="<?php echo $order->amount; ?>" class="text" />
	    </li>


	    <li class="odd">
				<label for="currency">payment_method</label>
				<?php echo form_dropdown('payment_method', array('creditcard'=>'Credit Card','paypal'=>'Pay Pal'), $order->payment_method); ?>
	    </li>


		<li class="even">
			<label for="title">Note</label>
		</li>
		<li  class="even">
			<?php echo form_textarea(array('id'=>'note', 'name'=>'note', 'value' =>$order->note, 'rows' => 40, 'class'=>'wysiwyg-simple')); ?>
		</li>




	</ol>

	</ol>
	<div class="float-right">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
