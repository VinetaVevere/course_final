// ===== Skripti galeriju attēlošanai

document.getElementById('Work').onclick = function () {
    this.style.backgroundColor = "red";
    document.getElementById('Private').style.backgroundColor ="transparent";
    document.getElementsByClassName('gallery_block_private')[0].style.display = "none";
    document.getElementsByClassName('gallery_block_work')[0].style.display = "block";
   
  }
  document.getElementById('Private').onclick = function () {
    this.style.backgroundColor = "blue";
    document.getElementById('Work').style.backgroundColor = "transparent";
    const professional = document.getElementsByClassName('gallery_block_work');
    professional[0].style.display = "none";
    document.getElementsByClassName('gallery_block_private')[0].style.display = "block";

  }

// Skripts, lai apskatītu pieprasījumus
// šeit taisām pieprasījumu uz get api
    // xhttp.get('api.php?author=Vineta', function (response) { 
    // console.log(response.message);
    // console.log("api.php?author"); //Izvada Vineta
// });

// Skripti Comments iesniegšanai

// Pārtvert notikumu uz formu. Piemēram sākumā izvadīt kaut ko consolē, kad submito formu.
const form = document.getElementById('comments_form'); ////nodefinēts formas mainīgais. Vispirms atlasām to formu. Kas radīta Index.html -->  <form action="" id="comments_form">. piešķiram mainīgajam
const comment_block = document.querySelector('.comments'); // nodefinēts comment_block mainīgais. Saturēs to elementu, kurā tika ievietoti komentāri
const comment_template = comment_block.querySelector('.template'); //nodefinēts komentāru template mainīgais. komentāru template izmantosim, lai ievietotu jaunu saturu

//Uz get metodi padodam url un callback. Notiek pieprasījums uz šo te adresi: api.php?name=get-comments. Un ar function (response) dabūjam atbildi.
//Response vajadzētu saturēt datus no db.php funkcijas getAll(), bet ierakstītus iekš 'comments' no api.php ('comments' => $db->getAll)
//metode izpildās lapai pārlādējoties
xhttp.get('api.php?name=get-comments', function (response) {
  console.log('xhttp.get response'); 
  console.log(response);
  console.log('xhttp.get response.comments'); 
  console.log(response.comments);
  //Izlaist cauri addComment darbību. Ejam cauri masīvam response.comments. Katru reizi jauns ieraksts būs mainīgajā comment.
  for (let comment of response.comments) {
  //varam izsaukt funkciju addComment
    addComment(comment.id, comment.author, comment.email, comment.phone, comment.message);
    console.log('comment or response.comments'); 
    console.log(comment); 
    console.log('comment.id'); 
    console.log(comment.id)
  }
})

form.onsubmit = function (event) { //Kad forma submitota

    /** izpildās javascripts, kas pārtver submita noklusējuma darbību */
    event.preventDefault(); 

    /** Var izmantot xhttp objektu un šim objektam bija metode postForm, uz kuru padosies forma un callback funkcija/metode, kas izpildīsies (xhttps.js)
    * this šajā kontekstā ir pati forma. Komentāra pievienošana, kad ir dabūta atbilde
    * Lai funkcija function (response) {} izpildītos, šajā atbildē - responsā, ir jābūt statuss "true" 
      Atbildē sagaidīsim tos pašus datus, ko nosūtījām. Iespējams, ka atbildē dati būs nedaudz formatēti. Servera pusē pārveidoti, piemēram, noņemtas liekās atstarpes.*/
    xhttp.postForm(this, function (response) { 

      /** šeit jau atbildes datus varam izmantot*/
      addComment(response.id, response.author, response.email, response.phone, response.message); //response.id šeit pievienots, kad realizēta dzēšana
      console.log('addComment response.id');  
      console.log(response.id);
    });

    /** Vienkāršais alternatīvais variants, kad ņēmām datus no formas nevis jau no responsa. Nav aktuāls, bet viegli saprast
     * 
    /** Jāievāc formas dati, varam dabūt visas formas datus vienā rāvienā. Jāpadod pati forma - FormData(this). this būs tā pati forma*/
    /** atrodam visus datus no formas. tie, kas adrešu joslā
    * const data = new FormData(this); 

    /** Izpildam addComment medoti, uz kuru padodam autoru,.. un messagi. Input lauki name.
    * addComment(data.get('author'), data.get('email'), data.get('phone'), data.get('message'));*/
};

