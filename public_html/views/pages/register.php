<div class="auth-page" data-page="register">
  <div class="auth-card">
    <h1>Регистрация</h1>
    <?php $error = Helpers::flash('error'); ?>
    <?php if ($error): ?>
      <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="/auth/register" class="form">
      <label>Имя
        <input type="text" name="first_name" required />
      </label>
      <label>Фамилия
        <input type="text" name="last_name" required />
      </label>
      <label>Username
        <input type="text" name="username" required />
      </label>
      <label>Email
        <input type="email" name="email" required />
      </label>
      <label>Пароль
        <input type="password" name="password" required />
      </label>
      <button type="submit" class="btn btn-accent">Создать аккаунт</button>
    </form>
    <p>Уже есть аккаунт? <a href="/login">Войти</a></p>
  </div>
</div>
