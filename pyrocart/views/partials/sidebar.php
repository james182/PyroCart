
<ul>
<?php foreach ($product_categories as $cat): ?>
        <li><?php echo anchor('products/search/' . $cat->id,$cat->name); ?>
                <?php $childs = $this->products_m->getChildCategories($cat->id);?>

                <?php if($childs):?>

                        <ul>
                        <?php foreach ($childs as $cat2): ?>
                                <li class="contentContainerMain green"><?php echo anchor('products/search/' . $cat2->id,$cat2->name); ?>


                                <?php $childs2 = $this->products_m->getChildCategories($cat2->id);?>

                                <?php if($childs2):?>

                                        <ul>
                                        <?php foreach ($childs2 as $cat3): ?>
                                                <li><?php echo anchor('products/search/' . $cat3->id,$cat3->name); ?></li>
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



