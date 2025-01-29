document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const languageSelect = document.getElementById("language");
    const selectedFlag = document.getElementById("selectedFlag");

    // Update flag when language changes
    if (languageSelect && selectedFlag) {
        languageSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const flagUrl = selectedOption.getAttribute('data-flag');
            selectedFlag.src = flagUrl;

            // Update the URL with the new language
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("lang", this.value);
            window.location.search = urlParams.toString();
        });
    }

    // Gestion responsive pour mobile
    function handleResize() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add("collapsed");
        }
    }
    window.addEventListener("resize", handleResize);
    handleResize();

    // Gestion du menu déroulant des usines
    const toggleUsines = document.querySelector(".usines-toggle");
    const dropdownMenus = document.querySelectorAll(".dropdown");
    if (toggleUsines) {
        toggleUsines.addEventListener("click", () => {
            dropdownMenus.forEach(menu => menu.classList.toggle("visible"));
        });
    }
});