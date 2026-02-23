<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Stok vs Penjualan Chart (2 grid) -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-medium">Stok vs Penjualan per Kategori</h3>
                <p class="text-sm text-gray-500">Perbandingan stok dan penjualan tiap kategori</p>
            </div>
        </div>
        <div id="stockSalesChart"></div>
    </div>

    <!-- Kategori Produk Chart (1 grid) -->
    <div class="bg-white p-6 rounded-xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-medium">Kategori Produk</h3>
                <p class="text-sm text-gray-500">Distribution by category</p>
            </div>
        </div>
        <div id="kategoriProdukChart"></div>
    </div>
</div>

<script>
// Stok vs Penjualan Chart
var stockSalesOptions = {
    series: [{
        name: 'Stok',
        data: @json($stockData)
    }, {
        name: 'Terjual',
        data: @json($salesData)
    }],
    chart: {
        height: 350,
        type: 'bar',
        stacked: false,
        toolbar: {
            show: false
        }
    },
    colors: ['#3B82F6', '#10B981'],
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 4,
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    grid: {
        borderColor: '#f1f1f1',
    },
    xaxis: {
        categories: @json($categories)
    },
    yaxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'right'
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + " unit"
            }
        }
    }
};

// Kategori Produk Chart
var kategoriProdukOptions = {
    series: @json($series), // Data jumlah produk per kategori
    chart: {
        type: 'pie',
        height: 350
    },
    labels: @json($labels), // Data nama kategori
    colors: ['#3B82F6', '#8B5CF6', '#F97316', '#EF4444', '#10B981'], // Tambah warna sesuai jumlah kategori
    legend: {
        position: 'bottom'
    },
    dataLabels: {
        formatter: function (val) {
            return val.toFixed(1) + "%"
        }
    }
};

// Render Charts
var stockSalesChart = new ApexCharts(document.querySelector("#stockSalesChart"), stockSalesOptions);
var kategoriProdukChart = new ApexCharts(document.querySelector("#kategoriProdukChart"), kategoriProdukOptions);

stockSalesChart.render();
kategoriProdukChart.render();
</script>