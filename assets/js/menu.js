(function () {
  const init = () => {
    let has_sub_menu = document.querySelectorAll(".has_sub_menu");

    if (has_sub_menu) {
      has_sub_menu.forEach((sub_menu) => {
        sub_menu.addEventListener("click", (e) => {
          e.preventDefault();
          let target_id = sub_menu.getAttribute("data-submenu");
          let target_el = document.getElementById(target_id);
          let icon_fechado = sub_menu.querySelector(".is_close");
          let icon_aberto = sub_menu.querySelector(".is_open");
          if (icon_fechado.classList.contains("hidden")) {
            icon_fechado.classList.remove("hidden");
            icon_aberto.classList.add("hidden");
            target_el.style.height = "0px";
          } else {
            icon_fechado.classList.add("hidden");
            icon_aberto.classList.remove("hidden");
            target_el.style.height = target_el.scrollHeight + "px";
          }
        });
      });
    }
  };
  init();
})();
