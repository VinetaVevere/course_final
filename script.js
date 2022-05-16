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

// Skripti Comments iesniegšanai

// Pārtvert notikumu uz formu. Piemēram sākumā izvadīt kaut ko consolē, kad submito formu.
const form = document.getElementById('comments_form'); //vispirms atlasām to formu. Index.html -->  <form action="" id="comments_form">. piešķiram mainīgajam

const comment_block = document.querySelector('.comments'); //comment_block saturēs to elementu, kurā tika ievietoti komentāri
const comment_template = comment_block.querySelector('.template'); //komentāru template izmantosim, lai ievietotu jaunu saturu


form.onsubmit = function (event) {
    event.preventDefault();
    // tālāk jāievāc formas dati, varam dabūt visas formas datus vienā rāvienā. Jāpadod pati forma - FormData(this). this būs tā pati forma
    const data = new FormData(this); //tas, kas adrešu joslā
    addComment(data.get('author'), data.get('email'), data.get('phone'), data.get('message'));
};

function addComment(author, email, phone, message) { //Izvadīt. Lai pievienotu vajadzēs id, bet pagaidām varam messagi un autoru)
  console.log(author, email, phone, message);  

  /**
   * Iekš šīs funkcijas tiks izveidots new_comment, kurš tiks paņemts no komentāru template - const comment_template 
   * Ja pirms tam veidojām elementu ar Document.createElement(), tad piešķīrām atribūtus, iekš viņa vajadzēja atsevišķus elementus. citu veidot. 
   * Tad šobrīd ejam citu ceļu -  sākumā sagatavojam template. index. html failā varam mainīt html, paši pievienot atribūtus
   * nodublējam elementu, uztaisām kopiju. Jāpadod true, lai nokopējas ne tikai pats elements, bet arī viss viņa iekšējais saturs. Mums ir div elements, arī lai p tags tiktu nokopēts
   */
  const new_comment = comment_template.cloneNode(true); //Izveidojam kopiju un ierakstām iekš new_comment
    new_comment.classList.remove('template'); // Lai būtu redzams!!! Jaunajam komentāram vajag noņemt klasi, jo viņš nokoppējās un arī parādījās klases template. 
    new_comment.querySelector('.message').textContent = message; //Iekš new_comment.querySelector('.message') Varam pievienot tekstu

    comment_block.append(new_comment) //komentāru blokā ar append pievienosim new_comment.
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
  