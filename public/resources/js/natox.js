document.getElementById('btn').addEventListener('click', loadText)
const view = document.getElementById('view')

function postRequest(url, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    var loader = document.createElement("div");
    loader.className = "loader";
    document.body.appendChild(loader);
    xhr.addEventListener("readystatechange", function() {
        if (xhr.readyState === 4) {
            if (callback) {
                callback(xhr.response);
            }
            loader.remove();
        }
    });

    var formdata = data ?
        data instanceof FormData ?
        data :
        new FormData(document.querySelector(data)) :
        new FormData();

    var csrfMetaTag = document.querySelector('meta[name="csrf_token"]');
    if (csrfMetaTag) {
        formdata.append("csrf_token", csrfMetaTag.getAttribute("content"));
    }

    xhr.send(formdata);
}


function getRequest(url) {
    const xhr = new XMLHttpRequest();

    xhr.open("GET", url, true);

    xhr.onprogress = function() {
        alert("Processing....");
    };

    xhr.onload = function() {
        if (this.status == 200) {
            JSON.parse(this.responseText)
        }
    };
    xhr.onerror = function() {
        console.log("Request Error....");
    };

    xhr.send();
}

function loadText() {
    getRequest('about')
}