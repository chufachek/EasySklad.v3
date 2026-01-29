<header class="app-header">
  <div class="header-left">
    <div class="logo" aria-label="Easy. склад">Easy. склад</div>
    <div class="header-selects">
      <select id="companySelect" class="select"></select>
      <select id="warehouseSelect" class="select"></select>
    </div>
  </div>
  <div class="header-actions">
    <button class="btn btn-light" data-modal="incomeQuick">Приход</button>
    <button class="btn btn-light" data-modal="orderQuick">Заказ</button>
    <button class="btn btn-accent" data-modal="pos">Касса</button>
    <button class="icon-btn" id="themeToggle" aria-label="Переключить тему">
      <span class="icon">☀️</span>
    </button>
    <div class="user-menu" id="userMenu">
      <button class="icon-btn">👤</button>
      <div class="user-dropdown">
        <div class="user-meta">
          <div><strong>ID:</strong> <span id="userId">-</span></div>
          <div><strong>Тариф:</strong> <span id="userTariff">-</span></div>
          <div><strong>Баланс:</strong> <span id="userBalance">-</span> ₽</div>
        </div>
        <a href="/app/profile">Профиль</a>
        <a href="/logout">Выход</a>
      </div>
    </div>
  </div>
</header>
<div class="breadcrumbs" id="breadcrumbs"></div>
<div class="app-body">
