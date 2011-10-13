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


                                    <?php $childs2 = $this->pyrocart_m->get_child_categories($cat2->id);?>

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


<style>
#myGallery{max-width:204px;max-height:204px;}
.color_box_icon{
	-moz-border-radius: 3px;
border-radius: 3px;
width:21px;height:21px;
}
</style>

<?php
    $image =  $this->images_m->get_product_images($product->id,1);
    $images = $this->images_m->get_product_images_details($product->id);
?>

<style>
div#loader {
            border: 1px solid #ccc;
            width: 204px;
            height: 204px;
            overflow: hidden;
        }
div#loader img{width:204px;height:204px;}
div#loader.loading {
    background: url('<?php echo base_url()?>addons/modules/pyrocart/img/loading.gif') no-repeat center center;
}
</style>

<script type="text/javascript">

$(document).ready(function(){
    
    
    var img = new Image();
    
    $(img).load(function (){
        $(this).hide();
        $('#loader').removeClass('loading').append(this);
        $(this).fadeIn();
    }).error(function () {

    }).attr('src', BASE_URI+'uploads/pyrocart/full/<?php echo $image[0]->productImage?>');
    
    
    $('.portfolio_image_right img').click(function(){
        //alert($(this).attr('src'));

        $('a.colorbox_image').attr('href',$(this).attr('rel'));

        $('#loader').html('');
        $('#loader').addClass('loading');
        var img = new Image();
        $(img).load(function () {

        $(this).hide();
        $('#loader').removeClass('loading').append(this);
        $(this).fadeIn();
        }).error(function () {

        }).attr('src', $(this).attr('rel'));
        
        //$("a.colorbox_image").colorbox({href:$(this).attr('rel')});
    });
    
    $('a.colorbox_image').click(function(){
        $("a.colorbox_image").colorbox({href:$(this).attr('href')});
    });
});

</script>

<?php
if($product->expire_date!=''){
    $expire_date = getdate(strtotime($product->expire_date));
    $year = $expire_date["year"];
    $month = $expire_date["mon"]-1;
    $day = $expire_date["mday"];
    $hours = $expire_date["hours"];
    $minutes = $expire_date["minutes"];
    $seconds = $expire_date["seconds"];
?>
<script>
$(document).ready(function(){
	var newYear = new Date('<?php echo $year ?>','<?php echo $month?>','<?php echo $day?>','<?php echo $hours?>','<?php echo $minutes?>','<?php echo $seconds?>');
$('#defaultCountdown').countdown({until: newYear,compact: true});

})
$(document).ready(function(){
    alert($(this).attr('rel'));$("a.colorbox_image").colorbox();
});
</script>
<?php } ?>



<div id="pagesidebar" class="rightStyle">
    <div id="productdetail">
        
        <h2><?php echo $product->title; ?></h2>
        
        <div class="productview">
            
            <div class="col1 leftStyle">
                <div id="loader">
                    <?php if (!empty($images)){?>
                    <img id="myGallery" src="{pyro:url:base}uploads/pyrocart/full/<?php echo $image[0]->product_image?>" alt="" title="<?php echo $image[0]->name?>"  />
                    <?php } ?>
                </div>
            </div><!--End Col1-->

            <div class="col2 leftStyle portfolio_image_right">
                <?php foreach($images as $img):?>
                <div class="raw">
                    <img rel='{pyro:url:base}uploads/pyrocart/full/<?php echo $img->product_image?>' style="width:64px;height:64px;overflow:hidden;" src="{pyro:url:base}uploads/pyrocart/thumbs/<?php echo $img->product_image_thumb?>" alt = "" title="<?php echo $img->name?>"/></a>
                </div>
                <?php endforeach;?>
            </div><!--End Col2-->

            
            <div class="col3 leftStyle">
                
                <?php echo form_open('pyrocart/cart/add_cart_item/','id="add_to_cart_form" method="post"');?>
                    <div class="description">
                        <span>Product Code:</span> <?php echo $product->product_code; ?><br>
                        <span>Availability:</span> <?php echo $product->stock?><br>
                        
                        <?php if($product->expire_date!=''):?>
                            <span>Time Left:</span> <span id="defaultCountdown"></span><br />
                        <?php endif;?>
                    </div>

                    <div class="price">Price: $<?php echo $product->price; ?></div>

                    <div class="cart">
                        <div>
                            <span>Qty:</span> <input name="quantity" size="2" value="1" type="text">
                            <input type="hidden" name="product_id" value="<?php echo $product->id?>"/>
                            <?php if($product->stock>0):?>
                                <input  name="addtocart" type="image" src="{pyro:url:base}addons/default/modules/pyrocart/img/cart.jpg" />
                            <?php else: ?>
                                <div class="sold_out" style="margin-left:5px;">&nbsp;</div>
                            <?php endif;?>
                        </div>
                    </div>
                    
                <?php echo form_close(); ?>
            </div><!--End Col3-->
            <?php if (!empty($images)){?>
            <div class="enlarge"><a class="colorbox_image" href="{pyro:url:base}uploads/pyrocart/full/<?php echo $image[0]->product_image?>">Click to enlarge</a></div>
            <?php } ?>
        </div>
        
        <br clear="both"/>
        <br clear="both"/>
        
        <h3>Description:</h3>
        <?php echo html_entity_decode($product->description);?>
    </div><!--End productrow-->
</div><!--End pagesidebar-->