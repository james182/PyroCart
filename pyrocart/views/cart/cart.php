

<div id="pagesidebar" class="rightStyle">
    <h2>Your Shopping Cart</h2>
    <br>

    <?php if(!$this->cart->contents()):
            echo 'You don\'t have any items yet.';
    else:
    ?>
    
    
    <?php echo form_open('pyrocart/cart/update_cart'); ?>
    <table class="cart" width="100%" cellpadding="0" cellspacing="0">
        <tr class="borderbottom">
            <td>Qty</td>
            <td>Item Description</td>
            <td>Item Price</td>
            <td>Sub-Total</td>
            <td></td>
        </tr>

        <tbody>
        <?php 
        $i = 1; 
        foreach($this->cart->contents() as $items):
            echo form_hidden('rowid[]', $items['rowid']); ?>

            <tr <?php if($i&1){ echo 'class="odd"'; }?>>
                <td><?php echo form_input(array('name' => 'qty[]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?></td>
                <td><?php echo $items['name']; ?></td>
                <td>$<?php echo $this->cart->format_number($items['price']); ?></td>
                <td>$<?php echo $this->cart->format_number($items['subtotal']); ?></td>
                <td><a href="" class="cart_ids" rel="<?php echo $items['rowid'];?>">X</a></td>
            </tr>

            <?php $i++; ?>
        <?php endforeach; ?>
            
        <?php $total_amount =  $this->cart->format_number($this->cart->total()); ?>

        <tr class="bordertop">
            <td colspan="5">
                <table class="total" align="right">
                    <tr>
                        <td>Sub-Total:</td>
                        <td>$<?php echo $total_amount; ?></td>
                    </tr>
                    <tr>
                        <td>GST 10%</td>
                        <td>$<?php $gst =  $this->cart_m->get_gst(); echo $this->cart->format_number($gst);
                        $total_amount = $this->cart->total() +  $gst;?> </td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td>$<?php echo $this->cart->format_number($total_amount); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div id="actions">
                    <div class="btn_empty leftStyle">
                        <?php echo anchor('pyrocart/cart/empty_cart', 'Empty Cart', 'class="empty"');?>
                    </div>
                    
                    <div class="checkout rightStyle">
                        <div class="btn_shop"><a href="{pyro:url:base}pyrocart/">Continue Shopping</a></div>
                        <div class="btn_checkout">
                            <a href="{pyro:url:base}pyrocart/paypal/form/">
                            <input type="button" name="checkout" style="width:112px; height:30px; border:0px; background:url('{pyro:url:base}addons/default/modules/pyrocart/img/checkout.jpg')" value="" >
                            </a>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    
    <!--
    <p><?php echo form_submit('', 'Update your Cart'); echo anchor('pyrocart/cart/empty_cart', 'Empty Cart', 'class="empty"');
    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    echo anchor('pyrocart/paypal/form/', 'CHECKOUT', 'class="checkout"');
    ?></p>

    <p><small>If the quantity is set to zero, the item will be removed from the cart.</small></p>
    -->
    <?php
        echo form_close();
    endif;
    ?>
</div><!--End pagesidebar-->