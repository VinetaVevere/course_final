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

//Definējam mainīgos
const form = document.getElementById('comments_form'); ////nodefinēts formas mainīgais. Vispirms atlasām to formu. Kas radīta Index.html -->  <form action="" id="comments_form">. piešķiram mainīgajam
const popup = document.querySelector('.popup');
//document_getElementById - jāmeklē no visa dokumenta nevis no popup popup_getElementById)
const form_update = document.getElementById('comments_update_form'); //nodefinēts update formas mainīgais. Index failā: id="comments_update_form"
const comment_block = document.querySelector('.comments'); // nodefinēts comment_block mainīgais. Saturēs to elementu, kurā tika ievietoti komentāri
const comment_template = comment_block.querySelector('.template'); //nodefinēts komentāru template mainīgais. komentāru template izmantosim, lai ievietotu jaunu saturu

//Uz get metodi padodam url un callback. Notiek pieprasījums uz šo te adresi: api.php?name=get-comments. Un ar function (response) dabūjam atbildi.
//Response vajadzētu saturēt datus no db.php funkcijas getAll(), bet ierakstītus iekš 'comments' no api.php ('comments' => $db->getAll)
//metode izpildās lapai pārlādējoties

xhttp.get('api.php?name=get-comments', function (response) {
    // console.log('xhttp.get response'); 
    // console.log(response);
    // console.log('xhttp.get response.comments'); 
    // console.log(response.comments);

    //Izlaist cauri addComment darbību. Ejam cauri masīvam response.comments. Katru reizi jauns ieraksts būs mainīgajā comment.
    //Pārtvert notikumu uz formu. Piemēram sākumā izvadīt kaut ko consolē, kad submito formu.
    for (let comment of response.comments) {
    //varam izsaukt funkciju addComment
      addComment(comment.id, comment.author, comment.email, comment.phone, comment.message);
      // console.log('comment or response.comments'); 
      // console.log(comment); 
      // console.log('comment.id'); 
      // console.log(comment.id)
    }
});

form.onsubmit = function (event) { //Kad forma submitota
    /** izpildās javascripts, kas pārtver submita noklusējuma darbību */
    event.preventDefault();
    submitForm(this);
};
    /** Vienkāršais alternatīvais variants, kad ņēmām datus no formas nevis jau no responsa. Nav aktuāls, bet viegli saprast
     * 
    /** Jāievāc formas dati, varam dabūt visas formas datus vienā rāvienā. Jāpadod pati forma - FormData(this). this būs tā pati forma*/
    /** atrodam visus datus no formas. tie, kas adrešu joslā
    * const data = new FormData(this); 

    /** Izpildam addComment medoti, uz kuru padodam autoru,.. un messagi. Input lauki name.
    * addComment(data.get('author'), data.get('email'), data.get('phone'), data.get('message'));*/

form_update.onsubmit = function (event) {
  /** izpildās javascripts, kas pārtver submita noklusējuma darbību - lapas pārlādēšanu. Lai lapa nepārlādējās */
  event.preventDefault(); 
    // varam nokopēt formu no submitForm (). Mainām uz form_update. Notiks pieprasījums, uz šo jauno form_update nevis uz form 
    xhttp.postForm(form_update, function (response) {
      // šeit tiek rakstīts kods, kas notiek javasscript un Html pusē, kad veikta formas updatošana
      // lai popupa logs ar visu formu pazūds. popup.style.display = 'none'
      popup.style.display = 'none'; //this.style.display = 'none';
      console.log("ŗesponse.id=", response.id);

      //Tālāk nodrošinā to, lai no db un api iegūtās vērtības attēlotos HTML bez lapas pārlādes.
      //atrodam elementu pēc id un piešķiram mainīgajam update_comment
      //Piemēram, Document.QuerySelector('[data-id ="23"]'). Skaitlis programmātiski jāieliek.Tā kā tas ir Javascript, tad jāsaliek caur vairākiem nogriežņiem.  
      //Savienošanu taisām nevis ar punktiem, bet ar plusiem
      const update_comment = document.querySelector('[data-id="' + response.id + '"]');

      //Iekš atlasītā elementa atrodam message un update laukus un to atribūtā text.Content (saturā) ieliekam vērtības, kas mums zināmas caur respones.comment.message author... 
      update_comment.querySelector('.message').textContent = response.comment.message;
      update_comment.querySelector('.email').textContent = response.comment.email;
      update_comment.querySelector('.phone').textContent = response.comment.phone;
      update_comment.querySelector('.author').textContent = response.comment.author;
  });
}

function submitForm (form) {
  /** Var izmantot xhttp objektu un šim objektam bija metode postForm, uz kuru padosies forma un callback funkcija/metode, kas izpildīsies (xhttps.js)
    * this šajā kontekstā ir pati forma. Komentāra pievienošana, kad ir dabūta atbilde
    * Lai funkcija function (response) {} izpildītos, šajā atbildē - responsā, ir jābūt statuss "true" 
      Atbildē sagaidīsim tos pašus datus, ko nosūtījām. Iespējams, ka atbildē dati būs nedaudz formatēti. Servera pusē pārveidoti, piemēram, noņemtas liekās atstarpes.*/
  xhttp.postForm(form, function (response) {
    /** šeit jau atbildes datus varam izmantot*/
    addComment(response.id, response.author, response.email, response.phone, response.message);
    // console.log('addComment response.id'); 
  });
}

