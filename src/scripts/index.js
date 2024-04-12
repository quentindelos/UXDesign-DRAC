function opentab(tabname) {
    const sensors = document.querySelector(".sensors");
    const graph = document.querySelector(".graph");

    if (tabname === "sensors") {
        sensors.style.zIndex = "1";
        graph.style.zIndex = "0";
    } else if (tabname === "graph") {
        graph.style.zIndex = "1";
        sensors.style.zIndex = "0";
    }
}