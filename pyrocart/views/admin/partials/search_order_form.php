
<div class="filter">
<form method="get">
<ul><li>
Order status: <?php echo form_dropdown('s_status', $order_status, $this->input->get('s_status')); ?>
</li>

<li>
Customer name: <input type="text" id="s_name" name="s_name" maxlength="100" value="<?php echo $this->input->get('s_name'); ?>" class="text" />
</li>
<li>
<li><button class="searchBtn" type="submit" name="searchMember" value="Search"><span>Search</span></button></li>

</ul>
</form>
<br class="clear-both">
</div>
