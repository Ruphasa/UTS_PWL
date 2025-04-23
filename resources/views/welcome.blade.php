@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $userCount }}</h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ url('/user') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $productCount }}</h3>
                        <p>Products</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{ url('/barang') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $saleCount }}</h3>
                        <p>Sales</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ url('/penjualan') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $supplierCount }}</h3>
                        <p>Suppliers</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <a href="{{ url('/supplier') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left column -->
            <section class="col-lg-7 connectedSortable">
                <!-- Custom tabs (Charts with tabs)-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sales Overview</h3>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <!-- Morris chart - Sales -->
                            <div class="chart tab-pane active" id="revenue-chart"
                                style="position: relative; height: 300px;">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- Recent Sales -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Recent Sales</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSales as $sale)
                                        <tr>
                                            <td><a href="{{ url('/sales/' . $sale->id) }}">{{ $sale->penjualan_kode }}</a>
                                            </td>
                                            <td>{{ $sale->penjualan_tanggal }}</td>
                                            <td>{{ number_format($sale->penjualan_total, 0, ',', '.') }}</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <a href="{{ url('/sales') }}" class="btn btn-sm btn-info float-right">View All Sales</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </section>

            <!-- Right column -->
            <section class="col-lg-5 connectedSortable">
                <!-- Product Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Statistics</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-box"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Products</span>
                                        <span class="info-box-number">{{ $productCount }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i
                                            class="fas fa-low-vision"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Low Stock</span>
                                        <span class="info-box-number">{{ $lowStockCount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i
                                            class="fas fa-chart-line"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Categories</span>
                                        <span class="info-box-number">{{ $categoryCount }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1"><i
                                            class="fas fa-truck"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Active Suppliers</span>
                                        <span class="info-box-number">{{ $activeSupplierCount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Top Products</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach ($topProducts as $product)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ asset('Adminlte/dist/img/default-150x150.png') }}" alt="Product Image">
                                    </div>
                                    <div class="product-info">
                                        <a href="{{ url('/products/' . $product->id) }}"
                                            class="product-title">{{ $product->barang_nama }}
                                            <span class="badge badge-warning float-right">{{ $product->jumlah }}</span></a>
                                        <span class="product-description">
                                            {{ $product->kategori_nama }} | Stock: {{ $product->jumlah }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer text-center">
                        <a href="{{ url('/products') }}" class="uppercase">View All Products</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </section>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    @endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {{ Js::from($chartLabels) }},
                    datasets: [{
                        label: 'Sales Amount',
                        data: {{ Js::from($chartData) }},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush