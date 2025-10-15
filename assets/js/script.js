document.addEventListener("DOMContentLoaded", () => {
  const sidebarLinks = document.querySelectorAll(".sidebar-menu a");
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", () => {
      sidebarLinks.forEach((l) => l.classList.remove("active"));
      link.classList.add("active");
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editBtn");
  const editModal = new bootstrap.Modal(
    document.getElementById("editUserModal")
  );
  editButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      document.getElementById("edit-id").value = this.dataset.id;
      document.getElementById("edit-username").value = this.dataset.username;
      document.getElementById("edit-email").value = this.dataset.email;
      document.getElementById("edit-role").value = this.dataset.role;
      editModal.show();
    });
  });
});

const inpAdd = document.querySelector('#modalTambah input[name="gambar"]');
const previewAdd = document.getElementById("previewAdd");
if (inpAdd) {
  inpAdd.addEventListener("change", (e) => {
    const f = e.target.files[0];
    if (!f) {
      previewAdd.style.display = "none";
      return;
    }
    previewAdd.src = URL.createObjectURL(f);
    previewAdd.style.display = "block";
  });
}

document.querySelectorAll(".editBtn").forEach((btn) => {
  btn.addEventListener("click", () => {
    document.getElementById("edit-id").value = btn.dataset.id;
    document.getElementById("edit-nama").value = btn.dataset.nama;
    document.getElementById("edit-harga").value = btn.dataset.harga;
    document.getElementById("edit-stok").value = btn.dataset.stok;
    document.getElementById("edit-deskripsi").value = btn.dataset.deskripsi;
    document.getElementById("edit-kategori").value = btn.dataset.kategori;
    const previewEdit = document.getElementById("previewEdit");
    if (previewEdit) {
      if (btn.dataset.gambar) {
        previewEdit.src = "../assets/gambar/produk/" + btn.dataset.gambar;
        previewEdit.style.display = "block";
      } else {
        previewEdit.style.display = "none";
      }
    }
  });
});

