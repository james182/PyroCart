
<div class="filter">
<form method="get">
<ul><li>
Category: <?php echo form_dropdown('s_category', $product_categories, $this->input->get('s_category')); ?>
</li>

<li>
Product name: <input type="text" id="s_name" name="s_name" maxlength="100" value="<?php echo $this->input->get('s_name'); ?>" class="text" />
</li>
<li>
<li><button class="searchBtn" type="submit" name="searchProduct" value="Search"><span>Search</span></button></li>

</ul>
</form>
<br class="clear-both">
</div>
