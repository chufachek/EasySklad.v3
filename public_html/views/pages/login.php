<div class="auth-page" data-page="login">
  <div class="auth-card">
    <h1>Вход в Easy. склад</h1>
    <?php $success = Helpers::flash('success'); $error = Helpers::flash('error'); ?>
    <?php if ($success): ?>
      <div class="notice success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="/auth/login" class="form">
      <label>Email
        <input type="email" name="email" required />
      </label>
      <label>Пароль
        <input type="password" name="password" required />
      </label>
      <button type="submit" class="btn btn-accent">Войти</button>
    </form>
    <p>Нет аккаунта? <a href="/register">Регистрация</a></p>
  </div>
</div>
