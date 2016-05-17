deleteItem = (confirmMessage, callback) ->
    result = confirm(confirmMessage)
    if result
        action = $(this).attr('action')
        data = {
            _method: $(this._method).val()
            id: $(this.id).val()
        }

        $.ajax({
            url: action
            method: 'post'
            data: data
            dataType: 'json'
        }).done (response)->
            if response.deleted > 0
                callback(data.id)
            true

    true

restoreItem = (confirmMessage, callback) ->
    result = confirm(confirmMessage)
    if result
        action = $(this).attr('action')
        data = {
            _method: $(this._method).val()
            id: $(this.id).val()
        }

        $.ajax({
            url: action
            method: 'post'
            data: data
            dataType: 'json'
        }).done (response)->
            if response.restored > 0
                callback(data.id)
            true

    true

$(document).on 'submit', '.forbidden-post-delete', ()->
    deleteItem.call this, "Want to delete?", (dataId)->
        $('#forbidden-post-' + dataId).remove()
        true
    false

$(document).on 'submit', '.forbidden-post-restore', ()->
    restoreItem.call this, "Want to restore?", (dataId)->
        $('#forbidden-post-' + dataId).remove()
        true
    false


$(document).on 'submit', '.page-delete', ()->
    deleteItem.call this, "Want to delete?", (dataId)->
        window.location.reload()
        true
    false

$(document).on 'submit', '.page-restore', ()->
    restoreItem.call this, "Want to restore?", (dataId)->
        window.location.reload()
        true
    false