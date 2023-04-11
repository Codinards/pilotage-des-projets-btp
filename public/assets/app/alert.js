/**
 *
 * @param {HTMLElement} HTMLElement
 */
function CustomAlert(HTMLElement = null) {
  let type;
  let element = HTMLElement || document.querySelector("body");

  this.alert = function (message, title, type = "info") {
    this.type = type;

    let dialogboxDiv = document.createElement("div");
    dialogboxDiv.id = "dialogbox";
    dialogboxDiv.class = "slit-in-vertical";
    dialogboxDiv.innerHTML =
      '<div><div id="dialogboxhead"></div><div id="dialogboxbody"></div><div id="dialogboxfoot"></div>';

    element.appendChild(dialogboxDiv);

    let dialogbox = document.getElementById("dialogbox");

    switch (type) {
      case "danger":
        dialogbox.style.backgroundColor = "#dc3545";
        break;
      case "success":
        dialogbox.style.backgroundColor = "#28a745";
        break;
      case "warning":
        dialogbox.style.backgroundColor = "#ffc107";
        break;
      default:
        dialogbox.style.backgroundColor = "#17a2b8";
    }
    if (element.nodeName.toLowerCase() == "body".toLowerCase()) {
      dialogbox.style.top = "100px";
    }

    dialogbox.style.display = "block";

    document.getElementById("dialogboxhead").style.display = "block";
    dialogbox.childNodes.forEach((elt) => {
      elt.style.backgroundColor = dialogbox.style.backgroundColor;
    });

    if (typeof title === "undefined") {
      document.getElementById("dialogboxhead").style.display = "none";
      document.getElementById("dialogboxhead").style.backgroundColor =
        dialogbox.style.backgroundColor;
    } else {
      document.getElementById("dialogboxhead").innerHTML =
        '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> ' + title;
    }
    document.getElementById("dialogboxhead").style.backgroundColor =
      dialogbox.style.backgroundColor;
    document.getElementById("dialogboxbody").innerHTML = message;
    document.getElementById("dialogboxbody").style.backgroundColor =
      dialogbox.style.backgroundColor;
    document.getElementById("dialogboxfoot").style.backgroundColor =
      dialogbox.style.backgroundColor;

    let btnStyle;
    switch (this.type) {
      case "danger":
        btnStyle = "#dc3545";
        break;
      case "warning":
        btnStyle = "#ffc107";
        break;
      case "success":
        btnStyle = "#28a745";
        break;
      default:
        btnStyle = "#17a2b8";
    }

    document.getElementById("dialogboxfoot").innerHTML = `
    <button class="pure-material-button-contained active" style='background-color: ${btnStyle};' onclick="(function(){document.body.removeChild(document.getElementById('dialogbox'));/*document.body.removeChild(document.getElementById('dialogoverlay'));*/})()">OK</button>
    `;
    //
  };
  this.ok = function () {
    document.removeChild(document.getElementById("dialogbox"));
    document.getElementById("dialogbox").style.display = "none";
  };
}

function Alert(message, title, type = "info", element = null) {
  return CustomAlert(element).alert(message, title, type);
}

function success(message, title, element = null) {
  return Alert(message, title, "success", element);
}

function warning(message, title, element = null) {
  return Alert(message, title, "warning", element);
}

function info(message, title, element = null) {
  return Alert(message, title, "info", element);
}

function danger(message, title, element = null) {
  return Alert(message, title, "danger", element);
}
