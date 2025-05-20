<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Quản lý Gara Ô tô</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<style>
  .navbar-custom {
    background-color: orange; /* xanh dương Bootstrap */
  }
  .navbar-custom .navbar-brand {
    color: #fff !important;
    font-size: 1.8rem;
    font-weight: bold;
  }
  .navbar-custom .nav-link,
  .navbar-custom .dropdown-toggle {
    color: #fff !important;
  }
  .navbar-custom .dropdown-menu {
    right: 0;
    left: auto;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-custom">
    <!-- Căn giữa thương hiệu -->
    <div class="mx-auto">
      <a class="navbar-brand" href="#">Garage Management</a>
    </div>

    <!-- Dropdown menu bên phải -->
    <div class="dropdown ms-auto me-3">
      <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Tài khoản
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
