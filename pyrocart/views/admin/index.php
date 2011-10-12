<?php if (!empty($products)): ?>
    <h3><?php echo lang('pyrocart.list_title'); ?></h3>
    
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th><?php echo lang('pyrocart.title');?></th>
                <th><?php echo lang('pyrocart.images');?></th>
                <th><?php echo lang('pyrocart.price');?></th>
                <th>Stock</th>
                <th>Expire date</th>
                <th><span><?php echo lang('pyrocart.actions');?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product_item): if($product_item->featured=='true'){$checked=true;}else{$checked=false;} ?>
                <tr>
                    <td><?php echo $product_item->title; ?></td>

                    <td><?php echo anchor('admin/pyrocart/product_image_manage/'. $product_item->id	, 'Manage', 'class="product_image_upload"'); ?></td>
                    <td><?php echo $product_item->price;?></td>
                    <td><?php echo $product_item->stock;?></td>

                    <td><?php 
                        $curdate = date('Y-m-d H:i:s');
                        if($product_item->expire_date < $curdate && $product_item->expire_date != '')
                        {
                            echo '<font color="red">Product has expired</font>';
                        }
                        else
                        { 
                            echo $product_item->expire_date;
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo anchor('admin/pyrocart/edit/' . $product_item->id, 'Edit'); ?>|
                        <a href="<?php echo base_url().'pyrocart/details/' . $product_item->id; ?>" target="_blank">View</a>|
                        <a href="<?php echo base_url().'admin/pyrocart/delete/' . $product_item->id; ?>" onclick="return confirm('Are you sure you want to delete this apartment?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
	</table>

<?php else: ?>
    <div class="blank-slate">
        <img src="<?php echo base_url().'addons/modules/pyrocart/img/products.png' ?>" />
        <h2><?php echo lang('pyrocart.no_products_error');?></h2>
    </div>
<?php endif;?>

<p><?php $this->load->view('admin/partials/pagination'); ?></p>

