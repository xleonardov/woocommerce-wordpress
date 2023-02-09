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
      clearAllFilters: document.querySelector(".clear-all-filters"),
      clearFilters: document.querySelectorAll(".clear-filter"),
      openModalFilters: document.querySelector(".open-filters"),
      closeModalFilters: document.querySelector(".close-filters-container"),
      closeFiltersButton: document.querySelector(".close-filters-button"),
    };

    const disableScroll = () => {
      document.body.style.overflow = "hidden";
    };
    const enableScroll = () => {
      document.body.style.overflow = "auto";
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
          "bg-white",
          "rounded-lg",
          "p-4",
          "max-w-sm",
          "animate-pulse",
          "h-[488px]"
        );
        productSkeleton.innerHTML =
          '<div class="flex justify-center items-center mb-2 h-[300px] bg-gray-300 rounded ">\
                <svg class="w-12 h-12 text-gray-200 " xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" viewBox="0 0 640 512"><path d="M480 80C480 35.82 515.8 0 560 0C604.2 0 640 35.82 640 80C640 124.2 604.2 160 560 160C515.8 160 480 124.2 480 80zM0 456.1C0 445.6 2.964 435.3 8.551 426.4L225.3 81.01C231.9 70.42 243.5 64 256 64C268.5 64 280.1 70.42 286.8 81.01L412.7 281.7L460.9 202.7C464.1 196.1 472.2 192 480 192C487.8 192 495 196.1 499.1 202.7L631.1 419.1C636.9 428.6 640 439.7 640 450.9C640 484.6 612.6 512 578.9 512H55.91C25.03 512 .0006 486.1 .0006 456.1L0 456.1z"/></svg>\
                  </div>\
                  <div class="grid gap-2 pb-4 grid-rows-[88px_72px]">\
                    <div>\
                      <div class="h-2.5 bg-gray-300 rounded-full mb-2"></div>\
                      <div class="h-2.5 bg-gray-300 rounded-full mb-2"></div>\
                      <div class="h-2.5 bg-gray-200 rounded-full mb-2"></div>\
                      <div class="h-2.5 bg-gray-200 rounded-full"></div>\
                    </div>\
                    <div>\
                      <div class="h-2 bg-gray-200 rounded-full w-full lg:w-32 mb-2"></div>\
                      <div class="flex space-x-2 items-center mb-2">\
                        <div class="w-full lg:w-4 h-4 bg-gray-200 rounded-full "></div>\
                        <div class="w-full lg:w-20 h-2 bg-gray-200 rounded-full "></div>\
                      </div>\
                      <div class="w-full lg:w-48 h-4 bg-gray-300 rounded-full "></div>\
                    </div>\
                  </div>\
                  <span class="sr-only">Loading...</span>';
        skeletonList.appendChild(productSkeleton);
      }
      return skeletonList;
    };

    const skeletonFiltersMount = (n) => {
      const skeletonList = document.createElement("div");
      skeletonList.classList.add("overflow-hidden");
      for (let i = 0; i < n; i++) {
        const filtersSkeleton = document.createElement("div");
        filtersSkeleton.classList.add("mb-4", "p-4", "animate-pulse");
        filtersSkeleton.innerHTML =
          '<div class="pb-2 border-b">\
          <div class="h-4 bg-gray-300 rounded-full w-48"></div>\
        </div>\
        <ul class="grid gap-1">\
          <li class="grid gap-4 grid-cols-[24px_auto] items-center mt-2">\
            <div class="rounded-md w-6 h-6 bg-gray-200"></div>\
            <div class="rounded-full w-48 h-2.5 bg-gray-200"></div>\
          </li>\
          <li class="grid gap-4 grid-cols-[24px_auto] items-center mt-2">\
            <div class="rounded-md w-6 h-6 bg-gray-200"></div>\
            <div class="rounded-full w-48 h-2.5 bg-gray-200"></div>\
          </li>\
          <li class="grid gap-4 grid-cols-[24px_auto] items-center mt-2">\
            <div class="rounded-md w-6 h-6 bg-gray-200"></div>\
            <div class="rounded-full w-48 h-2.5 bg-gray-200"></div>\
          </li>\
          <li class="grid gap-4 grid-cols-[24px_auto] items-center mt-2">\
            <div class="rounded-md w-6 h-6 bg-gray-200"></div>\
            <div class="rounded-full w-48 h-2.5 bg-gray-200"></div>\
          </li>\
          <li class="grid gap-4 grid-cols-[24px_auto] items-center mt-2">\
            <div class="rounded-md w-6 h-6 bg-gray-200"></div>\
            <div class="rounded-full w-48 h-2.5 bg-gray-200"></div>\
          </li>\
        </ul>\
        ';
        skeletonList.appendChild(filtersSkeleton);
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

    const filterProducts = async (checkbox) => {
      const productGrid = document.querySelector("#products_grid");
      productGrid.innerHTML = "";
      productGrid.appendChild(skeletonProductsMount(PER_PAGE));

      let queryParams = getQueryParams(window.location.href);
      if (queryParams.hasOwnProperty("orderby")) {
        queryParams["orderby"] = queryParams["orderby"];
      }
      let newQueryParams = {};

      for (let [key, value] of Object.entries(queryParams)) {
        let terms = value.split("_");
        newQueryParams = { ...newQueryParams, [key]: terms };
      }
      if (newQueryParams.hasOwnProperty("page")) {
        delete newQueryParams["page"];
      }

      let value = checkbox.value;
      let attribute = checkbox.dataset.attribute.replace("pa_", "");
      let taxonomy = checkbox.dataset.taxonomy;
      let newSearchString = "";
      if (checkbox.checked) {
        if (newQueryParams[attribute]) {
          if (!newQueryParams[attribute].includes(value)) {
            newQueryParams[attribute].push(value);
          }
        } else {
          newQueryParams[attribute] = [value];
        }
        newSearchString = searchString(newQueryParams);
      } else {
        if (newQueryParams[attribute]) {
          if (newQueryParams[attribute].includes(value)) {
            const index = newQueryParams[attribute].indexOf(value);
            newQueryParams[attribute].splice(index, 1);
            if (newQueryParams[attribute].length === 0) {
              delete newQueryParams[attribute];
            }
          }
        }
        newSearchString = searchString(newQueryParams);
      }

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
    };

    const reloadBoxes = (boxes) => {
      if (boxes) {
        boxes.forEach((box) => {
          if (box.children.length > 5) {
            const toggleItems = document.createElement("div");
            toggleItems.classList.add(
              "text-sm",
              "text-secondary",
              "cursor-pointer"
            );
            toggleItems.innerHTML = `Ver Mais (${box.children.length - 5})`;
            toggleItems.addEventListener("click", (e) => {
              if (box.style.maxHeight === "99999px") {
                box.style.maxHeight = "160px";
                toggleItems.innerHTML = `Ver Mais (${box.children.length - 5})`;
              } else {
                box.style.maxHeight = "99999px";
                toggleItems.innerHTML = `Ver Menos (${
                  box.children.length - 5
                })`;
              }
            });
            box.parentElement.appendChild(toggleItems);
          }
        });
      }
    };

    reloadBoxes(domElements.filterBoxes);

    const clearAllFilters = async (element) => {
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
      } catch (error) {
        console.log(error);
      }
    };

    const clearFilter = async (element) => {
      const productGrid = document.querySelector("#products_grid");
      productGrid.innerHTML = "";
      productGrid.appendChild(skeletonProductsMount(PER_PAGE));

      let queryParams = getQueryParams(window.location.href);
      if (queryParams.hasOwnProperty("orderby")) {
        queryParams["orderby"] = queryParams["orderby"];
      }
      let newQueryParams = {};

      for (let [key, value] of Object.entries(queryParams)) {
        let terms = value.split("_");
        newQueryParams = { ...newQueryParams, [key]: terms };
      }
      if (newQueryParams.hasOwnProperty("page")) {
        delete newQueryParams["page"];
      }

      let value = element.dataset.value;
      let attribute = element.dataset.attribute.replace("pa_", "");
      let taxonomy = element.dataset.taxonomy;
      let newSearchString = "";
      if (newQueryParams[attribute]) {
        if (newQueryParams[attribute].includes(value)) {
          const index = newQueryParams[attribute].indexOf(value);
          newQueryParams[attribute].splice(index, 1);
          if (newQueryParams[attribute].length === 0) {
            delete newQueryParams[attribute];
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
    };

    const mountFilters = async (e) => {
      const filtersBox = document.getElementById("filters-box");
      filtersBox.innerHTML = "";
      filtersBox.appendChild(skeletonFiltersMount(14));

      let data = {
        action: "woocommerce_ajax_rerender_filters",
        taxonomy: e.target.dataset.taxonomy,
        searchParams: window.location.search,
      };

      try {
        const res = await fetch(`${woocommerce_scritps_helper.ajaxurl}`, {
          mode: "cors",
          method: "POST",
          body: new URLSearchParams({ ...data }),
        });
        const mountedFilters = await res.text();

        let filtersResponse = new DOMParser().parseFromString(
          mountedFilters,
          "text/html"
        ).children[0].children[1];
        filtersBox.innerHTML = filtersResponse.innerHTML;
        let clearAllFiltersBtn = filtersBox.querySelector(".clear-all-filters");
        if (clearAllFiltersBtn) {
          clearAllFiltersBtn.addEventListener("click", (e) => {
            clearAllFilters(e.target);
            mountFilters(e);
          });
        }
        let clearFiltersBtns = filtersBox.querySelectorAll(".clear-filter");
        if (clearFiltersBtns) {
          clearFiltersBtns.forEach((cl) => {
            cl.addEventListener("click", (e) => {
              clearFilter(cl);
              mountFilters(e);
            });
          });
        }

        for (let chkbx of filtersBox.children[0].children) {
          chkbx
            .querySelectorAll("input[name='sidefilter']")
            .forEach((input) => {
              input.addEventListener("change", async (e) => {
                e.preventDefault();
                filterProducts(e.target);
                mountFilters(e);
              });
            });
        }
        reloadBoxes(filtersBox.children[0].querySelectorAll(".filters-box"));
      } catch (error) {
        console.log(error);
      }
    };

    if (domElements.sortProducts) {
      domElements.sortProducts.addEventListener("change", async (e) => {
        e.preventDefault();
        const productGrid = document.querySelector("#products_grid");
        productGrid.innerHTML = "";
        productGrid.appendChild(skeletonProductsMount(PER_PAGE));

        let queryParams = getQueryParams(window.location.href);
        if (queryParams.hasOwnProperty("orderby")) {
          queryParams["orderby"] = e.target.value;
        } else {
          queryParams["orderby"] = e.target.value;
        }
        let newQueryParams = {};

        for (let [key, value] of Object.entries(queryParams)) {
          let terms = value.split("_");
          newQueryParams = { ...newQueryParams, [key]: terms };
        }
        let newSearchString = searchString(newQueryParams);

        let newurl = `${window.location.protocol}//${window.location.host}${window.location.pathname}${newSearchString}`;
        window.history.pushState({ path: newurl }, "", newurl);

        let data = {
          action: "woocommerce_ajax_filter_products",
          taxonomy: e.target.dataset.taxonomy,
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

    if (domElements.filterCheckboxes) {
      domElements.filterCheckboxes.forEach((checkbox) => {
        //filter products
        checkbox.addEventListener("change", async (e) => {
          e.preventDefault();
          filterProducts(checkbox);
          mountFilters(e);
        });
      });
    }

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
      domElements.clearAllFilters.addEventListener("click", (e) => {
        clearAllFilters(e.target);
        mountFilters(e);
      });
    }

    if (domElements.clearFilters) {
      domElements.clearFilters.forEach((activeFilter) => {
        activeFilter.addEventListener("click", async (e) => {
          clearFilter(activeFilter);
          mountFilters(e);
        });
      });
    }

    if (domElements.openModalFilters) {
      domElements.openModalFilters.addEventListener("click", (e) => {
        let filtersContainer = document.querySelector(".filters-container");
        filtersContainer.style.transform = "translateX(0px)";
        disableScroll();
      });
    }

    if (domElements.closeModalFilters) {
      domElements.closeModalFilters.addEventListener("click", (e) => {
        let filtersContainer = document.querySelector(".filters-container");
        filtersContainer.style.transform = "translateX(-100%)";
        enableScroll();
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
