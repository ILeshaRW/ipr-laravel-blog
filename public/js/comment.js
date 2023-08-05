$( document ).ready(function() {
    $("[data-edit-comment-id]").on('click', function () {
        var commentId = $(this).data('edit-comment-id');

        $('#edit_comment_' + commentId).toggle();
    });
});
