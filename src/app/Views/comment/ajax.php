<script>
    $(document).ready(function() {
        var currentPage = 1;
        var totalPages = 1;
        var commentsPerPage = 3;

        // Функция для загрузки комментариев
        function loadComments(page) {
            $.ajax({
                url: '/get-comments',
                method: 'GET',
                data: { page: page },
                dataType: 'json',
                success: function(data) {
                    var commentsHtml = '';
                    data.comments.forEach(function(comment) {
                        commentsHtml +=
                            `<div class="card mb-3" data-id="${comment.id}">
                                <div class="card-body">
                                    <p class="text-muted mb-3 small">${comment.date}</p>
                                    <h6 class="card-subtitle mb-2"><strong>${comment.name}</strong></h6>
                                    <p class="card-text">${comment.text}</p>
                                    <button class="btn btn-danger btn-sm delete-comment">Delete</button>
                                </div>
                            </div>`;
                    });

                    $('#comments-section').html(commentsHtml);

                    totalPages = data.totalPages;
                    updatePagination();
                },
                error: function(xhr, status, error) {
                    alert('Произошла ошибка при загрузке комментариев: ' + error);
                }
            });
        }

        // Функция для обновления пагинации
        function updatePagination() {
            $('#pagination').empty();
            var paginationHtml = '<div id="pagination" class="mb-3">';

            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                paginationHtml += `<button class="btn btn-secondary page-number ${activeClass}" data-page="${i}">${i}</button>`;
            }

            paginationHtml += '</div>';
            $('#pagination').append(paginationHtml);
        }

        // Обработчик кликов на кнопки с номерами страниц
        $(document).on('click', '.page-number', function() {
            currentPage = $(this).data('page');
            loadComments(currentPage);
        });

        // Обработчик удаления комментариев
        $('#comments-section').on('click', '.delete-comment', function(e) {
            e.preventDefault();

            var $card = $(this).closest('.card');
            var commentId = $card.data('id');

            $.ajax({
                url: '/delete/' + commentId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $card.remove();
                    loadComments(currentPage);

                    setTimeout(function() {
                        if ($('#comments-section .card').length === 0 && currentPage > 1) {
                            currentPage--;
                            loadComments(currentPage);
                        }
                    }, 100);
                },
                error: function(xhr, status, error) {
                    alert('Произошла ошибка при удалении комментария: ' + error);
                }
            });
        });

        // Обработчик отправки формы
        $("#comment-form").on("submit", function(e) {
            e.preventDefault();
            var name = $("#form-name").val();
            var date = $("#form-date").val();
            var text = $("#form-text").val();

            $.ajax({
                url: '/store',
                method: 'POST',
                data: {
                    name: name,
                    date: date,
                    text: text,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var newCommentHtml =
                            `<div class="card mb-3" data-id="${response.comment.id}">
                                <div class="card-body">
                                    <p class="text-muted mb-3 small">${response.comment.date}</p>
                                    <h6 class="card-subtitle mb-2"><strong>${response.comment.name}</strong></h6>
                                    <p class="card-text">${response.comment.text}</p>
                                    <button class="btn btn-danger btn-sm delete-comment">Delete</button>
                                </div>
                            </div>`;
                        
                        var commentCount = $('#comments-section .card').length;

                        if (commentCount < commentsPerPage) {
                            $('#comments-section').append(newCommentHtml);
                        } else {
                            currentPage = totalPages + 1;
                            $('#comments-section').html(newCommentHtml);
                        }

                        $("#comment-form")[0].reset();

                        var totalComments = response.totalComments;
                        totalPages = Math.ceil(totalComments / commentsPerPage);
                        updatePagination();
                        $('.page-number').removeClass('active');
                        $('.page-number[data-page="' + currentPage + '"]').addClass('active');
                    } else {
                        alert('Произошла ошибка при добавлении комментария.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Произошла ошибка: ' + error);
                }
            });
        });

        loadComments(currentPage);
    });
</script>