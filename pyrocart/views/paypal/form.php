<?php

/************************************************************
This is the main web page for the DoDirectPayment sample.
This page allows the user to enter name, address, amount,
and credit card information. It also accept input variable
paymentType which becomes the value of the PAYMENTACTION
parameter.

When the user clicks the Submit button, DoDirectPaymentReceipt.php
is called.

Called by index.html.

Calls DoDirectPaymentReceipt.php.

************************************************************/
// clearing the session before starting new API Call
session_unset();

?>



<h1>Checkout</h1>

<?php
$total_qty = 0;
foreach($this->cart->contents() as $item)
{
    $total_qty += $item['qty'];
}

?>

<form id="payment" method="POST" action="{pyro:url:base}products/paypal/form/" name="DoDirectPaymentForm">
    <input type="hidden" name="shipping_type" value="<?php echo $this->settings->products_weight; ?>"/>
    <input type="hidden" name="total_qty" value="<?php echo $total_qty; ?>"/>
    
    <div id="wizard" class="swMain">
    <!-- Tabs -->
        <ul>
            <li><a href="#step-1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                    Delivery Details
                </span>
                </a>
            </li>
            <li><a href="#step-2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                   Delivery Method
                </span>
                </a>
            </li>
            <li><a href="#step-3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                   Payment Method
                </span>
                </a>
            </li>
            <li><a href="#step-4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                   Confirm Order
                </span>
                </a>
            </li>
        </ul>
    <!-- End Tabs -->
    
    
        <div id="step-1">	
            <h2 class="StepTitle">Step 1: Delivery Details</h2>
            
            <div style="display: block;" class="checkout-content">
                <div class="left">
                    <h5>Your Personal Details</h5>
                    <span class="required">*</span> First Name:<br>
                    <input name="firstname" id="firstname" value="" class="large-field" type="text">
                    <span id="msg_firstname"></span>
                    <br><br>

                    <span class="required">*</span> Last Name:<br>
                    <input name="lastname" id="lastname" value="" class="large-field" type="text">
                    <span id="msg_lastname"></span>
                    <br><br>

                    <span class="required">*</span> E-Mail:<br>
                    <input name="email" id="email" value="" class="large-field" type="text">
                    <span id="msg_email"></span>
                    <br><br>

                    <span class="required">*</span> Telephone:<br>
                    <input name="telephone" id="telephone" value="" class="large-field" type="text">
                    <span id="msg_telephone"></span>
                    <br><br>

                    Fax:<br>
                    <input name="fax" value="" class="large-field" type="text">
                    <br><br>
                </div>
            
                <div class="right">
                    <h5>Your Address</h5>
                    Company:<br>
                    <input name="company" value="" class="large-field" type="text">
                    <br><br>
                    
                    <span class="required">*</span> Address 1:<br>
                    <input name="address_1" id="address_1" value="" class="large-field" type="text">
                    <span id="msg_address_1"></span>
                    <br><br>
                    
                    Address 2:<br>
                    <input name="address_2" value="" class="large-field" type="text">
                    <br><br>
                    
                    <span class="required">*</span> City:<br>
                    <input name="city" id="city" value="" class="large-field" type="text">
                    <span id="msg_city"></span><br><br>
  
                    <span class="required">*</span> Post Code:<br>
                    <input name="postcode" id="postcode" value="" class="large-field" type="text">
                    <span id="msg_postcode"></span><br><br>

                    <span class="required">*</span> Country:<br>
                    <select name="country_id" id="country_id" class="large-field" >
                        <option value=""> --- Please Select --- </option>
                        <?php 
                        foreach($countries as $country):
                            echo '<option value="'.$country['country_id'].'">'. $country['country'] .'</option>';
                        endforeach; 
                        ?>
                    </select>
                    <span id="msg_country_id"></span><br><br>

                    <span class="required">*</span> Region / State:<br>
                    
                    <select name="zone_id" id="zone_id" class="large-field">
                        <option value=""> --- Please Select --- </option>
                        <?php 
                        foreach($zones as $zone):
                            echo '<option value="'.$zone['zone_id'].'" class="'.$zone['country_id'].'">'. $zone['state'] .'</option>';
                        endforeach; 
                        ?>                       
                    </select>
                    <span id="msg_zone_id"></span><br><br>
                    <br>
                </div>
            </div>
            
        </div>
    
  	<div id="step-2">
            <h2 class="StepTitle">Step 2: Delivery Method</h2>	
            
            <div style="display: block;" class="checkout-content">
                <p>Please select the preferred shipping method to use on this order.</p>
                
                <table class="form">
                    <tbody>
                        
                    </tbody>
                </table>
                
                <b>Add Comments About Your Order</b>
                <textarea name="comment" rows="8" style="width: 98%;"></textarea>
                <br><br>
            </div>
        </div>                      
  	
        <div id="step-3">
            <h2 class="StepTitle">Step 3: Payment Method</h2>	
            
            <div style="display: block;" class="checkout-content"><p>Please select the preferred payment method to use on this order.</p>
                <table class="form2">
                    <tbody>
                        <tr>
                            <td style="width: 1px;">            
                                <input name="payment_method" value="paypal" id="paypal" checked="checked" type="radio">
                            </td>
                            <td>
                                <label for="cod">Paypal</label>
                            </td>
                        </tr>
                       
                    </tbody>
                </table>

                
                I have read and agree to the <a class="fancybox" href="#" alt="Terms &amp; Conditions"><b>Terms &amp; Conditions</b></a>        
                    <input name="agree" id="agree" value="1" type="checkbox">
                    <span id="msg_agree"></span>
                
                
            </div>
            
        </div>
  	
        <div id="step-4">
            <h2 class="StepTitle">Step 4: Confirm Order</h2>
            <input type="hidden" name="amount" value="<?php $total_amount; ?>" />
            <?php
            $gst =  $this->cart_m->get_gst();
            $shipping = "5.05";
            $sub_total = $this->cart->total();
            $total_amount = $sub_total + $shipping + $gst;
            $total_amount =  $this->cart->format_number($total_amount);
            ?>
            
            
            <table id="checkout" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <td class="model">Product Code</td>
                        <td class="name">Product Name</td>
                        <td class="quantity">Quantity</td>
                        <td class="price">Price</td>
                        <td class="total">Total</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->cart->contents() as $item) : ?>
                    <tr>
                        <td class="model"><?php echo $item['product_code']?></td>
                        <td class="name"><a href="<?php echo site_url(); ?>products/details/<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></a></td>
                        <td class="quantity"><?php echo $item['qty']?></td>
                        <td class="price"><?php echo '$'.$this->cart->format_number($item['price']); ?></td>
                        <td class="total">$<?php echo $this->cart->format_number($item['subtotal']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="price"><b>Sub-Total:</b></td>
                        <td class="total">$<?php echo $sub_total; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="price"><b>Shipping & Handling:</b></td>
                        <td class="total">$<?php echo $shipping; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="price"><b>GST:</b></td>
                        <td class="total">$<?php echo $this->cart->format_number($gst); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="price"><b>Total:</b></td>
                        <td class="total">$<?php echo $this->cart->format_number($total_amount); ?></td>
                    </tr>
                </tfoot>
            </table>
         </div>
     </div>
<!-- End SmartWizard Content -->

    <input type="hidden" name="status" value="orderPlaced"/>
</form>

