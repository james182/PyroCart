
<ul>
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