/** Izvadīt. Uz addComment metodi padodas 4 vērtības. Lai pievienotu, vajadzēs id)*/
function addComment(id, author, email, phone, message) { 
  // console.log(author, email, phone, message, message);
  /**
   * Iekš šīs funkcijas tiks izveidots new_comment, kurš tiks paņemts no komentāru template - const comment_template 
   * Ja pirms tam veidojām elementu ar Document.createElement(), tad piešķīrām atribūtus, iekš viņa vajadzēja atsevišķus elementus....
   * Tad šobrīd ejam citu ceļu -  sākumā sagatavojam template. index.html failā varam mainīt html, paši pievienot atribūtus
   * nodublējam elementu, uztaisām kopiju. Jāpadod true, lai nokopējas ne tikai pats elements, bet arī viss viņa iekšējais saturs. Mums ir div elements, arī lai p tags tiktu nokopēts
   */

  /** Izveidojam jaunā komentāra objektu new_comment. Izveidojam kopiju un ierakstām iekš new_comment. Noklonējot sagatavoto template. The cloneNode() method creates a copy of a node, and returns the clone.*/
   const new_comment = comment_template.cloneNode(true);
   
   /** Lai būtu redzams!!! Jaunajam komentāram vajag noņemt klasi (jo klasei bija stils, ka nav redzams), jo viņš nokopējās un arī parādījās klases template.*/
   new_comment.classList.remove('template'); 
   
    new_comment.querySelector('.author').textContent = author;
    /** Iekš new_comment.querySelector('.message') Varam pievienot tekstu. */
    /** tajā div elementā (<div class="comments__entry" - bez template!!), atrodam elementu ar klasi message un pievienojam kā tekstu šo te ziņu (message) */
    new_comment.querySelector('.message').textContent = message; 
    new_comment.querySelector('.email').textContent = email;
    new_comment.querySelector('.phone').textContent = phone;
    //new_comment 
    new_comment.dataset.id = id;

    //No new_comment varam atlast to elementu.
    const delete_btn = new_comment.querySelector('.delete');
    console.log(delete_btn);

    // tikai pie klikšķa izpildīt darbību
    delete_btn.onclick = function (event) { 

    //data vajadzēja priekš xhttp.post
      const data = new FormData(); // ģenerējam data iekšpus onclick
    //ar data.set iekš data ar kaut kādu funkciju iestatīt id.
    //intuitīvāks, bet daudz garāk dabūt id. ceļš:  data.set('id', delete_btn.parentNode.parentNode.dataset.id);
      data.set('id', id);

      //tad data padots uz pieprasījumu
      xhttp.post('api.php?name=delete-comment', data, function (response) {
      // Noņemam no html tikai tad, ka uztaisīts pieprasījums un saņemta atbilde un šī funkcija izpildīsies tikai, tad ja atbildē statuss ir true
        new_comment.remove();
        console.log(response);
        console.log(data);
        console.log(new_comment);
      })
    }

     /** komentāru blokā ar append pievienosim new_comment */
    comment_block.append(new_comment)
}

  /**
   * Strādā:
   *  Klase 
   *  document.querySelector('.gallery_block_private').style.backgroundColor = "yellow";
   *  ID
   *  document.getElementById('Private').style.backgroundColor ="transparent";
   * 
   * Nestrādā atlase 
   * document.getElementsByClassName('gallery_block_private').style.display = "none";
   * with getElementsByClassName you are getting HTMLCollection so try document.getElementsByClassName('....')[0].style.display = 'none'
   * Strādā:
   * document.getElementsByClassName('gallery_block_private')[0].style.display = "none";
   */
  