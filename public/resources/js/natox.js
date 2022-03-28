document.getElementById('btn').addEventListener('click', loadText)

function loadText() {
    //create xhr object
    const xhr = new XMLHttpRequest()
        //open- type, url/file name, async
    xhr.open('GET', 'contact', true)
    console.log('READYSTATE', xhr.readyState)

    //optional for loaders
    xhr.onprogress = function() {
        console.log("Processing...", xhr.readyState);
    }

    xhr.onload = function() {
        console.log("READYSTATE", xhr.readyState);
        if (this.status == 200) {
            // console.log(this.responseText)
        }
    }
    xhr.onerror = function() {
            console.log('Request Error....')
        }
        // xhr.onreadystatechange = function() {
        //         console.log("READYSTATE", xhr.readyState);
        //         if (this.readyState == 4 && this.status == 200) {
        //             // console.log(this.responseText)
        //         }
        //     }
        //send request
    xhr.send()
}