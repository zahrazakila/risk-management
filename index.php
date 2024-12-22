<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <link href="assets\css\palette.css" rel="stylesheet">
    <style>
        /* Konfigurasi Partikel */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1; /* Di belakang konten */
            background-color:rgb(230, 247, 235); /* Warna latar belakang */
        }

        /* Shadow tambahan untuk kotak */
        .login-box {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        /* Styling pesan error */
        .error-message {
            color: #ff0000;
            font-size: 0.875rem;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen">

    <!-- Particles.js Background -->
    <div id="particles-js"></div>

    <!-- Header -->

    <header class="bg-beige shadow w-full">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo and Title -->
            <div class="flex items-center space-x-2">
                <img src="assets/img/logo.png" alt="Logo" class="w-10 h-10">
                <span class="text-xl font-bold text-gray-700">Risk Management</span>
            </div>

            <!-- Home Button -->
            <div>
            <a href="index.html" class="btn btn-sm font-bold text-medium-green py-2 px-4 rounded transition duration-300 hover:bg-[#235347] hover:text-white">
                Home
            </a>
            </div>
        </div>
    </header>

    <!-- Login Form -->
    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white login-box rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-medium-green mb-6">Login</h2>
            <!-- Pesan Error -->
            <?php
            session_start(); // Mulai sesi
            if (isset($_SESSION['error_message'])) {
                echo "<div class='error-message'>{$_SESSION['error_message']}</div>";
                unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
            }
            ?>
            <form action="users/login_process.php" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700 font-medium">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Username"
                        required
                    >
                </div>
                <div>
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Password"
                        required
                    >
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-medium-green text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                >
                    Masuk
                </button>
                <div class="text-center mt-4">
                    <a href="#" 
                    onclick="alert('Hubungi admin melalui email: admin@uin-suka.ac.id');" 
                    class="text-medium-green hover:underline">
                        Forgot Password?
                    </a>
                </div>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>

    <!-- Particles.js Configuration -->
    <script>
        particlesJS('particles-js', {
            particles: {
                number: { value: 100, density: { enable: true, value_area: 800 } },
                color: { value: "#235347" },
                shape: {
                    type: "circle",
                    stroke: { width: 0, color: "#000000" },
                    polygon: { nb_sides: 5 }
                },
                opacity: { value: 0.4, random: false },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: "#235347", opacity: 0.4, width: 1 },
                move: {
                    enable: true,
                    speed: 6,
                    direction: "none",
                    random: false,
                    straight: false,
                    out_mode: "out",
                    bounce: false,
                    attract: { enable: false, rotateX: 600, rotateY: 1200 }
                }
            },
        });
    </script>
</body>
</html>
