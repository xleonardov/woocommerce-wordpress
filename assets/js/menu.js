(function () {
  const init = () => {
    let has_sub_menu = document.querySelectorAll(".has_sub_menu");

    if (has_sub_menu) {
      has_sub_menu.forEach((sub_menu) => {
        sub_menu.addEventListener("click", (e) => {
          e.preventDefault();
          let target_id = sub_menu.getAttribute("data-submenu");
          let target_el = document.getElementById(target_id);
          let target_childs = target_el.children.length;
          let height =
            target_childs *
            target_el.children[0].getBoundingClientRect().height;
          if (target_el.getBoundingClientRect().height !== 0)
            target_el.style.height = "0px";
          else target_el.style.height = `${height}px`;
        });
      });
    }
  };
  init();
})();
