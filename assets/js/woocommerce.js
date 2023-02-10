(function () {
  const init = () => {
    const PER_PAGE = 16;
    const domElements = {
      decreaseQuantity: document.querySelectorAll(".cart .decrease_quantity"),
      increaseQuantity: document.querySelectorAll(".cart .increase_quantity"),
      sortProducts: document.getElementById("sort_products"),
      filterCheckboxes: document.querySelectorAll(".filter-checkbox"),
      filterBoxes: document.querySelectorAll(".filters-box"),
      loadMoreProducts: document.querySelector("#load-more-products"),
      clearAllFilters: document.querySelectorAll(".clear-all-filters"),
      clearFilters: document.querySelectorAll(".clear-filter"),
      openModalFilters: document.querySelector(".open-filters"),
      closeModalFilters: document.querySelector(".close-filters-container"),
      closeFiltersButton: document.querySelector(".close-filters-button"),
      toggleFilters: document.querySelectorAll(".toggle-filter"),
      filtersForm: document.querySelector("#filters-form"),
      openFiltersDrawer: document.querySelector(".open-filters-drawer"),
      filtersDrawer: document.querySelector(".filters-drawer"),
    };

    const disableScroll = () => {
      document.body.style.overflow = "hidden";
      document.body.style.paddingRight = "0.25rem";
    };
    const enableScroll = () => {
      document.body.style.overflow = "auto";
      document.body.style.paddingRight = "0rem";
    };

    ////////////////////////////////// Products //////////////////////////////////

    if (domElements.increaseQuantity) {
      domElements.increaseQuantity.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          const input = btn.parentElement.querySelector(
            "input.qty[type='number']"
          );
          input.value = parseInt(input.value) + 1;
          let updateCart = document.querySelector("[name='update_cart']");
          if (updateCart) {
            updateCart.disabled = false;
            updateCart.click();
          }
        });
      });
    }

    if (domElements.decreaseQuantity) {
      domElements.decreaseQuantity.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          const input = btn.parentElement.querySelector(
            "input.qty[type='number']"
          );
          if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
          }
          let updateCart = document.querySelector("[name='update_cart']");
          if (updateCart) {
            updateCart.disabled = false;
            updateCart.click();
          }
        });
      });
    }

    const skeletonProductsMount = (n) => {
      const skeletonList = document.createElement("ul");
      skeletonList.classList.add(
        "grid",
        "grid-cols-2",
        "lg:grid-cols-4",
        "gap-4",
        "mb-4"
      );

      for (let i = 0; i < n; i++) {
        const productSkeleton = document.createElement("li");
        productSkeleton.classList.add(
          "col-span-1",
          "border-b",
          "px-0",
          "pb-4",
          "md:pb-8",
          "relative"
        );
        productSkeleton.innerHTML = `<div class="col-span-1 px-0 pb-4 md:pb-8">
          <div class="relative w-full animate-pulse">
            <div class="aspect-productImg bg-gray-100 flex items-center justify-center relative"></div>
            <div class="mt-4 flex justify-center flex-col items-center">
              <div class="mb-4 h-2.5 w-32 rounded-full bg-gray-100"></div>
              <div class="mb-4 h-2.5 w-8 rounded-full bg-gray-100"></div>
            </div>
          </div>
        </div>`;
        skeletonList.appendChild(productSkeleton);
      }
      return skeletonList;
    };

    const searchString = (queryParams) => {
      let newSearchString = "";
      if (Object.entries(queryParams).length > 0) {
        newSearchString += "?";
      }
      let i = 1;
      for (let [key, value] of Object.entries(queryParams)) {
        if (i === 1) {
          newSearchString += `${key}=`;
        } else {
          newSearchString += `&${key}=`;
        }
        if (value.length === 1) {
          newSearchString += `${value}`;
        } else {
          let j = 1;
          value.forEach((it) => {
            if (j < value.length) {
              newSearchString += `${it}_`;
            } else {
              newSearchString += `${it}`;
            }
            j++;
          });
        }
        i++;
      }

      return newSearchString;
    };

    function getQueryParams(url) {
      // const paramArr = url.slice(url.indexOf('?') + 1).split('&');
      const parsedUrl = url.split("?");
      const paramArr = parsedUrl.length > 1 ? parsedUrl[1].split("&") : [];
      const params = {};
      paramArr.map((param) => {
        const [key, val] = param.split("=");
        params[key] = decodeURIComponent(val);
      });
      return params;
    }

    const registerLoadMore = async (element) => {
      const productGrid = document.querySelector("#products_grid");
      let taxonomy = element.dataset.taxonomy;
      let newSearchString = "";
      let queryParams = getQueryParams(window.location.href);
      if (queryParams.hasOwnProperty("orderby")) {
        queryParams["orderby"] = queryParams["orderby"];
      }
      if (queryParams.hasOwnProperty("page")) {
        queryParams["page"] = `${parseInt(queryParams["page"]) + 1}`;
      } else {
        queryParams["page"] = `2`;
      }
      let newQueryParams = {};

      for (let [key, value] of Object.entries(queryParams)) {
        let terms = value.split("_");
        newQueryParams = { ...newQueryParams, [key]: terms };
      }
      newSearchString = searchString(newQueryParams);

      let newurl = `${window.location.protocol}//${window.location.host}${window.location.pathname}${newSearchString}`;
      window.history.pushState({ path: newurl }, "", newurl);

      let data = {
        action: "woocommerce_ajax_filter_products",
        taxonomy: taxonomy,
        searchParams: window.location.search,
      };

      try {
        const res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
          mode: "cors",
          method: "POST",
          body: new URLSearchParams({ ...data }),
        });
        const filteredProducts = await res.text();

        let productGridResponse = new DOMParser().parseFromString(
          filteredProducts,
          "text/html"
        ).children[0].children[1];
        productGridResponse.removeChild(productGridResponse.children[0]);
        console.log(productGridResponse.children[0].children.length);

        Array.from(productGridResponse.children[0].children).forEach(function (
          element
        ) {
          productGrid.children[0].appendChild(element);
        });

        let count_results = document.querySelector("#count_results");
        if (
          parseInt(queryParams["page"]) * PER_PAGE >
          parseInt(count_results.innerHTML)
        ) {
          element.style.display = "none";
        }
      } catch (error) {
        console.log(error);
      }
    };

    const openFiltersDrawer = () => {
      domElements.filtersDrawer.style.visibility = "visible";
      domElements.filtersDrawer.children[0].style.opacity = "1";
      domElements.filtersDrawer.children[0].addEventListener("click", () => {
        closeFiltersDrawer();
      });
      domElements.filtersDrawer.children[1].style.transform = "translateX(0px)";
      let closeFilterDrawerBtn = document.querySelector(
        ".close-filters-drawer"
      );
      closeFilterDrawerBtn.addEventListener("click", () => {
        closeFiltersDrawer(domElements.filtersDrawer);
      });
      disableScroll();
    };

    const closeFiltersDrawer = () => {
      domElements.filtersDrawer.style.visibility = "hidden";
      domElements.filtersDrawer.children[0].style.opacity = "0";
      domElements.filtersDrawer.children[1].style.transform =
        "translateX(100%)";
      enableScroll();
      // setTimeout(() => {
      //   domElements.filtersDrawer.children[1].replaceWith(
      //     domElements.filtersDrawer.children[1].cloneNode(true)
      //   );
      // }, 200);
    };

    if (domElements.openFiltersDrawer) {
      domElements.openFiltersDrawer.addEventListener("click", () => {
        openFiltersDrawer();
      });
    }

    if (domElements.toggleFilters) {
      domElements.toggleFilters.forEach((filter) => {
        filter.addEventListener("click", () => {
          filter.classList.toggle("btn-quad-selected");
          filter.previousElementSibling.checked =
            !filter.previousElementSibling.checked;
        });
      });
    }

    if (domElements.filtersForm) {
      domElements.filtersForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        closeFiltersDrawer();
        const productGrid = document.querySelector("#products_grid");
        productGrid.innerHTML = "";
        productGrid.appendChild(skeletonProductsMount(PER_PAGE));
        let newSearchString = "";
        const formData = new FormData(domElements.filtersForm);
        let queryParams = getQueryParams(window.location.href);
        if (queryParams.hasOwnProperty("orderby")) {
          queryParams["orderby"] = queryParams["orderby"];
        }
        let newQueryParams = {};
        let taxonomy = "";
        for (var [key, value] of formData.entries()) {
          if (key === "taxonomy") {
            taxonomy = value;
          } else {
            if (newQueryParams.hasOwnProperty(key)) {
              if (!newQueryParams[key].includes(value)) {
                newQueryParams[key].push(value);
              }
            } else {
              newQueryParams[key] = [value];
            }
          }
        }
        newSearchString = searchString(newQueryParams);

        let newurl = `${window.location.protocol}//${window.location.host}${window.location.pathname}${newSearchString}`;
        window.history.pushState({ path: newurl }, "", newurl);

        let data = {
          action: "woocommerce_ajax_filter_products",
          taxonomy: taxonomy,
          searchParams: window.location.search,
        };

        try {
          const res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
            mode: "cors",
            method: "POST",
            body: new URLSearchParams({ ...data }),
          });
          const filteredProducts = await res.text();
          let productGridResponse = new DOMParser().parseFromString(
            filteredProducts,
            "text/html"
          ).children[0].children[1];
          let count = productGridResponse.querySelector("#count");
          let count_results = document.querySelector("#count_results");
          count_results.innerHTML = count.innerHTML;
          productGridResponse.removeChild(productGridResponse.children[0]);
          productGrid.innerHTML = productGridResponse.innerHTML;
          let loadMore = productGrid.querySelector("#load-more-products");
          if (loadMore) {
            loadMore.addEventListener("click", async (e) => {
              loadMore.innerHTML =
                '<div role="status">\
                  <svg class="inline w-4 h-4 text-white animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">\
                      <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>\
                      <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>\
                  </svg>\
                  <span class="sr-only">Loading...</span>\
              </div>';
              await registerLoadMore(e.target);
              loadMore.innerHTML = "Carregar mais produtos";
            });
          }
        } catch (error) {
          console.log(error);
        }
      });
    }

    const clearAllFilters = async (element) => {
      closeFiltersDrawer();
      const productGrid = document.querySelector("#products_grid");
      let taxonomy = element.dataset.taxonomy;
      productGrid.innerHTML = "";
      productGrid.appendChild(skeletonProductsMount(PER_PAGE));

      let newurl = `${window.location.protocol}//${window.location.host}${window.location.pathname}`;
      window.history.pushState({ path: newurl }, "", newurl);

      let data = {
        action: "woocommerce_ajax_filter_products",
        taxonomy: taxonomy,
        searchParams: window.location.search,
      };
      try {
        const res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
          mode: "cors",
          method: "POST",
          body: new URLSearchParams({ ...data }),
        });
        const filteredProducts = await res.text();
        let productGridResponse = new DOMParser().parseFromString(
          filteredProducts,
          "text/html"
        ).children[0].children[1];
        let count = productGridResponse.querySelector("#count");
        let count_results = document.querySelector("#count_results");
        count_results.innerHTML = count.innerHTML;
        productGridResponse.removeChild(productGridResponse.children[0]);
        productGrid.innerHTML = productGridResponse.innerHTML;
        let loadMore = productGrid.querySelector("#load-more-products");
        if (loadMore) {
          loadMore.addEventListener("click", async (e) => {
            loadMore.innerHTML =
              '<div role="status">\
                <svg class="inline w-4 h-4 text-white animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">\
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>\
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>\
                </svg>\
                <span class="sr-only">Loading...</span>\
            </div>';
            await registerLoadMore(e.target);
            loadMore.innerHTML = "Carregar mais produtos";
          });
        }
        const checkboxes = document.querySelectorAll(".f-checkbox");
        const radios = document.querySelectorAll(".f-radio");
        if (checkboxes) {
          checkboxes.forEach((c) => {
            c.checked = false;
            c.nextElementSibling.classList.remove("btn-quad-selected");
          });
        }
        if (radios) {
          radios.forEach((r) => {
            r.checked = false;
            r.nextElementSibling.classList.remove("btn-quad-selected");
          });
        }
      } catch (error) {
        console.log(error);
      }
    };

    if (domElements.loadMoreProducts) {
      domElements.loadMoreProducts.addEventListener("click", async (e) => {
        domElements.loadMoreProducts.innerHTML =
          '<div role="status">\
          <svg class="inline w-4 h-4 text-white animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">\
              <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>\
              <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>\
          </svg>\
          <span class="sr-only">Loading...</span>\
      </div>';
        await registerLoadMore(e.target);
        domElements.loadMoreProducts.innerHTML = "Carregar mais produtos";
      });
    }

    if (domElements.clearAllFilters) {
      domElements.clearAllFilters.forEach((cl) => {
        cl.addEventListener("click", () => {
          clearAllFilters(cl);
        });
      });
    }

    if (domElements.closeFiltersButton) {
      domElements.closeFiltersButton.addEventListener("click", (e) => {
        let filtersContainer = document.querySelector(".filters-container");
        filtersContainer.style.transform = "translateX(-100%)";
        enableScroll();
      });
    }
  };

  init();
})();

