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

                    <h4 class="fw-bold">Set 6 – map() + Components</h4>

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
                        <h3 class="mb-3"></h3>
                        <b class="text-success text-capitalize">output</b>
                        <div class="mb-3 mt-3 card shadow-sm p-3">
                            <b>Data:</b>
                            <code>
                                <pre>
const employees = [
  {
    id: 1,
    name: "Amit",
    role: "Frontend Developer",
    salary: 50000
  },
  {
    id: 2,
    name: "Rahul",
    role: "Backend Developer",
    salary: 60000
  },
  {
    id: 3,
    name: "Neha",
    role: "UI Designer",
    salary: 45000
  }
];
                            </pre>
                            </code>
                            <p>
                                EmployeeCard.jsx banao. <br />

                                map() se cards render karo.<br /> <br />
                                <b>Rules:</b>

                                map() compulsory , EmployeeCard compulsory , key compulsory <br/>

                                <b>Bonus:</b> Salary 50000 se zyada ho to High Salary show karo.
                            </p>
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