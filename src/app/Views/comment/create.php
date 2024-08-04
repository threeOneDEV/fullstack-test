<h1 class="mb-4">Добавить комментарий</h1>

<form id="comment-form" method="POST" class="mb-3">
    <div class="mb-3">
        <label for="name" class="form-label">Email</label>
        <input name="name" id="form-name" type="email" class="form-control" placeholder="Введите ваш email" required>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Дата</label>
        <input name="date" id="form-date" type="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="text" class="form-label">Комментарий</label>
        <textarea name="text" id="form-text" class="form-control" rows="5" placeholder="Введите ваш комментарий" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary" id="add">Отправить</button>
</form>