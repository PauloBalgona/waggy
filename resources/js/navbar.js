const navbar = `
<nav class="navbar navbar-expand-lg py-2 position-absolute w-100 top-0 start-0"
     style="z-index:10;">
  <div class="container-fluid px-3">

    <a class="d-flex align-items-center ms-1 text-decoration-none" href="/">
      <img src="/assets/logo.png" alt="Waggy logo" style="height:35px;margin-right:8px;">
      <div class="d-flex flex-column lh-1">
        <span class="fw-semibold text-dark" style="font-size:1rem;">Waggy</span>
        <small class="text-muted" style="font-size:0.65rem;">Community</small>
      </div>
    </a>

    <!-- MOBILE MENU BUTTON -->
    <button class="navbar-toggler d-lg-none border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mobileMenu">
      <i class="bi bi-list fs-3 text-dark"></i>
    </button>

    <!-- DESKTOP NAV -->
    <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex">
      <ul class="navbar-nav align-items-center gap-1">
        <li><a class="nav-link text-dark fw-medium" style="font-size:0.9rem;" href="/">Home</a></li>
        <li><a class="nav-link text-dark fw-medium" style="font-size:0.9rem;" href="/discover">Discover</a></li>
        <li><a class="nav-link text-dark fw-medium" style="font-size:0.9rem;" href="/guidelines">Security Guidelines</a></li>
        <li><a class="nav-link text-dark fw-medium" style="font-size:0.9rem;" href="/helpcenter">Help Center</a></li>
      </ul>
    </div>

    <!-- DESKTOP LOGIN -->
    <div class="d-none d-lg-flex align-items-center me-1">
      <a class="btn btn-primary px-3 py-1 text-white"
         style="font-size:0.85rem;border-radius:6px;"
         href="/login">Login</a>
    </div>

  </div>

  <!-- MOBILE MENU -->
  <div class="collapse d-lg-none w-100 bg-white shadow-sm text-center" id="mobileMenu">
    <ul class="navbar-nav py-4 gap-3 w-100">
      <li><a class="nav-link fw-medium text-dark" href="/">Home</a></li>
      <li><a class="nav-link fw-medium text-dark" href="/discover">Discover</a></li>
      <li><a class="nav-link fw-medium text-dark" href="/guidelines">Security Guidelines</a></li>
      <li><a class="nav-link fw-medium text-dark" href="/helpcenter">Help Center</a></li>

      <li class="px-4">
        <a class="btn btn-primary w-100 mt-2" href="/login">Login</a>
      </li>
    </ul>
  </div>
</nav>
`;

export default navbar;
