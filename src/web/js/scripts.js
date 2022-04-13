if (typeof yii2gallery == 'undefined' || !yii2gallery) {
    var yii2gallery = {};
}

yii2gallery = {
    init: function () {
        $('.yii2gallery-item .delete').on('click', this.deleteProductImage);
        $('.yii2gallery-item .write').on('click', this.callModal);
        $('.yii2gallery img').on('click', this.setMainProductImage);
        $('.yii2gallery-form').on('submit', this.writeProductImage);
    },
    
    setMainProductImage: function () {
        var url = $(this).data('action'),
            data = $(this).parents('.yii2gallery-item').data();
            
        yii2gallery._sendData(url, data);
        $('.yii2gallery-item').removeClass('main');
        $(this).parents('.yii2gallery-item').addClass('main');
        return false;
    },

    writeProductImage: function (event) {
        event.preventDefault();
        
        var form = $(this).find('form'),
            url = form.attr('action'),
            data = form.serialize();
            
        $.when(yii2gallery._sendData(url, data)).then(function () {
            $('#yii2gallery-modal').modal('hide');
        });
    },

    callModal: function (event) {
        event.preventDefault();

        var url = $(this).data('action'),
            data = $(this).parents('.yii2gallery-item').data();
            
        delete data.sortableItem;
        
        $('.yii2gallery-form').load(url, data, function () {
            $('#yii2gallery-modal').modal('show');
        });
    },
    
    deleteProductImage: function () {
        var url = $(this).data('action'),
            data = $(this).parents('.yii2gallery-item').data();
            
        if (confirm($(this).data('confirm'))) {
            yii2gallery._sendData(url, data);
            $(this).parents('.yii2gallery-item').remove();
        }
        
        return false;
    },
    
    setSort: function(event, ui){
        $('.yii2gallery').each(function(){
            var url = $(this).data('action'),
                data = {
                    items: []
                };
                
            $(this).find('.yii2gallery-item').each(function(){
                data.items.push(parseFloat($(this).attr('data-image')));
            });
            
            yii2gallery._sendData(url, data);
        });
    },
    
    _sendData: function (url, data) {
        return $.post(url, data, function (answer) {
            var json = $.parseJSON(answer);
            if (json.result == 'success') {
                return;
            } else {
                alert(json.error);
            }
        });
    }
};

yii2gallery.init();