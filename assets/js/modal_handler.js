/*
Use like this
 <a href="/admin/catalog/importset/15" data-toggle="koms-modal" data-target="#modal">Import</a>
OR
 <button data-source="/admin/catalog/importset/15" data-toggle="koms-modal" data-target="#modal">Import</button>
*/
$(function(){
    $('a[data-toggle="koms-modal"]').on("click", function(e){
        e.preventDefault();
        var data = $(this).data();
        var modal = $(data.target);
        var url = $(this).attr('href') != typeof undefined ? $(this).attr('href') : data.source ;
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    modal.html(data.content);
                    modal.modal('show');
                    modal.find('form').on('submit', function(e){
                        e.preventDefault();
                        $.ajax({
                            url: $(this).attr('action'),
                            method: 'post',
                            data: $(this).serializeArray(),
                            dataType: 'json',
                            success: function(data){
                                if(data.status){
                                    modal.modal('hide');
                                    $('#notify').notify({
                                        message: { html: data.message }
                                    }).show();
                                }
                                else{
                                    modal.find('#errors').html(data.errors);
                                }
                            }
                        });
                    });
                }
                else{
                    message = typeof data.message !== 'undefined' ? data.message : "An error appear while loading";
                    $('#notify').notify({
                        message: { text: message },
                        type: 'danger'
                    }).show();
                }
            }
        });
    });
});