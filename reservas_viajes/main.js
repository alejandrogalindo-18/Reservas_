
document.addEventListener("DOMContentLoaded", () => {
  const path = window.location.pathname;

  if (path.includes("index.html")) initLogin();
  else if (path.includes("registro.html")) initRegistro();
  else if (path.includes("panel.html")) initPanel();
});


function initLogin() {
  const form = document.getElementById("loginForm");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const correo = document.getElementById("correo").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();

    if (!correo || !contrasena) {
      alert("Por favor, completa todos los campos.");
      return;
    }

    try {
      const response = await fetch("../Controllers/LoginController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ correo, contrasena }),
      });

      const data = await response.json();

      if (data.success) {
        localStorage.setItem("user", JSON.stringify(data.user));
        window.location.href = "panel.html";
      } else {
        alert(data.message || "Credenciales incorrectas.");
      }
    } catch (error) {
      console.error("Error en login:", error);
      alert("Error al iniciar sesión.");
    }
  });
}


function initRegistro() {
  const form = document.getElementById("registerForm");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();
    const rol = document.getElementById("rol").value;

    if (!nombre || !correo || !contrasena) {
      alert("Completa todos los campos.");
      return;
    }

    try {
      const response = await fetch("../Controllers/RegistroController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nombre, correo, contrasena, rol }),
      });

      const data = await response.json();

      if (data.success) {
        alert("✅ Registro exitoso, ahora puedes iniciar sesión.");
        window.location.href = "index.html";
      } else {
        alert(data.message || "No se pudo registrar el usuario.");
      }
    } catch (error) {
      console.error("Error en registro:", error);
      alert("Error al registrar el usuario.");
    }
  });
}


function initPanel() {
  const userData = JSON.parse(localStorage.getItem("user"));
  const title = document.getElementById("panelTitle");
  const content = document.getElementById("panelContent");
  const logoutBtn = document.getElementById("logoutBtn");

  if (!userData) {
    window.location.href = "index.html";
    return;
  }

  // Cargar el panel según el rol
  title.textContent = `Bienvenido, ${userData.nombre}`;
  renderPanel(userData.rol, content);

  // Botón de cerrar sesión
  logoutBtn.addEventListener("click", () => {
    localStorage.removeItem("user");
    window.location.href = "index.html";
  });
}


function renderPanel(rol, container) {
  container.innerHTML = "";

  switch (rol) {
    case "admin":
      container.innerHTML = `
        <h3 class="text-xl font-semibold mb-3">Panel del Administrador</h3>
        <p class="text-gray-700 mb-3">Puedes gestionar todos los usuarios y reservas del sistema.</p>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Ver usuarios</button>
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg ml-2 hover:bg-green-700">Ver reservas</button>
      `;
      break;

    case "agente":
      container.innerHTML = `
        <h3 class="text-xl font-semibold mb-3">Panel del Agente</h3>
        <p class="text-gray-700 mb-3">Puedes registrar y administrar reservas de clientes.</p>
        <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Nueva reserva</button>
      `;
      break;

    default:
      container.innerHTML = `
        <h3 class="text-xl font-semibold mb-3">Panel del Cliente</h3>
        <p class="text-gray-700 mb-3">Bienvenido a tu zona de reservas. Aquí verás tus viajes próximos.</p>
        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Ver mis reservas</button>
      `;
      break;
  }
}
