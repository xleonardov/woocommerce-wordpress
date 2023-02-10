(function () {
  const init = () => {
    let btn_acordeao = document.querySelectorAll(".btn-acordeao");
    if (btn_acordeao) {
      btn_acordeao.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          let target_id = btn.getAttribute("data-target");
          let target_el = document.getElementById(target_id);
          let icon_fechado = btn.querySelector(".is_close");
          let icon_aberto = btn.querySelector(".is_open");
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
