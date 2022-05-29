//vispirms atradīsim formu ar document.getElementById() un iestatīsim mainīgajā form.
const form=document.getElementById('upload_form'); //document.QuerySelector('#upload_form')

//kad submitojam formu, tad izpildīsies funkcija
form.onsubmit = function(event) {
    event.preventDefault();

    //no xhttp.js faila metode postForm: function (form, callback = false) {}
    xhttp.postForm(this, function (response) {
        console.log(response);
    })
}