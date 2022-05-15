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
  