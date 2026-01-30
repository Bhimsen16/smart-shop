// ===== Simple Search + Sorting + Cart =====

// Search bar
function performSearch() {
  let query = document.getElementById("searchInput").value.toLowerCase();
  let cards = document.querySelectorAll(".product-card");

  cards.forEach((card) => {
    let text = card.innerText.toLowerCase();
    card.style.display = text.includes(query) ? "block" : "none";
  });
}

// Price sort
function sortProducts() {
  let value = document.getElementById("sortSelect").value;
  let grid = document.querySelector(".product-grid");
  let cards = [...document.querySelectorAll(".product-card")];

  cards.sort((a, b) => {
    let priceA = parseInt(
      a.querySelector(".price").innerText.replace(/[^0-9]/g, ""),
    );
    let priceB = parseInt(
      b.querySelector(".price").innerText.replace(/[^0-9]/g, ""),
    );

    if (value === "asc") return priceA - priceB;
    if (value === "desc") return priceB - priceA;
    return 0;
  });

  cards.forEach((card) => grid.appendChild(card));
}

// Add product to cart AJAX
function addToCart(product_id) {
  fetch("../user/add_to_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `product_id=${product_id}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        // update cart badge count
        document.querySelector(".cart-badge").innerText = data.cart_count;

        // show success notification
        showNotification("Product added to cart!");
      } else {
        showNotification("Something went wrong!");
      }
    });
}

// Function to update quantity (+/-) or remove product
function updateCart(cartId, action) {
  const qtyInput = document.getElementById("qty-" + cartId);
  let quantity = parseInt(qtyInput.value);

  fetch("../user/update_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      cart_id: cartId,
      action: action,
      quantity: quantity,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        // Update qty
        if (!data.remove) {
          qtyInput.value = data.new_quantity;
        }

        // Remove row if quantity reaches 0
        if (data.remove === true) {
          const row = document.getElementById("cart-row-" + cartId);
          if (row) row.remove();
        }

        // Update cart badge
        updateCartBadge(data.cart_count);

        // Update subtotal
        if (data.subtotal !== undefined) {
          const subtotalEl = document.getElementById("subtotal-" + cartId);
          if (subtotalEl) subtotalEl.innerText = "Rs. " + data.subtotal;
        }

        // Update total
        if (data.total !== undefined) {
          const totalEl = document.getElementById("cart-total");
          if (totalEl) totalEl.innerText = "Total: Rs. " + data.total;
        }
      } else {
        showNotification("Failed to update cart!");
      }
    })
    .catch((err) => console.error(err));
}

// Function to update the cart badge in navbar
function updateCartBadge(count) {
  const badge = document.querySelector(".cart-badge");
  if (badge) {
    badge.textContent = count;
    badge.classList.add("cart-bounce");
    setTimeout(() => badge.classList.remove("cart-bounce"), 300);
  }
}

//Remove products from cart
function removeFromCart(cartId) {
  fetch("../user/update_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ cart_id: cartId, action: "remove" }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        // Remove row
        const row = document.getElementById("cart-row-" + cartId);
        if (row) row.remove();

        // Update badge
        updateCartBadge(data.cart_count);

        // Update total
        const totalEl = document.getElementById("cart-total");
        if (totalEl) totalEl.textContent = "Rs. " + data.total;

        // 🔥 KEY LINE
        updateCartUI(data.cart_count);

        showNotification("Item removed!");
      }
    })
    .catch((err) => console.error(err));
}

function updateCartUI(cartCount) {
  const emptyCart = document.getElementById("emptyCart");
  const orderSummary = document.getElementById("orderSummary");

  if (!emptyCart || !orderSummary) return;

  if (cartCount === 0) {
    emptyCart.style.display = "block";
    orderSummary.style.display = "none";
  } else {
    emptyCart.style.display = "none";
    orderSummary.style.display = "block";
  }
}

// Simple notification popup
function showNotification(message) {
  const note = document.getElementById("notification");
  note.innerText = message;
  note.classList.add("show");

  setTimeout(() => {
    note.classList.remove("show");
  }, 2500);
}

function showRegister() {
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("registerForm").style.display = "block";
}

function showLogin() {
  document.getElementById("registerForm").style.display = "none";
  document.getElementById("loginForm").style.display = "block";
}

function toggleAuthBox() {
  const authBox = document.getElementById("authBox");
  if (!authBox) return;

  authBox.classList.toggle("show");
}

function toggleUserMenu() {
  document.getElementById("userMenu")?.classList.toggle("show");
}

document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("sidebarToggle");
  const layout = document.querySelector(".admin-layout");

  if (toggleBtn && layout) {
    toggleBtn.addEventListener("click", function () {
      layout.classList.toggle("collapsed");
    });
  }
});

const csvInput = document.getElementById("csvFile");
const fileName = document.getElementById("fileName");

csvInput.addEventListener("change", () => {
  fileName.textContent = csvInput.files.length
    ? csvInput.files[0].name
    : "No file selected";
});

//Product Images
const thumbnails = document.querySelectorAll(".thumb");
const mainImage = document.getElementById("mainImage");
const leftArrow = document.querySelector(".img-arrow.left");
const rightArrow = document.querySelector(".img-arrow.right");

let currentIndex = 0;

function updateImage(index) {
  currentIndex = index;
  mainImage.src = thumbnails[index].src;

  thumbnails.forEach((t) => t.classList.remove("active"));
  thumbnails[index].classList.add("active");
}

// thumbnail click
thumbnails.forEach((thumb) => {
  thumb.addEventListener("click", () => {
    updateImage(parseInt(thumb.dataset.index));
  });
});

// arrows
leftArrow.addEventListener("click", () => {
  let next = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
  updateImage(next);
});

rightArrow.addEventListener("click", () => {
  let next = (currentIndex + 1) % thumbnails.length;
  updateImage(next);
});
