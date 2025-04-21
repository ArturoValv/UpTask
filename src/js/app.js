const mobileMenuBtn = document.querySelector("#mobile-menu");
const cerrarMenuBtn = document.querySelector("#cerrar-menu");
const sidebar = document.querySelector(".sidebar");

if (mobileMenuBtn) {
  mobileMenuBtn.addEventListener("click", () => {
    sidebar.classList.add("mostrar");
  });
}

if (cerrarMenuBtn) {
  cerrarMenuBtn.addEventListener("click", () => {
    sidebar.classList.remove("mostrar");
  });
}

window.addEventListener("resize", () => {
  if (window.screen.width > 768) sidebar.classList.remove("mostrar");
});
