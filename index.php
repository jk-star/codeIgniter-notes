<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>React Tutorial</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/github-dark.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <div class="progress-container">
        <div id="readingProgress"></div>
    </div>

    <!-- <button id="menuToggle" class="menu-btn">
        ☰
    </button> -->

    <button id="menuToggle" class="menu-btn" aria-label="Toggle Menu">
        <span class="menu-icon">&#9776;</span>
    </button>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <aside class="col-lg-2 sidebar">

                <h2 class="logo">
                    JavaScript Tutorial
                </h2>

                <div class="theme-toggle">
                    <button id="themeBtn" class="btn btn-outline-light btn-sm w-100">
                        🌙 Dark Mode
                    </button>
                </div>


                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Chapter...">
                </div>

                <ul id="chapter-list">

                </ul>

            </aside>

            <!-- Content -->
            <main class="col-lg-10 content-area">

                <div class="content-box">

                    <nav class="breadcrumb-nav">
                        <span id="breadcrumb"></span>
                    </nav>

                    <div class="page-tools">
                        <a href="mini-project/mini-project-dashboard.php" target="_blank" class="btn btn-warning btn-sm"
                            id="task-dashboard">
                            Mini Project
                        </a>
                        <a href="task-practical/task-dashboard.php" target="_blank" class="btn btn-success btn-sm"
                            id="task-dashboard">
                            Task Dashboard
                        </a>
                        <button id="printBtn" class="btn btn-primary btn-sm">
                            🖨 Print / Save PDF
                        </button>
                    </div>

                    <div id="content">

                    </div>

                    <div class="navigation">

                        <button id="prevBtn" class="btn btn-primary">
                            ← Previous
                        </button>

                        <button id="nextBtn" class="btn btn-success">
                            Next →
                        </button>

                    </div>

                </div>

                <aside class="toc">
                    <h5>📚 On This Page</h5>
                    <ul id="tocList"></ul>
                </aside>
                <button id="backToTop" class="back-to-top">
                    ↑
                </button>
            </main>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="js/chapters.js"></script>
    <script src="js/app.js"></script>

</body>

</html>
