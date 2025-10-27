// Manejo simple de usuarios y reservas con localStorage (puedes conectar a PHP/MySQL luego)
document.addEventListener("DOMContentLoaded", () => {
  const formLogin = document.querySelector("#formLogin");
  const formRegister = document.querySelector("#formRegister");
  const formReserva = document.querySelector("#formReserva");

  // Registro de usuarios
  if (formRegister) {
    formRegister.addEventListener("submit", e => {
      e.preventDefault();
      const nombre = document.querySelector("#nombre").value;
      const email = document.querySelector("#email").value;
      const password = document.querySelector("#password").value;
      const rol = document.querySelector("#rol").value;

      let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
      usuarios.push({ nombre, email, password, rol });
      localStorage.setItem("usuarios", JSON.stringify(usuarios));
      alert("✅ Registro exitoso como " + rol);
      window.location.href = "login.html";
    });
  }

  // Login
  if (formLogin) {
    formLogin.addEventListener("submit", e => {
      e.preventDefault();
      const email = document.querySelector("#email").value;
      const password = document.querySelector("#password").value;
      const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
      const user = usuarios.find(u => u.email === email && u.password === password);

      if (user) {
        localStorage.setItem("usuarioActual", JSON.stringify(user));
        alert(`Bienvenido ${user.nombre} (${user.rol})`);
        if (user.rol === "Administrador") window.location.href = "admin.html";
        else window.location.href = "reservas.html";
      } else {
        alert("❌ Credenciales incorrectas");
      }
    });
  }

  // Reservas
  if (formReserva) {
    formReserva.addEventListener("submit", e => {
      e.preventDefault();
      const destino = document.querySelector("#destino").value;
      const fecha = document.querySelector("#fecha").value;
      const user = JSON.parse(localStorage.getItem("usuarioActual"));

      let reservas = JSON.parse(localStorage.getItem("reservas")) || [];
      reservas.push({ usuario: user.email, destino, fecha });
      localStorage.setItem("reservas", JSON.stringify(reservas));

      alert("✈️ Reserva realizada correctamente para " + destino);
      formReserva.reset();
    });
  }
});
