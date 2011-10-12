<html>
<head>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/text.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>system/pyrocms/assets/css/admin/buttons.css">

<style>
body{background:#fff;}
form.crud li {
    padding: 2px;
}
.error_pop{ background: #FFCECE;
    border-color: #DF8F8F;
    color: #665252;padding-left:10px;}
</style>
</head>
<body>
<h3>List of order items</h3>


<table class="table-list">
<thead><tr><th>ID</th><th>Description</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>
<?php foreach($order_items  as $row):?>
<tr>
<td><?php echo $row->id;?></td>
<td><?php echo $row->description;?></td>
<td><?php echo $row->price;?></td>
<td><?php echo $row->quantity;?></td>
<td><?php echo $row->subtotal;?></td>
</tr>
<?php endforeach;?>
</table>

</body>
</html>