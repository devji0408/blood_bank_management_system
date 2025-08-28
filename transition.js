document.addEventListener("DOMContentLoaded", function () {
    // Apply effect before page unload
    document.querySelectorAll("a").forEach(link => {
        if (link.getAttribute("href") && link.getAttribute("href") !== "#") {
            link.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent instant navigation
                let url = this.getAttribute("href");

                document.body.style.transition = "all 0.8s ease";
                document.body.style.opacity = "0";
                document.body.style.transform = "scale(0.9)";

                setTimeout(() => {
                    window.location.href = url; // Redirect after effect
                }, 800);
            });
        }
    });

    // Smooth fade-in on page load
    document.body.style.opacity = "0";
    document.body.style.transform = "scale(1.1)";
    setTimeout(() => {
        document.body.style.transition = "all 0.8s ease";
        document.body.style.opacity = "1";
        document.body.style.transform = "scale(1)";
    }, 100);

    // Create Background Floating Shapes
    createFloatingShapes();
});

function createFloatingShapes() {
    const shapeContainer = document.createElement("div");
    shapeContainer.classList.add("floating-shapes");
    document.body.appendChild(shapeContainer);

    for (let i = 0; i < 15; i++) { // Number of shapes
        let shape = document.createElement("div");
        shape.classList.add("shape");
        shape.style.width = `${Math.random() * 50 + 20}px`;
        shape.style.height = shape.style.width;
        shape.style.top = `${Math.random() * 100}vh`;
        shape.style.left = `${Math.random() * 100}vw`;
        shape.style.animationDuration = `${Math.random() * 10 + 5}s`;
        shapeContainer.appendChild(shape);
    }
}
