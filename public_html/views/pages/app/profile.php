<div class="page" data-page="profile">
  <div class="page-header">
    <h1>Профиль</h1>
  </div>
  <div class="card">
    <form id="profileForm" class="form grid">
      <label>Email
        <input type="email" name="email" required />
      </label>
      <label>Имя
        <input type="text" name="first_name" required />
      </label>
      <label>Фамилия
        <input type="text" name="last_name" required />
      </label>
      <label>Username
        <input type="text" name="username" required />
      </label>
      <div class="profile-meta">
        <div><strong>User ID:</strong> <span id="profileUserId">-</span></div>
        <div><strong>Тариф:</strong> <span id="profileTariff">-</span></div>
        <div><strong>Баланс:</strong> <span id="profileBalance">-</span> ₽</div>
      </div>
      <button type="submit" class="btn btn-accent">Сохранить</button>
    </form>
  </div>
</div>
