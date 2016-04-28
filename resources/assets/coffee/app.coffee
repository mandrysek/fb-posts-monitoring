$(document).on 'submit', '.forbidden-post-delete', ()->
    action = $(this).attr('action')
    data = {
        _method : $(this._method).val()
        id: $(this.id).val()
    }



    $.ajax({
        url: action
        method: 'post'
        data: data
        dataType: 'json'
    }).done (response)->
        if response.deleted > 0
            $('#forbidden-post-' + data.id).remove()
        true
    false