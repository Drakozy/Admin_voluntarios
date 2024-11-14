<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiViaje.com</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #scrollToTopBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px 15px;
            font-size: 24px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <div class="bg-primary text-white py-5 w-100">
    <header class="container d-flex justify-content-between align-items-right">
            <nav>
                <a href="#" class="text-white me-3">Ayuda</a>

                <!-- Botón de Ingresar (se muestra solo si el usuario no está autenticado) -->
                @guest
                    <a href="#" class="text-white me-3" data-bs-toggle="modal" data-bs-target="#loginModal">Ingresar</a>
                @endguest

                <!-- Botón Mi cuenta (se muestra solo si el usuario está autenticado) -->
                @auth
                    <a href="#" class="text-white">Mi cuenta</a> 
                    <a href="{{ route('logout') }}" class="text-white" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endauth
            </nav>
        </header>

        <div class="container text-center mt-5">
            <h1 class="display-4">Encuentra <span class="fw-bold">proyectos</span> para aportar a cuidar el planeta</h1>
            <form action="/buscador" method="POST" class="d-flex justify-content-center mt-4" onsubmit="return false;">
                <input type="text" id="busqueda" class="form-control w-50 me-2" placeholder="Argentina, Colombia, España" onkeyup="filtrarProyectos()">
                <button type="button" class="btn btn-secondary" onclick="limpiarBusqueda()">Limpiar</button>
                </form>
            </form>
        </div>
    </div>

    <main class="container my-5">
        <section class="mb-5">
            <h2 class="mb-4">Proyectos Disponibles</h2>
            <div class="row row-cols-1 row-cols-md-4 g-4" id="proyectos-container">
                @foreach($proyectos as $proyecto)
                    <div class="col proyecto-card" data-nombre="{{ $proyecto->nombre }}" data-lugar="{{ $proyecto->lugar }}" data-pais="{{ $proyecto->pais }}">
                        <div class="card h-100">
                        <img src="{{ asset('storage/' . explode(' ', trim($proyecto->nombre))[0] . '.jpg') }}" class="card-img-top" alt="Imagen del Proyecto">
                            <div class="card-body">
                                <h5 class="card-title">{{ $proyecto->nombre }}</h5>
                                <p class="card-text">{{ Str::limit($proyecto->descripcion, 100) }}</p>
                                <p><strong>Lugar:</strong> {{ $proyecto->lugar }}</p>
                                <p><strong>Pais:</strong> {{ $proyecto->pais }}</p>
                                <p><strong>Fecha de inicio:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d M, Y') }}</p>
                                <p><strong>Fecha de fin:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d M, Y') }}</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('Proyecto.inscribir', $proyecto->id) }}" class="btn btn-primary">Inscribirse</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-5">
            <h2>Destinos donde desarrollamos proyectos</h2>
            <div class="row row-cols-1 row-cols-md-4 g-4" id="destinos-populares-container">
                <!-- Aquí se agregan los destinos populares sin repeticiones -->
            </div>
        </section>
        <button id="scrollToTopBtn" onclick="scrollToTop()">↑</button>
    </main>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h5>MiAyudaEnProyectos.com</h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Síguenos</h5>
                    <nav>
                        <a href="http://facebook.com" target="_blank" class="d-block text-white">Facebook</a>
                        <a href="http://twitter.com" target="_blank" class="d-block text-white">Twitter</a>
                        <a href="http://instagram.com" target="_blank" class="d-block text-white">Instagram</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal de Inicio de Sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <span>¿No tienes cuenta?</span>
                    <a href="{{ route('registro') }}" class="text-primary">Crear cuenta</a>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript para filtrar proyectos y mostrar destinos populares únicos -->
    <script>
        window.onscroll = function() {
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // Función para desplazar la página hacia la parte superior
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Función para filtrar los proyectos según la búsqueda
        function filtrarProyectos() {
            const busqueda = document.getElementById('busqueda').value.toLowerCase();
            const proyectos = document.querySelectorAll('.proyecto-card');
            proyectos.forEach(proyecto => {
                const nombre = proyecto.getAttribute('data-nombre').toLowerCase();
                const lugar = proyecto.getAttribute('data-lugar').toLowerCase();
                const pais = proyecto.getAttribute('data-pais').toLowerCase();
                if (nombre.includes(busqueda) || lugar.includes(busqueda) || pais.includes(busqueda)) {
                    proyecto.style.display = 'block';
                } else {
                    proyecto.style.display = 'none';
                }
            });
        }

        // Función para agregar los destinos populares sin duplicados
        function mostrarDestinosPopulares() {
            const destinosContainer = document.getElementById('destinos-populares-container');
            const proyectos = document.querySelectorAll('.proyecto-card');
            const paisesUnicos = new Set(); // Usamos un Set para evitar duplicados

            proyectos.forEach(proyecto => {
                const pais = proyecto.getAttribute('data-pais');
                if (!paisesUnicos.has(pais)) {
                    paisesUnicos.add(pais); // Agregamos el país al Set (si no está ya)
                    const destinoCard = document.createElement('div');
                    destinoCard.classList.add('col');
                    destinoCard.innerHTML = `
                        <div class="card" onclick="scrollToTopAndSetSearch('${pais}')">
                            <img src="{{asset('storage/${pais}.jpg')}}" class="card-img-top" alt="${pais}">
                            <div class="card-body">
                                <h5 class="card-title">${pais}</h5>
                                <p class="card-text">Descubre los mejores proyectos en ${pais}</p>
                            </div>
                        </div>
                    `;
                    destinosContainer.appendChild(destinoCard);
                }
            });
        }

        // Llamamos a la función para mostrar los destinos populares
        mostrarDestinosPopulares();

        function scrollToTopAndSetSearch(pais) {
    
            scrollToTop()
            document.querySelector('input[id="busqueda"]').value = pais;
            filtrarProyectos();
        }

        function limpiarBusqueda() {
            const searchInput = document.getElementById('busqueda');
            searchInput.value = '';  // Limpiar el valor del campo de búsqueda
            filtrarProyectos();      // Llamar a la función para actualizar la lista de proyectos
        }
    </script>
</body>
</html>
