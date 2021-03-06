const xhttp = {
    /**
     * @param {*} form
     * @param {function || false} callback() - function parameters (response_object, form)
     * 
     *  Šajā failā 2 metodes: get un post. Varēs ar šo metodi taisīt pieprasījumus uz serveri
     * 
     *  ===postForm
     *  Šajā funkcijā apvieno datu dabūšanu un post metodi
     *  postForm, jo submito visu formu. postForm nevajadzēja atsevišķi datus (data) sūtīt, jo mēs padevām formu, un tur bija visa vajadzīgā informācija iekšā
     *  Kad submito formu, tad formā pašā par sevi ir noteikta adrese, kur sūtīt datus. index.html failā <form action="api.php?name=add-task" id="todo_list_form" method="post">
     *  un pašā formā ir pieejami dati.
     *  form tiek padots no script.js faila, kurā izsauc šo metodi xhttp.postForm(this, function (response) {}, kur this ir elements - mūsu forma, kas tiek submitota
     *  ar ko šī postForm funkcija atšķīras no parastās post funkcijas? ar to, ka šī iekš sevis pati noteica url un data.
    */ 
    /** Datu dabūšana. šeit no formas nolasa adresi, kur notiek pieprasījums. Tas bija index failā: <form action="api.php?name=add-task" id="todo_list_form" method="post"> */
    postForm: function (form, callback = false) { 
        /** actions nodefinēts */
        let url = form.getAttribute('action'), 

            /**un pats pieprasījums notika. FormData() constructor creates a new FormData object. Syntax new FormData(form)*/
            data = new FormData(form); 

        /** post. tālāk notikumi nākošajā metodē post: (zemāk). šī metode atdot paramaterus, lai tur turpinātos*/
        this.post(url, data, callback); 

        /** Izmantojot jebkad šo funkciju (arī citur piemērojot), svarīgi, lai forma resetojas. Lai pazūd ieraksti esošajā formā
        * Kad notiek pieprasījums uz šo funkciju function (form, callback = false) {}, forma resetoja.   
        * padots form elements, tāpēc formu resetojam. */
        form.reset(); 
    },

/**
 * Mums būs post: funkcija arī tāda, kur varam paši padot url, data 
 * šeit parastā post metode. Tīri, kas nav saistīta ar formu.
 * post metodei vajadzēs DATUS, ko sūtīt. Tā ir atšķirība no get metodes.
 * uz šo metodi jāpado 3 vērtības url, data, callback.
 */

//Post
    post: function (url, data, callback = false) { //jāzina, protams, url, kur sūtīt. Tad callback - funkcija.
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (callback !== false) {

                /**  
                 * parse: json saturs tiek pārsvērsts uz javascript formātu. Ja this.responseText nav Json formātā, tad kļūda, jo mēģinām ne jsonu pārveidot uz JS formātu
                 * šeit tiek aprakstīta mainīgā response_object struktūra. Tā ir JSON.parse(this.responseText)
                 */
                let response_object = JSON.parse(this.responseText); //16.05 lekcijā bija atbilde. When receiving data from a web server, the data is always a string.Parse the data with JSON.parse(), and the data becomes a JavaScript object.
                
                /** Rinda, kas pārbauda to, kas ir atbildē. Ja ir klāt statuss */
                if (response_object.status == true) {

                /** Tad izpildām callback metodi. Un scrip.js failā ir nodefinēta callback metode
                * šeit pateikts, ka atbilde ir response_object 
                */
                     callback(response_object);
                }
            }
        };
        /**Specifies the type of request. open (method, url, async). method: the type of request: GET or POST*/
        xhttp.open("POST", url);
    
        /** Sends the request to the server (used for POST)*/
        xhttp.send(data); 
    },

//GET

    // Padodam funkciju uz otro mainīgo - callback. Tajā brīdī, kad funkcija izsaukta (zemāk) - callback (response_object); 
    // Callback (Atzvanīšana) ir funkcija, kas tiek nodota kā arguments citai funkcijai.
    get: function (url, callback = false) { 
        //Objekts XMLHttpRequest tiek izmantots datu apmaiņai ar serveri.
        const xhttp = new XMLHttpRequest(); //Visās mūsdienu pārlūkprogrammās ir iebūvēts XMLHttpRequest objekts, lai pieprasītu datus no servera.
      
        //XMLHttpRequest onload definē funkciju, kas jāizsauc, kad pieprasījums tiek saņemts (ielādēts)
        xhttp.onload = function() {
            if (callback !== false) {
                try {
                    //tiek parsots jsons
                    let response_object = JSON.parse(this.responseText); 

                    if (response_object.status == true) {

                        // funkcija, kas tika padota no script.js faila (function (response) {}*) šeit tiks izsaukta. 
                        // šajā brīdī, kad tā funkcija šeit tiek izsaukta - callback(response_object); Uz viņu tiks padots response_object. Tiks padots objekts, kuru varam izmantot, lai dabūtu kaut kādi informāciju
                        callback(response_object); 
                    }
                } catch (e) {  
                    console.log('invalid json');  
                }
            }
        };
        
        //Lai nosūtītu pieprasījumu serverim, mēs izmantojam XMLHttpRequest objekta open() un send() metodes:
        xhttp.open("GET", url); // Specifies the type of request. XMLHttpRequest metode open() inicializē jaunizveidotu pieprasījumu vai atkārtoti inicializē esošu pieprasījumu. open(method, url, async) 
        xhttp.send(); //Sends the request to the server (used for GET)
    }
};