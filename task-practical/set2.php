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

                    <h4 class="fw-bold">Set 2 – Props</h4>

                    <div class="ms-auto">

                        <button class="btn btn-primary">
                            <i class="bi bi-bell"></i>
                        </button>

                    </div>

                </div>

            </nav>

            <div class="container-fluid mt-4">

                <!-- Cards -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-3">Ek ProductCard.jsx component banao jo props accept kare</h3>
                        <b class="text-success text-capitalize">output</b>
                        <div class="mb-3 mt-3 card shadow-sm p-3">
                            <code><pre>
name
price
category
inStock
                            </pre></code>
                            <p>App.jsx se 3 products bhejo:</p>
                            <code><pre>
&lt;ProductCard
  name="Laptop"
  price={55000}
  category="Electronics"
  inStock={true}
/&gt;

&lt;ProductCard
  name="Shoes"
  price={2500}
  category="Fashion"
  inStock={false}
/&gt;
                            </pre></code>
                        </div>
                    </div>
                </div>

                <!-- script -->

                <script></script>

                <!-- Previous & Next Buttons -->
                <div class="button-wrapper">
                    <a href="set6_functions.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>

                    <a href="set8_object.php" class="btn btn-primary">
                        Next <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
