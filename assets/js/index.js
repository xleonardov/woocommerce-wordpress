(function () {
  const init = () => {
    const domElements = {
      openFloatingCart: document.querySelector(".open-floating-cart"),
      closeFloatingCart: document.querySelector(".close-floating-cart"),
      addTocartButton: document.querySelectorAll(".single_add_to_cart_button"),
      floatingCart: document.querySelector(".floating-cart"),
      hamburger: document.querySelector(".hamburger"),
      megaMenu: document.querySelector(".mega-menu"),
      closeMegaMenu: document.querySelector(".close-mega-menu"),
      navigationBox: document.getElementById("navigation-box"),
      megaMenuGoBack: document.getElementById("mega-menu-go-back"),
      megaMenuContainer: document.querySelector(".mega-menu-nav"),
      categoryFilterLinks: document.querySelectorAll(".category-filter-link"),
      megaMenuGoBack: document.getElementById("mega-menu-go-back"),
      decreaseQuantity: document.querySelector(".decrease_quantity"),
      increaseQuantity: document.querySelector(".increase_quantity"),
      sortProducts: document.getElementById("sort_products"),
      filterCheckboxes: document.querySelectorAll(".filter-checkbox"),
      filterBoxes: document.querySelectorAll(".filters-box"),
      loadMoreProducts: document.querySelector("#load-more-products"),
      clearAllFilters: document.querySelector(".clear-all-filters"),
      clearFilters: document.querySelectorAll(".clear-filter"),
      openModalFilters: document.querySelector(".open-filters"),
      closeModalFilters: document.querySelector(".close-filters-container"),
      closeFiltersButton: document.querySelector(".close-filters-button"),
      searchBoxContainer: document.getElementById("search-box-container"),
      searchProductsInput: document.getElementById("search-products"),
      resultsBox: document.getElementById("search-results-box"),
      results: document.getElementById("products-list"),
      termsList: document.getElementById("search-terms-list"),
      searchHistory: document.getElementById("search-history"),
    };

    const disableScroll = () => {
      document.body.style.overflow = "hidden";
      document.body.style.paddingRight = "0.25rem";
    };
    const enableScroll = () => {
      document.body.style.overflow = "auto";
      document.body.style.paddingRight = "0rem";
    };

    const closeCart = () => {
      domElements.floatingCart.style.visibility = "hidden";
      domElements.floatingCart.children[0].style.opacity = "0";
      domElements.floatingCart.children[1].style.transform = "translateX(100%)";
      enableScroll();
      setTimeout(() => {
        domElements.floatingCart.children[1].replaceWith(
          domElements.floatingCart.children[1].cloneNode(true)
        );
      }, 200);
    };

    const removeItemFromCart = async (product_id) => {
      let data = {
        action: "woocommerce_ajax_remove_from_cart",
      };
      data.product_id = product_id;
      try {
        let res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
          credentials: "same-origin",
          method: "POST",
          body: new URLSearchParams({ ...data }),
        });

        const updatedCart = await res.json();

        let cartCounter = document.querySelector(".shop-cart-counter");
        let cartCounterUpdated = new DOMParser().parseFromString(
          updatedCart.fragments[".shop-cart-counter"],
          "text/xml"
        );
        cartCounter.innerHTML = cartCounterUpdated.childNodes[0].innerHTML;

        let cartContent = document.querySelector(".cart-container");
        let cartContentUpdated = new DOMParser().parseFromString(
          updatedCart.fragments[".cart-container"],
          "text/html"
        );

        cartContent.innerHTML =
          cartContentUpdated.children[0].children[1].children[0].innerHTML;
        setTimeout(() => {
          openCart();
        }, 100);
      } catch (error) {
        console.log(error);
      }
    };

    const modalRemoveItemFromCart = async (product_id) => {
      let data = {
        action: "woocommerce_ajax_remove_from_cart_confirmation",
      };
      data.product_id = product_id;

      try {
        const res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
          method: "POST",
          mode: "cors",
          body: new URLSearchParams({ ...data }),
        });
        const response = await res.text();
        domElements.floatingCart.children[1].style.transform =
          "translateX(100%)";
        let modalConfirmation = new DOMParser().parseFromString(
          response,
          "text/html"
        ).children[0].children[1].children[0];

        let body = domElements.floatingCart;
        modalConfirmation.children[1].children[0].children[0].addEventListener(
          "click",
          (e) => {
            e.preventDefault();
            body.removeChild(modalConfirmation);
            closeCart();
          }
        );
        modalConfirmation.children[1].children[2].children[0].addEventListener(
          "click",
          (e) => {
            e.preventDefault();
            body.removeChild(modalConfirmation);
            closeCart();
          }
        );
        modalConfirmation.children[1].children[2].children[1].addEventListener(
          "click",
          (e) => {
            e.preventDefault();
            removeItemFromCart(product_id);
            body.removeChild(modalConfirmation);
            closeCart();
          }
        );
        domElements.floatingCart.children[1].style.transform =
          "translateX(100%)";
        body.appendChild(modalConfirmation);

        setTimeout(() => {
          modalConfirmation.children[1].style.opacity = 1;
          modalConfirmation.children[1].style.transform = "translateY(0px)";
          modalConfirmation.children[0].addEventListener("click", (e) => {
            body.removeChild(modalConfirmation);
            closeCart();
          });
        }, 50);
      } catch (error) {
        console.log(error);
      }
    };

    const openCart = () => {
      domElements.floatingCart.style.visibility = "visible";
      domElements.floatingCart.children[0].style.opacity = "1";
      domElements.floatingCart.children[0].addEventListener("click", () => {
        closeCart();
      });
      domElements.floatingCart.children[1].style.transform = "translateX(0px)";
      let closeCartBtn = document.querySelector(".close-floating-cart");
      closeCartBtn.addEventListener("click", () => {
        closeCart(domElements.floatingCart);
      });

      let allRemoveItems = document.querySelectorAll(".remove_item_from_cart");

      allRemoveItems.forEach((it) => {
        it.addEventListener("click", async (e) => {
          e.preventDefault();
          await modalRemoveItemFromCart(it.dataset.product_id);
        });
      });
      disableScroll();
    };

    domElements.openFloatingCart.addEventListener("click", () => {
      openCart();
    });

    if (domElements.addTocartButton) {
      domElements.addTocartButton.forEach((addToCart) => {
        addToCart.addEventListener("click", async function (evnt) {
          evnt.preventDefault();
          let data = {
            action: "woocommerce_ajax_add_to_cart",
          };
          data.quantity = addToCart.parentNode.querySelector(
            "input[name='quantity']"
          )?.value
            ? addToCart.parentNode.querySelector("input[name='quantity']").value
            : 1;
          data.product_id = addToCart.value
            ? addToCart.value
            : addToCart.parentNode.querySelector("input[name='product_id']")
                .value;
          data.variation_id = addToCart.parentNode.querySelector(
            "input[name='variation_id']"
          )?.value
            ? addToCart.parentNode.querySelector("input[name='variation_id']")
                .value
            : null;

          try {
            let res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
              credentials: "same-origin",
              method: "POST",
              body: new URLSearchParams({ ...data }),
            });
            const updatedCart = await res.json();
            let cartCounter = document.querySelector(".shop-cart-counter");
            let cartCounterUpdated = new DOMParser().parseFromString(
              updatedCart.fragments[".shop-cart-counter"],
              "text/xml"
            );
            cartCounter.innerHTML = cartCounterUpdated.childNodes[0].innerHTML;
            let cartContent = document.querySelector(".cart-container");
            let cartContentUpdated = new DOMParser().parseFromString(
              updatedCart.fragments[".cart-container"],
              "text/html"
            );
            cartContent.innerHTML =
              cartContentUpdated.children[0].children[1].children[0].innerHTML;
            setTimeout(() => {
              openCart();
              addToCart.parentNode.querySelector(
                "input[name='quantity']"
              ).value = 1;
            }, 100);
            if (data.variation_id) {
              let currentSelected =
                addToCart.parentNode.querySelector(".current_selected");
              currentSelected.innerHTML = "define";
              addToCart.parentNode.querySelector(
                "input[name='variation_id']"
              ).value = "";
              addToCart.classList.add("disabled");
              addToCart.classList.add("wc-variation-selection-needed");
            }
          } catch (error) {
            console.log(error);
          }
        });
      });
    }

    //////////////////////////////// MEGA MENU ////////////////////////////////

    const closeMenu = () => {
      domElements.megaMenu.style.visibility = "hidden";
      domElements.megaMenu.children[0].style.opacity = 0;
      domElements.megaMenu.children[1].style.transform = "translateX(-100%)";
      enableScroll();
      setTimeout(() => {
        domElements.navigationBox.classList.remove("showNav");
        resetMenu();
      }, 300);
    };

    if (domElements.hamburger) {
      domElements.hamburger.addEventListener("click", () => {
        domElements.megaMenu.style.visibility = "visible";
        domElements.megaMenu.children[0].style.opacity = 1;
        domElements.megaMenu.children[1].style.transform = "translateX(0px)";
        domElements.megaMenu.children[0].addEventListener("click", () => {
          closeMenu();
        });
        disableScroll();
      });
    }

    if (domElements.closeMegaMenu) {
      domElements.closeMegaMenu.addEventListener("click", () => {
        closeMenu();
      });
    }

    if (domElements.categoryFilterLinks) {
      domElements.categoryFilterLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
          const menuToOpen = document.getElementById(link.dataset.openmenu);
          if (menuToOpen) {
            domElements.navigationBox.classList.remove("showNav");
            domElements.navigationBox.style.opacity = 0;
            domElements.navigationBox.style.visibility = "hidden";
            menuToOpen.style.opacity = 1;
            menuToOpen.style.visibility = "visible";
            menuToOpen.children[0].style.display = "block";
            menuToOpen.children[0].classList.add("showSubNav");
            domElements.megaMenuGoBack.style.opacity = 1;
            // navLogo.style.transform = "translateX(10px)";
            domElements.megaMenuContainer.style.transform = "translateX(10px)";
          }
        });
      });
    }

    const resetMenu = () => {
      const subMenus = document.querySelectorAll(".second-menu");
      if (subMenus) {
        subMenus.forEach((e) => {
          e.style.visibility = "hidden";
          e.style.opacity = 0;
          domElements.megaMenuGoBack.style.opacity = 0;
          domElements.navigationBox.style.opacity = 1;
          domElements.navigationBox.style.visibility = "visible";
          e.children[0].style.display = "none";
          e.children[0].classList.remove("showSubNav");
          // navLogo.style.transform = "translateX(0px)";
          domElements.megaMenuContainer.style.transform = "translateX(0px)";
        });
      }
    };

    if (domElements.megaMenuGoBack) {
      domElements.megaMenuGoBack.addEventListener("click", (e) => {
        domElements.navigationBox.classList.add("showNav");
        resetMenu();
      });
    }
  };

  const newsletterForm = document.getElementById("subscribe_newsletter");
  if (newsletterForm) {
    newsletterForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      console.log("vai");
      const status = document.getElementById("newsletter_status");
      let dados = [...newsletterForm.getElementsByTagName("input")];

      let dataToSend = {
        base: {
          consent: "any",
          status: "active",
        },
      };
      let extra = [
        {
          field_id: 38,
          value: "Bizclick",
        },
      ];

      dados.forEach((input) => {
        if (input.type === "text" || input.type === "email") {
          let value = input.value;
          let name = input.name;
          let dataInput = input.getAttribute("data-input-id");

          if (!dataInput) {
            dataToSend.base[name] = value;
          } else {
            extra.push({
              field_id: dataInput,
              value: value,
            });
          }
        } else if (input.type === "date") {
          let value = dataFormatada(input.value);
          let name = input.name;
          let dataInput = input.getAttribute("data-input-id");

          if (!dataInput) {
            dataToSend.base[name] = value;
          } else {
            extra.push({
              field_id: dataInput,
              value: value,
            });
          }
        }
      });
      dataToSend.extra = extra;

      let data = {
        action: "subscribe_to_egoi_newsletter",
        data: JSON.stringify(dataToSend),
      };
      try {
        const res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
          credentials: "same-origin",
          method: "POST",
          body: new URLSearchParams({ ...data }),
        });

        let response = await res.json();

        if (response === 201) {
          status.style.color = "#22c55e";
          status.innerHTML = "Subscrição efectuada com sucesso.";
          status.style.maxHeight = "200px";

          setTimeout(() => {
            status.style.maxHeight = "0px";
          }, 5000);
        } else if (response === 409) {
          status.style.color = "#E54235";
          status.innerHTML =
            "O seu email já se encontra na nossa lista. Subscreva a newsletter com outro email.";
          status.style.maxHeight = "200px";

          setTimeout(() => {
            status.style.maxHeight = "0px";
          }, 5000);
        } else {
          status.style.color = "#E54235";
          status.innerHTML = "Erro a adicionar o email a nossa lista!";
          status.style.maxHeight = "200px";

          setTimeout(() => {
            status.style.maxHeight = "0px";
          }, 5000);
        }
      } catch (error) {
        console.log(error);
        status.style.color = "#E54235";
        status.innerHTML = error;
        status.style.maxHeight = "200px";

        setTimeout(() => {
          status.style.maxHeight = "0px";
        }, 5000);
      }
    });
  }

  init();
})();
