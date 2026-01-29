<div class="page" data-page="products">
  <div class="page-header">
    <h1>Товары</h1>
    <button class="btn btn-accent" id="createProduct">Добавить товар</button>
  </div>
  <div class="card">
    <div class="table-controls">
      <input type="text" id="productSearch" placeholder="Поиск" />
      <select id="categoryFilter" class="select"></select>
    </div>
    <table class="table" id="productsTable">
      <thead><tr><th>SKU</th><th>Название</th><th>Цена</th><th>Остаток</th><th></th></tr></thead>
      <tbody></tbody>
    </table>
  </div>
</div>
