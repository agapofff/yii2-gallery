if (typeof yii2gallery == 'undefined' || !yii2gallery) {
    var yii2gallery = {};
}

yii2gallery = {
    init: function () {
        $('.dvizh-gallery-item a.delete').on('click', this.deleteProductImage);
        $('.dvizh-gallery-item a.write').on('click', this.callModal);
        $('.dvizh-gallery img').on('click', this.setMainProductImage);
        $('.noctua-gallery-form').on('submit', this.writeProductImage);
    },
    
    setMainProductImage: function () {
        var url = $(this).data('action'),
            data = $(this).parents('.dvizh-gallery-item').data();
            
        yii2gallery._sendData(url, data);
        $('.dvizh-gallery-item').removeClass('main');
        $(this).parents('.dvizh-gallery-item').addClass('main');
        return false;
    },

    writeProductImage: function (event) {
        event.preventDefault();
        
        var form = $(this).find('form'),
            url = form.attr('action'),
            data = form.serialize();
            
        $.when(yii2gallery._sendData(url, data)).then(function () {
            $('#noctua-gallery-modal').modal('hide');
        });
    },

    callModal: function (event) {
        event.preventDefault();

        var url = $(this).data('action'),
            data = $(this).parents('.dvizh-gallery-item').data();
            
        delete data.sortableItem;
        
        $('.noctua-gallery-form').load(url, data, function () {
            $('#noctua-gallery-modal').modal('show');
        });
    },
    
    deleteProductImage: function () {
        var url = $(this).data('action'),
            data = $(this).parents('.dvizh-gallery-item').data();
            
        if (confirm($(this).data('confirm'))) {
            yii2gallery._sendData(url, data);
            $(this).parents('.dvizh-gallery-item').remove();
        }
        
        return false;
    },
    
    setSort: function(event, ui){
        $('.dvizh-gallery').each(function(){
            var url = $(this).data('action'),
                data = {
                    items: []
                };
                
            $(this).find('.dvizh-gallery-item').each(function(){
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