/** Izvadīt. Uz addComment metodi padodas 4 vērtības. Lai pievienotu, vajadzēs id)*/
function addComment(id, author, email, phone, message) { 
  // console.log(author, email, phone, message, message);
  /**
   * Iekš šīs funkcijas tiks izveidots new_comment, kurš tiks paņemts no komentāru template - const comment_template 
   * Ja pirms tam veidojām elementu ar Document.createElement(), tad piešķīrām atribūtus, iekš viņa vajadzēja atsevišķus elementus....
   * Tad šobrīd ejam citu ceļu -  sākumā sagatavojam template. index.html failā varam mainīt html, paši pievienot atribūtus u.c.
   * nodublējam elementu, uztaisām kopiju. Jāpadod true, lai nokopējas ne tikai pats elements, bet arī viss viņa iekšējais saturs. Mums ir div elements, arī lai p tags tiktu nokopēts
   */

  /** Izveidojam jaunā komentāra objektu new_comment. Izveidojam kopiju un ierakstām iekš new_comment. Noklonējot sagatavoto template. The cloneNode() method creates a copy of a node, and returns the clone.*/
  const new_comment = comment_template.cloneNode(true);
  console.log('New comment', new_comment);
   
   /** Lai būtu redzams!!! Jaunajam komentāram vajag noņemt klasi (jo klasei bija stils, ka nav redzams), jo viņš nokopējās un arī parādījās klases template.*/
   new_comment.classList.remove('template');
   
   //Tad iekš new_comment ievietojas author.... message. 
    new_comment.querySelector('.author').textContent = author;
    /** Iekš new_comment.querySelector('.message') Varam pievienot tekstu. */
    new_comment.querySelector('.email').textContent = email;
    new_comment.querySelector('.phone').textContent = phone;
    /** tajā div elementā (<div class="comments__entry" - bez template!!), atrodam elementu ar klasi message un pievienojam kā tekstu šo te ziņu (message) */ 
    new_comment.querySelector('.message').textContent = message;
    //new_comment 
    new_comment.dataset.id = id;


    //tikai pie klikšķa DELETE izpildīt darbību
    new_comment.querySelector('.delete').onclick = function (event) {
      //data vajadzēja priekš xhttp.post
        const data = new FormData();
        //ar data.set iekš data ar kaut kādu funkciju iestatīt id.
        //intuitīvāks, bet daudz garāk dabūt id. ceļš:  data.set('id', delete_btn.parentNode.parentNode.dataset.id);
        data.set('id', id);

        //tad data padots uz pieprasījumu
        xhttp.post('api.php?name=delete-comment', data, function (response) {
          // Noņemam no html tikai tad, ka uztaisīts pieprasījums un saņemta atbilde un šī funkcija izpildīsies tikai, tad ja atbildē statuss ir true
            new_comment.remove();
            // console.log(response);
            // console.log(data);
            // console.log(new_comment);
        });
     };

    //tikai pie klikšķa uz elementa EDIT izpildīt darbību
     new_comment.querySelector('.edit').onclick = function (event) {
        const data = new FormData();
        data.set('id', id);

        xhttp.post('api.php?name=get-comment', data, function (response) {
          //console.log(response);
          popup.style.display = 'flex'; // kad uzklikšinās uz edit, tad dabūs display:flex vērtību. Stila failā nodefinēts, ka ir display:none (sanāk pēc noklusējuma)
          
          // popup.querySelector('[name="author"]').value = response.comment.author;
          // popup.querySelector('[name="email"]').value = response.comment.email;
          // popup.querySelector('[name="phone"]').value = response.comment.phone;
          // popup.querySelector('[name="message"]').value = response.comment.message;

          // šeit ieliekam autoru, e-pastu, telefonu un messagi jaunajā formā.
          // Kad sākam darbu pie update formas, tad ieviešot jaunu mainīgo form_update sākam input laukus meklētu no formas, nevis no visa popup.
          // ja atlasa pēc atribūta, tad izmanto kvadrātiekvas, piemēram, [name="author"]. No index faila redzams, ka name ir input tagad atribūts <input type="text" name="author" placeholder="Name" required>
          // forma bija aprakstīta pie mainīgajiem const form_update
          form_update.querySelector('[name="id"]').value = response.comment.id;
          form_update.querySelector('[name="author"]').value = response.comment.author;
          form_update.querySelector('[name="email"]').value = response.comment.email;
          form_update.querySelector('[name="phone"]').value = response.comment.phone;
          form_update.querySelector('[name="message"]').value = response.comment.message; 
        });
      };

     /** komentāru blokā ar append pievienosim new_comment */
    comment_block.append(new_comment)
}

//garais pieraksts: document.querySelector('popup').onclick=function{}
popup.onclick = function(event) {
  // lai popup logs pazūd tikai tad, ja uzlikškinām iekš šī elementa. 19.05.lekcija 47.minūte
  //pārbaudām event target ir vienāds ar this. Vai šie objekti ir viens un tas 
  if (event.target == this) {
      this.style.display = 'none';
  }
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