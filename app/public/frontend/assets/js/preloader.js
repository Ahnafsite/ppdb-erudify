const preloader = () => {
    console.log("hello");
    const preloaderElemet = document.querySelector(".preloader");

    // Tambahkan log untuk memastikan elemen ditemukan
    if (preloaderElemet) {
        console.log("Preloader element found");
    } else {
        console.log("Preloader element not found");
    }

    setTimeout(() => {
        preloaderElemet.style = "opacity:0; visibility:hidden;";
        setTimeout(() => {
            preloaderElemet.style.display = "none";
        }, 400);
    }, 1000);
};
