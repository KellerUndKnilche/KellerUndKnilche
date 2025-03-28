document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.getElementById("toggle-side-panels");
    const sidePanels = document.getElementById("side-panels");

    if (toggleButton && sidePanels) {
        toggleButton.addEventListener("click", () => {
            sidePanels.classList.toggle("active");
            const isActive = sidePanels.classList.contains("active");
            toggleButton.setAttribute("aria-expanded", isActive);
            // When panel is open, add a "below" class; otherwise, remove it.
            if (isActive) {
                toggleButton.classList.add("moved");
            } else {
                toggleButton.classList.remove("moved");
            }
        });
    }
});
