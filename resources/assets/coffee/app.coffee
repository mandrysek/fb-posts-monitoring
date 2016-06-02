itemAction = (confirmMessage, callback) ->
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
            if response.done > 0
                callback(data.id)
            true

    true

$(document).on 'submit', '.post-delete', ()->
    itemAction.call this, "Are you sure this post is OK?", (dataId)->
        $('#post-' + dataId).remove()
        true
    false

$(document).on 'submit', '.post-restore', ()->
    itemAction.call this, "Are you sure this post is not OK?", (dataId)->
        $('#post-' + dataId).remove()
        true
    false

$(document).on 'submit', '.post-evaluate', ()->
    itemAction.call this, "Are you sure you want to evaluate this post?", (dataId)->
        $('#post-' + dataId).remove()
        true
    false

$(document).on 'submit', '.post-forbid', ()->
    itemAction.call this, "Are you sure you want to forbid this post?", (dataId)->
        $('#post-' + dataId).remove()
        true
    false


$(document).on 'submit', '.page-delete', ()->
    itemAction.call this, "Want to delete page?", (dataId)->
        window.location.reload()
        true
    false

$(document).on 'submit', '.page-restore', ()->
    itemAction.call this, "Want to restore page?", (dataId)->
        window.location.reload()
        true
    false