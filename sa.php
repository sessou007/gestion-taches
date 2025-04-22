<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Tâches</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Embedded Custom CSS -->
    <style>
        /* Reset & Global */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f7fb; }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 250px; height: 100%;
            background: #283593; padding: 1rem;
            display: flex; flex-direction: column;
            transition: transform 0.3s ease; z-index: 1000;
        }
        .sidebar.closed { transform: translateX(-100%); }
        .sidebar img { max-width: 140px; margin-bottom: 2rem; align-self: center; }
        .sidebar .nav-link {
            display: flex; align-items: center;
            color: #fff; padding: 0.75rem 1rem; margin-bottom: 0.5rem; border-radius: 4px;
            transition: background 0.2s; text-decoration: none;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.1); }
        .sidebar .nav-link i { font-size: 1.5rem; margin-right: 0.75rem; }

        /* Content Adjust */n        .content { margin-left: 250px; padding: 2rem; transition: margin-left 0.3s ease; }
        .content.fullwidth { margin-left: 0; }

        /* Home Grid Cards */
        .home-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .home-card {
            background: #fff; border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            text-align: center; padding: 2rem 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .home-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .home-card i { font-size: 3rem; color: var(--primary-color, #3f51b5); margin-bottom: 1rem; }
        .home-card h5 { font-size: 1.1rem; color: #333; margin: 0; }

        /* FullCalendar */
        #calendar { max-width: 900px; margin: 2rem auto; }

        /* Modal & Alerts */
        .modal-content { border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); padding: 1.5rem; }
        .modal-header, .modal-footer { border: none; }
        .swal2-popup { font-family: 'Segoe UI', sans-serif; border-radius: 8px; }

        /* Responsive */
        @media (max-width: 992px) {
            .content { padding: 1rem; }
            .sidebar { width: 200px; }
        }
        @media (max-width: 768px) {
            .sidebar { position: absolute; height: auto; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .content { margin-left: 0; }
        }
        @media (max-width: 576px) {
            .home-card { padding: 1.5rem 0.5rem; }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar closed" id="sidebar">
        <img src="images/armo.png" alt="Logo">
        <a href="acceuil.php" class="nav-link"><i class="bi bi-speedometer2"></i> Tableau de Bord</a>
        <a href="listedirect.php" class="nav-link"><i class="bi bi-people-fill"></i> Agents</a>
        <a href="tac.php" class="nav-link"><i class="bi bi-graph-up-arrow"></i> Tâches</a>
        <a href="rapport.php" class="nav-link"><i class="bi bi-stack"></i> Rapports</a>
        <a href="assigned_tasks.php" class="nav-link"><i class="bi bi-list-task"></i> Tâches Assignées</a>
        <a href="home.php" class="nav-link"><i class="bi bi-gear-fill"></i> Page Admin</a>
    </nav>

    <!-- Hamburger -->
    <button class="btn btn-primary d-md-none m-3" id="toggleSidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Main Content -->
    <div class="content fullwidth" id="content">
        <?php include('respons.php'); ?>
        <div class="container-fluid">
            <h1 class="text-center mb-4">Suivi et gestion des tâches</h1>
            <div class="home-grid">
                <?php if ($postid == $postv['id']): ?>
                    <div class="home-card">
                        <a href="listedirect.php" class="stretched-link text-decoration-none text-dark">
                            <i class="bi bi-people-fill mb-2 text-success"></i>
                            <h5>Agents</h5>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="home-card">
                    <a href="tac.php" class="stretched-link text-decoration-none text-dark">
                        <i class="bi bi-graph-up-arrow mb-2 text-primary"></i>
                        <h5>Tâches</h5>
                    </a>
                </div>
                <div class="home-card">
                    <a href="rapport.php" class="stretched-link text-decoration-none text-dark">
                        <i class="bi bi-stack mb-2 text-warning"></i>
                        <h5>Rapports</h5>
                    </a>
                </div>
                <?php if ($postid == $postv['id'] || $postid == $postv1['id']): ?>
                    <div class="home-card">
                        <a href="assigned_tasks.php" class="stretched-link text-decoration-none text-dark">
                            <i class="bi bi-list-task mb-2 text-info"></i>
                            <h5>Tâches Assignées</h5>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($postid == $postv2['id']): ?>
                    <div class="home-card">
                        <a href="home.php" class="stretched-link text-decoration-none text-dark">
                            <i class="bi bi-gear-fill mb-2 text-secondary"></i>
                            <h5>Page Admin</h5>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Calendar Section -->
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('closed');
            document.getElementById('content').classList.toggle('fullwidth');
        });
        // Initialize FullCalendar
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay' },
                editable: true,
                events: 'fetch_events.php'
            });
        });
    </script>
</body>
</html>