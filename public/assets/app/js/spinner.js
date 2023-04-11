class SpinningDots extends HTMLElement {
  constructor() {
    super();
    const width = 28;
    const circleRadius = 2;
    const root = this.attachShadow({ mode: "open" });
    // let node = document.createElement("div");
    // node.innerHTML = `<span>Open : Salut mon gars</span>`;
    // node.style.position = "fixed";
    // node.id = "spinner-loader";
    // node.style.top = `${window.innerHeight / 2 - 10}px`;
    // node.style.left = `${window.innerWidth / 2 - 50}px`;
    // node.style.zIndex = "30";
    // root.appendChild(node);
    // document.querySelector("main").style.opacity = "0.1";
    // node.style.opacity = "1";
  }
}

try {
  customElements.define("spinning-dots", SpinningDots);
} catch (error) {
  if (error instanceof DOMException) {
    console.log(error);
  } else {
    throw error;
  }
}

// const SPINNER = function () {
//   alert("Bonjour");
// };

// export default SPINNER;
