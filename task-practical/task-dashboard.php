<?php include 'header.php'; ?>

<body>

    <div class="d-flex">

        <!-- Sidebar -->

        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->

        <div class="main-content">

            <!-- Navbar -->

            <nav class="navbar navbar-expand-lg bg-white shadow-sm">

                <div class="container-fluid">

                    <h4 class="fw-bold">Practice Set 1 – Variables (let, const, var)</h4>

                    <div class="ms-auto">

                        <button class="btn btn-primary">
                            <i class="bi bi-bell"></i>
                        </button>

                    </div>

                </div>

            </nav>

            <div class="container-fluid mt-4">

                <!-- Cards -->

                <div class="row g-4">

                    <div class="col-lg-3 col-md-6">

                        <div class="card stat-card shadow-sm">

                            <div class="card-body d-flex justify-content-between">

                                <div>

                                    <h6>Total Users</h6>

                                    <h2>2,540</h2>

                                </div>

                                <div class="icon-box bg-primary">

                                    <i class="bi bi-people"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <div class="card stat-card shadow-sm">

                            <div class="card-body d-flex justify-content-between">

                                <div>

                                    <h6>Orders</h6>

                                    <h2>1,260</h2>

                                </div>

                                <div class="icon-box bg-success">

                                    <i class="bi bi-cart"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <div class="card stat-card shadow-sm">

                            <div class="card-body d-flex justify-content-between">

                                <div>

                                    <h6>Revenue</h6>

                                    <h2>$12K</h2>

                                </div>

                                <div class="icon-box bg-warning">

                                    <i class="bi bi-currency-dollar"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <div class="card stat-card shadow-sm">

                            <div class="card-body d-flex justify-content-between">

                                <div>

                                    <h6>Products</h6>

                                    <h2>325</h2>

                                </div>

                                <div class="icon-box bg-danger">

                                    <i class="bi bi-box"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Table + Profile -->

                <div class="row mt-4">

                    <div class="col-lg-8">

                        <div class="card shadow-sm">

                            <div class="card-header bg-white">

                                <h5>Recent Orders</h5>

                            </div>

                            <div class="card-body">

                                <table class="table table-hover">

                                    <thead>

                                        <tr>

                                            <th>ID</th>

                                            <th>Customer</th>

                                            <th>Status</th>

                                            <th>Amount</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td>#1001</td>

                                            <td>Rahul</td>

                                            <td><span class="badge bg-success">Completed</span></td>

                                            <td>$150</td>

                                        </tr>

                                        <tr>

                                            <td>#1002</td>

                                            <td>Priya</td>

                                            <td><span class="badge bg-warning">Pending</span></td>

                                            <td>$90</td>

                                        </tr>

                                        <tr>

                                            <td>#1003</td>

                                            <td>Amit</td>

                                            <td><span class="badge bg-danger">Cancelled</span></td>

                                            <td>$200</td>

                                        </tr>

                                        <tr>

                                            <td>#1004</td>

                                            <td>Neha</td>

                                            <td><span class="badge bg-primary">Processing</span></td>

                                            <td>$340</td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <!-- Profile -->

                    <div class="col-lg-4">

                        <div class="card shadow-sm profile-card">

                            <div class="card-body text-center">

                                <img
                                    src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/javascript/javascript-original.svg" />

                                <h4>Jyoti Singh</h4>

                                <p class="text-muted">Frontend Developer</p>

                                <hr>

                                <h6>Project Progress</h6>

                                <div class="progress mb-3">

                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        style="width:75%">

                                        75%

                                    </div>

                                </div>

                                <button class="btn btn-primary w-100">

                                    View Profile

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
