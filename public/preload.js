const { ipcMain } = require("electron");
const ipr = require("electron").ipcRenderer;
console.log("Salut");
const printPDF = document.querySelector("#print-pdf");

printPDF.addEventListener("click", (event) => {
  // ipr.send("print-to-pdf");
  alert("oui");
});

ipcMain.on("wrote-pdf", (event, path) => {
  alert("Wrote pdf to : ".path);
});
