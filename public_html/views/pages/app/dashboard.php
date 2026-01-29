<div class="page" data-page="dashboard">
  <div class="page-header">
    <h1>Дашборд</h1>
    <div class="range-switch" id="dashboardRange">
      <button data-range="7d" class="btn btn-light active">7 дней</button>
      <button data-range="30d" class="btn btn-light">30 дней</button>
    </div>
  </div>
  <div class="cards-grid">
    <div class="card metric">
      <div class="label">Выручка</div>
      <div class="value" id="metricRevenue">0 ₽</div>
    </div>
    <div class="card metric">
      <div class="label">Продажи</div>
      <div class="value" id="metricOrders">0</div>
    </div>
    <div class="card metric">
      <div class="label">Остатки</div>
      <div class="value" id="metricStock">0 SKU</div>
    </div>
    <div class="card metric">
      <div class="label">Последние операции</div>
      <div class="value" id="metricOps">0</div>
    </div>
  </div>
  <div class="charts-grid">
    <div class="card">
      <div class="card-title">Выручка</div>
      <canvas id="revenueChart" height="120"></canvas>
    </div>
    <div class="card">
      <div class="card-title">Структура заказов</div>
      <canvas id="pieChart" height="120"></canvas>
    </div>
  </div>
  <div class="dashboard-row">
    <div class="card orders-table">
      <div class="card-title">Последние заказы</div>
      <table class="table" id="lastOrders">
        <thead><tr><th>ID</th><th>Клиент</th><th>Сумма</th><th>Статус</th><th>Дата</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="card">
      <div class="card-title">Параметры заказов</div>
      <div class="stat-list" id="orderStats"></div>
    </div>
  </div>
  <div class="dashboard-row">
    <div class="card">
      <div class="card-title">Топ товаров</div>
      <ul class="list" id="topProducts"></ul>
    </div>
    <div class="card">
      <div class="card-title">Низкий остаток</div>
      <ul class="list" id="lowStock"></ul>
    </div>
    <div class="card">
      <div class="card-title">Активность</div>
      <ul class="list" id="activity"></ul>
    </div>
  </div>
</div>
