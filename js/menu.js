
const logoItem = document.querySelector("#logo");
const menu = document.querySelector(".menu-container");

logoItem.addEventListener("click", () => {
    menu.classList.toggle("show");
    console.log("show")

})