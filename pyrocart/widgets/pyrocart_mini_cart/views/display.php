<ul class="mini_cart">
<?php if ($mini_cart_contents != NULL) : ?>
    <?php foreach ($mini_cart_contents as $item) : ?>
        <li class="mini_item"><?php echo $item['qty']?>x <a href="<?php echo site_url(); ?>products/details/<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></a> <?php echo '$'.$this->cart->format_number($item['subtotal']); ?></li>
    <?php endforeach; ?>
        <li class="mini_total">Total: $<?php echo $this->cart->format_number($this->cart->total()); ?></li>
        <li class="mini_checkout"><a href="<?php echo site_url(); ?>products/cart/show_cart">Checkout</a></li>
<?php else: ?>
	<li class="mini_no_item">Your cart is empty.</li>
<?php endif; ?>
</ul>