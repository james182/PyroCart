$(function() {
      $('.cart_ids').click(function() {
            var self = $(this);
            $.post('/gbc_2011/products/cart/delete_cart_item', {item_id: self.attr('rel')}, function(data) {
                  if (data.results) {
                        //alert(data.results);
                        self.parent().parent().fadeOut(1000, function() {
                              $(this).remove();
                              document.location.href = "/gbc_2011/products/cart/show_cart";
                        });
                  }
            }, 'json');
            return false;
      });
});
