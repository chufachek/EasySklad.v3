var Charts = {
  line: null,
  pie: null,
  render: function (series, pie) {
    var ctx = document.getElementById('revenueChart');
    var pieCtx = document.getElementById('pieChart');
    if (!ctx || !pieCtx) return;
    var labels = series.map(function (s) { return s.date; });
    var values = series.map(function (s) { return s.value; });
    var pieLabels = pie.map(function (p) { return p.label; });
    var pieValues = pie.map(function (p) { return p.value; });

    if (this.line) this.line.destroy();
    if (this.pie) this.pie.destroy();

    this.line = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Выручка',
          data: values,
          borderColor: getComputedStyle(document.documentElement).getPropertyValue('--accent'),
          backgroundColor: 'rgba(33,163,102,0.15)',
          tension: 0.4
        }]
      },
      options: {
        plugins: {
          tooltip: { enabled: true }
        }
      }
    });

    this.pie = new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: pieLabels,
        datasets: [{
          data: pieValues,
          backgroundColor: ['#21a366', '#a5b4fc']
        }]
      },
      options: {
        plugins: {
          tooltip: { enabled: true }
        }
      }
    });
  }
};
