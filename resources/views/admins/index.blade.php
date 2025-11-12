    @extends('layouts.admin')
    @section('content')

    <body>
        <div class="container">
        <div class="navigation">
        <ul>
            <li>
                <a href="{{ route('all.admins') }}">
                    <span class="icon">
                        <ion-icon name="people"></ion-icon>
                    </span>
                    <span class="title">Admin</span>
                </a>
            </li>
            <li>
                <a href="{{ route('all.bookings') }}">
                    <span class="icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </span>
                    <span class="title">Bookings Management</span>
                </a>
            </li>
                <li>
                <a href="{{ route('all.orders') }}">
                    <span class="icon">
                        <ion-icon name="receipt-outline"></ion-icon>
                    </span>
                    <span class="title">Order Management</span>
                </a>
            </li>
                <li>
            <a href="{{ route('admin.raw-material.stock') }}">
            <span class="icon"><ion-icon name="cube-outline"></ion-icon></span>
            <span class="title">Ingredients Management</span>
                </a>
            </li>
            <li>
                <a href="{{ route('all.products') }}">
                    <span class="icon">
                        <ion-icon name="cart-outline"></ion-icon>
                    </span>
                    <span class="title">Products Management</span>
                </a>
            </li>
             <li>
                <a href="{{ route('admin.low.stock') }}">
                    <span class="icon">
                        <ion-icon name="warning-outline"></ion-icon>
                    </span>
                    <span class="title">Stock Product Management</span>
                </a>
            </li>
                    <li>
                <a href="{{ route('admins.help') }}">
                    <span class="icon">
                        <ion-icon name="help-outline"></ion-icon>
                    </span>
                    <span class="title">Help </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.sell.form') }}">
                    <span class="icon">
                        <ion-icon name="cash-outline"></ion-icon>
                    </span>
                    <span class="title">Sell Product</span>
                </a>
            </li>


                <li>
        <a href="{{ route('admin.sales.report') }}">
            <span class="icon">
                <ion-icon name="bar-chart-outline"></ion-icon>
            </span>
            <span class="title">Total Sales Report</span>
        </a>
        </li>
        <li>
            <a href="{{ route('admin.expenses') }}">
                <span class="icon">
                    <ion-icon name="cash-outline"></ion-icon>
                </span>
                <span class="title">Expenses</span>
            </a>
        </li>
            <li>
                <a href="{{ route('admin.logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="icon">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </span>
                    <span class="title">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <div class="cardBox">
        <a href="{{ route('all.admins') }}" class="card" style="background-color:#6c5ce7; color:#fff;">
            <div>
                <div class="numbers" style="font-size:2rem; font-weight:bold;">{{ $usersCount }}</div>
                <div class="cardName" style="font-size:1rem; font-weight:600; color:#fff; text-shadow:1px 1px 2px rgba(0,0,0,0.5);">
                    Total Admin
                </div>
            </div>
            <div class="iconBx">
                <ion-icon name="people-outline" style="color:#fff; font-size:2rem;"></ion-icon>
            </div>
        </a>

        <a href="{{ route('all.bookings') }}" class="card" style="background-color:#fdcb6e; color:#000;">
            <div>
                <div class="numbers" style="font-size:2rem; font-weight:bold;">{{ $productsCount }}</div>
                <div class="cardName" style="font-size:1rem; font-weight:600; color:#000; text-shadow:1px 1px 2px rgba(255,255,255,0.5);">
                    Total Bookings
                </div>
            </div>
            <div class="iconBx">
                <ion-icon name="calendar-outline" style="color:#000; font-size:2rem;"></ion-icon>
            </div>
        </a>

        <a href="{{ route('all.orders') }}" class="card" style="background-color:{{ $ordersCount > 50 ? '#d63031' : '#00cec9' }}; color:#fff;">
            <div>
                <div class="numbers" style="font-size:2rem; font-weight:bold;">{{ $ordersCount }}</div>
                <div class="cardName" style="font-size:1rem; font-weight:600; color:#fff; text-shadow:1px 1px 2px rgba(0,0,0,0.5);">
                    Total Orders
                </div>
            </div>
            <div class="iconBx">
                <ion-icon name="cart-outline" style="color:#fff; font-size:2rem;"></ion-icon>
            </div>
        </a>

        <a href="{{ route('all.orders') }}" class="card" style="background-color:{{ $earning > 1000 ? '#00b894' : '#e17055' }}; color:#fff;">
            <div>
                <div class="numbers" style="font-size:2rem; font-weight:bold;">${{ $earning }}</div>
                <div class="cardName" style="font-size:1rem; font-weight:600; color:#fff; text-shadow:1px 1px 2px rgba(0,0,0,0.5);">
                    Total Earnings
                </div>
            </div>
            <div class="iconBx">
                <ion-icon name="cash-outline" style="color:#fff; font-size:2rem;"></ion-icon>
            </div>
        </a>
    </div>

                <!-- ================ Order Details List ================= -->
                <div class="details">
                    <div class="recentOrders">
            <div class="cardHeader">
        <h2>Recent Orders</h2>
        <a href="{{ route('all.orders') }}" class="btn">View All</a>
    </div>

 <div class="recent-orders-table">
    <table>
        <thead>
            <tr>
                <td>Product</td>
                <td>Price</td>
                <td>Payment</td>
                <td>Status</td>
                <td>Order Date</td>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td>{{ $order->product->name ?? 'N/A' }}</td>
                <td>${{ $order->price }}</td>
                <td>{{ $order->payment_status ?? 'Pending' }}</td>
                <td>
                    <span class="badge
    @if(strtolower($order->status)=='pending') bg-warning
    @elseif(strtolower($order->status)=='cancelled') bg-danger
    @else bg-success-custom
    @endif px-3 py-1 rounded-pill">
    {{ $order->status }}
</span>

                </td>
                <td>{{ $order->created_at->timezone('Asia/Phnom_Penh')->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No recent orders</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


        </div>
            <!-- ================= Analytics Charts ================= -->
            <div class="analytics col-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="analyticsChart" style="width:100%; height:500px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </tbody>
                    </table>
                </div>
                    </div>
                </div>
            </div>

        <!-- =========== Scripts =========  -->
        <script src="assets/js/main.js"></script>

        <!-- ====== ionicons ======= -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        const analyticsChart = new Chart(ctx, {
            type: 'bar', // can change to 'line', 'pie', etc.
            data: {
                labels: ['SaleTotal', 'Orders', 'Expense', 'Earnings'],
                datasets: [{
    label: 'Statistics',
    data: [{{ $totalSales ?? 0 }}, {{ $ordersCount ?? 0 }}, {{ $totalExpenses ?? 0 }}, {{ $earning ?? 0 }}],
    backgroundColor: [
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)'
    ],
    borderColor: [
        'rgba(54, 162, 235, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)'
    ],
    borderWidth: 1,
    borderRadius: 8,
}]

            },
            options: {
    responsive: true,
    scales: {
        y: {
            min: 1,
            beginAtZero: false,
            ticks: {
                stepSize: 1
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    }
}
        });
    </script>
    </body>
    @endsection
