<nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
                      <!-- Judul RME KLINIK di Navbar -->
        <div class="d-flex align-items-center">
            <span class="fw-bold text-dark" style="font-size: 24px;">Rekam Medis Elektronik (RME) Klinik</span>
        </div>

              <!-- <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input 
                    type="text"
                    placeholder="Search ..."
                    class="form-control"
                  />
                </div>
              </nav> -->

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >                    
                    <span class="profile-username">
                      @if(Auth::check())
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                        <span class="profile-role text-muted ms-2">({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})</span>
                      @else
                        <span class="fw-bold">Guest</span>
                      @endif
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="u-text">
                            @if(Auth::check())
                              <h4>{{ Auth::user()->name }}</h4>
                              <p class="text-muted">{{ Auth::user()->email }}</p>
                            @endif
                          </div>
                        </div>
                      </li>
                      @if(Auth::check())
                        <li>
                          <div class="dropdown-divider"></div>
                          <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                              Logout
                            </button>
                          </form>
                        </li>
                      @else
                        <li>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                        </li>
                      @endif
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>