jQuery(document).ready(function ($) {
  var woocommerce_form = $(".woocommerce-cart form");
  woocommerce_form
    .on("change", ".qty", function () {
      form = $(this).closest("form");
      $(
        "<input type='hidden' name='update_cart' id='update_cart' value='1'>"
      ).appendTo(form);
      formData = form.serialize();
      $("input[name='update_cart']").val("A atualizar").prop("disabled", true);
      $("a.checkout-button.wc-forward")
        .addClass("opacity-50")
        .addClass("pointer-events-none")
        .html(
          '<div role="status">\
          <svg class="inline mr-2 w-8 h-8 text-white animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">\
              <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>\
              <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>\
          </svg>\
          <span class="sr-only">Loading...</span>\
      </div>'
        );

      // update cart via ajax
      $.post(form.attr("action"), formData, async function (resp) {
        var shop_table = $(".cart-content", resp).html();
        var cart_totals = $(".cart-collaterals .cart_totals", resp).html();
        $(".cart-content").html(shop_table);
        $(".woocommerce-cart .cart-collaterals .cart_totals").html(cart_totals);
        let data = {
          action: "woocommerce_ajax_update_cart_fragments_cart",
        };
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
      });
    })
    .on("click", ".decrease_quantity", function (btn) {
      btn.preventDefault();
      const input = btn.currentTarget.parentElement.querySelector(
        "input.qty[type='number']"
      );
      if (parseInt(input.value) > 1) {
        let current = parseInt(input.value) - 1;
        $(this).next(".qty").val(current).trigger("change");
      }
    })
    .on("click", ".increase_quantity", function (btn) {
      btn.preventDefault();
      const input = btn.currentTarget.parentElement.querySelector(
        "input.qty[type='number']"
      );
      let current = parseInt(input.value) + 1;
      $(this).prev(".qty").val(current).trigger("change");
    });

  $(".woocommerce-cart").on(
    "click",
    "a.checkout-button.wc-forward.disabled",
    function (e) {
      e.preventDefault();
    }
  );
});
