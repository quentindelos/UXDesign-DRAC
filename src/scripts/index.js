function opentab(tabname) {
    const sensors = document.querySelector(".sensors");
    const graph = document.querySelector(".graph");
    const manager = document.querySelector(".manager");

    if (tabname === "sensors") {
        sensors.style.zIndex = "1";
        graph.style.zIndex = "0";
        manager.style.zIndex = "0";
    } else if (tabname === "graph") {
        sensors.style.zIndex = "0";
        graph.style.zIndex = "1";
        manager.style.zIndex = "0";
    } else if (tabname === "manager") {
        sensors.style.zIndex = "0";
        graph.style.zIndex = "0";
        manager.style.zIndex = "1";
    }
}
