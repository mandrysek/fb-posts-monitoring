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

commentsShow = (href)->
    $.ajax({
        url: href
        beforeSend: ()->
            $('.lightbox').show()
            $('.lightbox-loading').show()
            true
        method: 'get'
        dataType: 'html'
    }).done (response)->
        $('.lightbox-loading').hide()
        $('.lightbox-content').html(response).show()
        true
    false

$(document).on 'click', '.comments-show', ()->
    href = $(@).attr 'href'
    commentsShow href

$(document).on 'submit', '.comments-create', ()->
    action = $(@).attr 'action'
    form = @

    $.ajax({
        url: action
        data:
            message: $(form.message).val()
            last_comment: $(form.last_comment).val()
        method: 'post'
        dataType: 'json'
    }).done (response)->
        if response.error
            $('.comments-error').html(response.error.message).show()
        else
            $('.comments-error').html("").hide()
            if $(form.last_comment).val() == 0
                $(form).parent().children('.comments').html("")
            $(form.last_comment).val(response.last_comment)
            $(form).parent().children('.comments').append(response.html)

        true
    false

$(document).on 'click', '.comments-close', ()->
    $('.lightbox').hide()
    $('.lightbox-content').html("").hide()
    false