const inpEdit = document.querySelector('#modalEdit input[name="gambar"]');
const previewEdit = document.getElementById("previewEdit");
if (inpEdit) {
  inpEdit.addEventListener("change", (e) => {
    const f = e.target.files[0];
    if (!f) {
      return;
    }
    previewEdit.src = URL.createObjectURL(f);
    previewEdit.style.display = "block";
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  const nav = document.getElementById("mainNav");
  const hero = document.getElementById("hero");

  function updateNavbar() {
    const heroBottom = hero.getBoundingClientRect().bottom;
    if (heroBottom <= window.innerHeight * 0.15) {
      nav.classList.add("navbar-scrolled");
      nav.classList.remove("navbar-transparent");
    } else {
      nav.classList.add("navbar-transparent");
      nav.classList.remove("navbar-scrolled");
    }
  }

  updateNavbar();
  window.addEventListener("scroll", updateNavbar);
  window.addEventListener("resize", updateNavbar);

  const navLinks = document.querySelectorAll(".nav-link");
  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const targetId = link.getAttribute("href").replace("#", "");
      const target = document.getElementById(targetId);
      if (target) {
        const offset = 72;
        const top =
          target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top, behavior: "smooth" });
      }

      navLinks.forEach((l) => l.classList.remove("active"));
      link.classList.add("active");

      const bsCollapse = document.querySelector(".navbar-collapse");
      if (bsCollapse && bsCollapse.classList.contains("show")) {
        new bootstrap.Collapse(bsCollapse).hide();
      }
    });
  });

  const sections = document.querySelectorAll("section, header");
  const sectionObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute("id");
          if (!id) return;
          navLinks.forEach((l) => {
            l.classList.toggle("active", l.getAttribute("href") === `#${id}`);
          });
        }
      });
    },
    { root: null, threshold: 0.45 }
  );

  sections.forEach((s) => sectionObserver.observe(s));

  const revealEls = document.querySelectorAll(".reveal, [data-reveal]");
  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
          obs.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.18 }
  );

  revealEls.forEach((el) => observer.observe(el));

  const categoryBtns = document.querySelectorAll(".category-btn");
  const productItems = document.querySelectorAll(".product-item");

  categoryBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      categoryBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      const cat = btn.getAttribute("data-category");
      productItems.forEach((item) => {
        if (cat === "all" || item.getAttribute("data-category") === cat) {
          item.style.display = "";
        } else {
          item.style.display = "none";
        }
      });
    });
  });

  const modal = document.getElementById("modalProduct");
  const carouselInner = document.getElementById("carouselInner");
  const modalTitle = document.getElementById("modalTitle");
  const productDesc = document.getElementById("productDesc");

  document.querySelectorAll("[data-product]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const data = JSON.parse(btn.getAttribute("data-product"));

      modalTitle.textContent = data.title || "Detail Produk";
      carouselInner.innerHTML = "";
      data.items.forEach((it, i) => {
        const div = document.createElement("div");
        div.className = `carousel-item${i === 0 ? " active" : ""}`;
        div.innerHTML = `<img src="${it.img}" class="d-block w-100" alt="slide-${i}">`;
        div.dataset.desc = it.desc || "";
        carouselInner.appendChild(div);
      });

      productDesc.textContent = data.items[0].desc || "";
      const carouselEl = document.getElementById("productCarousel");
      const bsCarousel =
        bootstrap.Carousel.getInstance(carouselEl) ||
        new bootstrap.Carousel(carouselEl, { ride: false });
      bsCarousel.to(0);
    });
  });

  const productCarouselEl = document.getElementById("productCarousel");
  if (productCarouselEl) {
    productCarouselEl.addEventListener("slid.bs.carousel", function (e) {
      const active = productCarouselEl.querySelector(".carousel-item.active");
      if (active) productDesc.textContent = active.dataset.desc || "";
    });
  }

  const gallery =
    document.getElementById("galleryScroll") ||
    document.getElementById("galleryScroll");
  const galleryWrap = document.querySelector(".gallery-scroll");
  if (galleryWrap) {
    let isDown = false,
      startX,
      scrollLeft;
    galleryWrap.addEventListener("mousedown", (e) => {
      isDown = true;
      galleryWrap.classList.add("dragging");
      startX = e.pageX - galleryWrap.offsetLeft;
      scrollLeft = galleryWrap.scrollLeft;
    });
    galleryWrap.addEventListener("mouseleave", () => {
      isDown = false;
      galleryWrap.classList.remove("dragging");
    });
    galleryWrap.addEventListener("mouseup", () => {
      isDown = false;
      galleryWrap.classList.remove("dragging");
    });
    galleryWrap.addEventListener("mousemove", (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - galleryWrap.offsetLeft;
      const walk = (x - startX) * 1.2;
      galleryWrap.scrollLeft = scrollLeft - walk;
    });

    let touchStartX = 0,
      touchStartScroll = 0;
    galleryWrap.addEventListener(
      "touchstart",
      (e) => {
        touchStartX = e.touches[0].pageX;
        touchStartScroll = galleryWrap.scrollLeft;
      },
      { passive: true }
    );
    galleryWrap.addEventListener(
      "touchmove",
      (e) => {
        const x = e.touches[0].pageX;
        const walk = touchStartX - x;
        galleryWrap.scrollLeft = touchStartScroll + walk;
      },
      { passive: true }
    );
  }

  const WHATSAPP_NUMBER = "6285233929574";
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const name = document.getElementById("name").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const msg = document.getElementById("message").value.trim();
      const text = `Halo Bloomify, saya ${encodeURIComponent(
        name
      )}. ${encodeURIComponent(msg)} ${
        phone ? " Kontak: " + encodeURIComponent(phone) : ""
      }`;
      const url = `https://wa.me/${WHATSAPP_NUMBER}?text=${text}`;
      window.open(url, "_blank");
    });
  }

  const modalEl = document.getElementById("modalProduct");
  if (modalEl) {
    modalEl.addEventListener("hidden.bs.modal", () => {
      carouselInner.innerHTML = "";
      productDesc.textContent = "";
      modalTitle.textContent = "Detail Produk";
    });
  }
});

document.getElementById("year").textContent = new Date().getFullYear();

// const editButtons = document.querySelectorAll(".editBtn");
// editButtons.forEach((btn) => {
//   btn.addEventListener("click", () => {
//     document.getElementById("edit-id").value = btn.dataset.id;
//     document.getElementById("edit-nama").value = btn.dataset.nama;
//     document.getElementById("edit-harga").value = btn.dataset.harga;
//     document.getElementById("edit-stok").value = btn.dataset.stok;
//     document.getElementById("edit-deskripsi").value = btn.dataset.deskripsi;
//     document.getElementById("edit-kategori").value = btn.dataset.kategori;
//     const modal = new bootstrap.Modal(
//       document.getElementById("editProdukModal")
//     );
//     modal.show();
//   });
// });
