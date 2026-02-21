//Product Images
document.addEventListener("DOMContentLoaded", function () {
  const thumbnails = document.querySelectorAll(".thumb");
  const mainImage = document.getElementById("mainImage");
  const leftArrow = document.querySelector(".img-arrow.left");
  const rightArrow = document.querySelector(".img-arrow.right");

  if (!thumbnails.length || !mainImage) return;

  let currentIndex = 0;

  function updateImage(index) {
    currentIndex = index;
    mainImage.src = thumbnails[index].src;

    thumbnails.forEach((thumb) => thumb.classList.remove("active"));
    thumbnails[index].classList.add("active");
  }

  // thumbnail click
  thumbnails.forEach((thumb, index) => {
    thumb.addEventListener("click", () => {
      updateImage(index);
    });
  });

  // arrows
  if (leftArrow) {
    leftArrow.addEventListener("click", () => {
      let next = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
      updateImage(next);
    });
  }

  if (rightArrow) {
    rightArrow.addEventListener("click", () => {
      let next = (currentIndex + 1) % thumbnails.length;
      updateImage(next);
    });
  }
});