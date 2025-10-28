function toggleSidebar() {
    const main = document.querySelector(".main");
    if (main) {
        main.classList.toggle("active");
        sessionStorage.setItem(
            "sidebar_state",
            main.classList.contains("active")
        );
    }
}

function expandMenu(element) {
    let arrowParent = element.parentElement.parentElement;
    arrowParent.classList.toggle("showMenu");
}
