<h1>Online Store</h1>

<div id="store-nav-wrapper">
    <h2>Categories</h2>

    <ul class="store_cat">
    <?php foreach ($product_categories as $cat): ?>
            <li><?php echo anchor('pyrocart/search/' . $cat->id,$cat->name); ?>
                    <?php $childs = $this->pyrocart_m->get_child_categories($cat->id);?>

                    <?php if($childs):?>

                            <ul>
                            <?php foreach ($childs as $cat2): ?>
                                    <li class="contentContainerMain green"><?php echo anchor('pyrocart/search/' . $cat2->id,$cat2->name); ?>


                                    <?php $childs2 = $this->pyrocart_m->getChildCategories($cat2->id);?>

                                    <?php if($childs2):?>

                                            <ul>
                                            <?php foreach ($childs2 as $cat3): ?>
                                                <li><?php echo anchor('pyrocart/search/' . $cat3->id,$cat3->name); ?></li>
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
</div>
<span style="clear:both;"></span>




<h3><?php echo $cat_breadcrumb;?></h3>


<div id="pagesidebar" class="rightStyle">
    
    <div class="productrow">
    <?php foreach ($products as $product):
        $show_in_list = true;
        $time_difference = false;
        if($product->expire_date!=''){
            $curdate = date('Y-m-d H:i:s');
            if($product->expire_date<$curdate){
                $show_in_list = false;
            }
            else{
                $time_difference = $this->pyrocart_m->time_left_to_expire($product->expire_date);
            }

        }
        
        if($show_in_list){
        ?>
        <div class="probox">            
            <a href="{pyro:url:base}pyrocart/details/<?php echo $product->id?>">
            <div class="product" >
                <?php $image =  $this->images_m->get_product_images($product->id,1);?>
                <?php if(count($image)>0):?>
                    <div style="width:140px;height:140px;overflow:hidden;">
                        <img width="130px" src="{pyro:url:base}uploads/pyrocart/thumbs/<?php echo $image[0]->product_image_thumb?>" alt="" />
                    </div>
                <?php else:?>
                    <img src="{pyro:url:base}addons/modules/pyrocart/img/product.jpg" alt="" />
                <?php endif;?>
                
                <?php if($product->stock<=0):?>
                    <div class="sold_out">&nbsp;</div>
                <?php endif;?>


            </div><!--End product-->
            </a>
            
            <div class="shadow">
                <div class="shadowup">
                    <dl>
                        <dt class="p1"><?php echo $product->title?></dt>
                        <dt class="p3"><a href="{pyro:url:base}pyrocart/details/<?php echo $product->id?>">More Details</a></dt>
                        <?php if($time_difference):?>
                            <dt class="p2">Time Left:<?php echo $time_difference;?></dt>
                        <?php endif;?>
                        <dt class="p4">Price: $<?php echo $product->price?></dt>
                    </dl>
                </div>
                
                <div class="shadowdwn">
                <?php echo form_open('pyrocart/cart/add_cart_item/','id="add_to_cart_form" method="post"');?>
                    <input type="hidden" name="quantity" value="1"/>
                    <input type="hidden" name="weight" value="<?php echo $product->weight; ?>"/>
                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>"/>
                    
                    <?php if($product->external_url): ?>
                        <a href="<?php echo $product->external_url; ?>" target="_blank"><img src="{pyro:url:base}addons/modules/pyrocart/img/external.jpg" /></a>
                    <?php else: ?>
                        <?php if($product->stock > 0): ?>
                                <input  name="addtocart" type="image" src="{pyro:url:base}addons/default/modules/pyrocart/img/cart.jpg" />
                        <?php else: ?>
                                Sold Out
                        <?php endif; ?>
                                
                    <?php endif; ?>
                                
                <?php echo form_close(); ?>

                </div>
            </div>
                </div><!--End probox product-->
        <?php
        }
        endforeach;?>
        </div><!--End productrow-->


</div><!--End pagesidebar-->

