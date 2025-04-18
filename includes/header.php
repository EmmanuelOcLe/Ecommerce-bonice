<header>
    <div class="header-container">

        <div class="img-container">
            <img src="assets/img/bonice.png" alt="logo bonice" class="logo-bonice">
        </div>

        <nav class="navbar">
            <div class="container-fluid">
                

                <?php if (isset($_SESSION["user_rol"]) && $_SESSION["user_rol"] == "admin"): ?>
                <div class="admin-user-icon">
                    <i class="bi bi-person-circle"></i>
                    <i class="bi bi-chevron-down"></i>
                </div>

                <div class="admin-user-options">
                    <a href="">Gestionar productos</a>
                    <a href="">Gestionar categorías</a>
                    <a href="">Gestionar pedidos</a>
                    <a href="functions/cerrar_sesion.php">Cerrar Sesion</a>
                </div>

                <?php endif; ?>

                <div class="navbar-nav">

                    <a class="nav-link" href="index.php?page=home">Inicio</a>

                    <!-- Dropdown Productos -->
                    <div class="dropdown">
                        <a class="nav-link dropbtn" href="index.php?page=user/productos">Productos</a>
                        <div class="dropdown-content">
                            <a href="#">Bonice</a>
                            <a href="#">Popetas</a>
                        </div>
                    </div>

                    <a class="nav-link" href="index.php?page=user/quienes">Quienes Somos</a>
                    <a class="nav-link" href="index.php?page=user/equipo">Nuestro Equipo</a>
                </div>
            </div>
        </nav>

    </div>
</header>
