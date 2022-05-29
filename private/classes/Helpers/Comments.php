<?php

//Namspespace sakrīt ar mapi, kur mēs atrodamies
namespace Helpers;
//Comments varēs izmanto funkcionalitāti no šīs klases, no DB.
use Storage\DB; //Variants ar as (piemēram, use Storage\DB as DataBase ir, kad lielāks projekts). Visbiežāk izmantotie nosaukumi sāk jau aizpildīties.

class Comments
{ 
    //privāti pieejama vērtība, kas nebūs pieejama ārpus klases. $db_comments neko nesaturēs sākumā. null.
    private $db_comments; 
    // jāraksta klāt public vai private. šī būs publiski pieejama funkcija - arī ārpus šīs klases
    public function __construct() {
        // Konstruēšanas brīdī $db_comments saturēs šo jauno objektu new DB('comments'). Šis objekts $db_comments būs pieejams iekš visām metodēm. Varam ņemt ārā no funkcijas getAll();
        // konstruējam objektu, padodot uz to tabulas nosaukumu. Izveidojam, mainīgo $db, kas tika pārsaukts par comment_manager, bet tagad ir  
        $this->db_comments = new DB('contacts'); // Izveidots DB objekts. Pārsaukts no $db uz $comment_manager, lai pēc nosaukuma saistīts ar komentāru tabulu datu bāzē. db_comments ir objekts no šī: new DB('contacts')
    }

    /**
     * getAll() funkcija uztaisa pieprasījumu uz šejieni: $this->db_comments->getAll(). Ierakstām iekš mainīgā $result. Tur būs resultāts, kas tiks saņemts no db.php
     */
    public function getAll() {
        $result = $this->db_comments->getAll();
        //tiks atgriezts masīvs
        
        if ($result === false) { 
            return [
                'status' => false,
                'error_msg'=>  $this->db_comments->getError() //Atkļūdošanai. Piemēram, nepareizam db nosaukumam. $this->db_comments = new DB('contacts_111')
            ];
        }

        return [
            'status' => true, //=> ir piešķiršanas operators, kad veido masīvu
            'comments' => $result // getAll būs mūsu izveidota metode, 'comments' => $comment_manager ->getAll() // getAll būs mūsu izveidota metode. -> izmanto, lai piekļūtu objekta metodēm un īpašībām.
                //caur šo objektu  $this->db_comments, kas izveidots no klases class DB, tiekam pie šis klases metodes getAll(), lai dabūtu visus ierakstus no datu bāzes. Objekts var dabūt mums visas klases objektus.
        ];   
    }

    public function get() {
         //Ja gribam dabūt konkrētu komentāru, pārbaudām, vai ir dabūts konkrēts id
         if (isset($_POST['id']) && is_string($_POST['id'])) {
            /**
             * Šo vairs nevajag, jo augstāk, konstruēšanas brīdī tika izveidots objekts $this->db_comments no klases DB, caur kuru varēs piekļūt klases DB metodei getEntry().
             * Izveidojam $comment_manager objektu
             * $comment_manager = new DB('contacts'); 
             */
            
            //dabūjam id
            $id = (int) $_POST['id']; 
            //atgriežam datus. Iepriekšējā pieraksta  $output = [] vietā rakstām return []
            return [
                'status' => true,
                // dabūjam pēc id komentāru un izvadām mainīgajā ar atslēgu'comment'
                'comment' => $this->db_comments->getEntry($id)
            ];
        }
    }

    public function add(){
        if (    
            // Api pusē pārbaudām, vai tika padoti īstie dati. Izmantojam POS metodi.
            isset($_POST['author']) && is_string($_POST['author']) &&
            isset($_POST['email']) && is_string($_POST['email']) &&
            isset($_POST['phone']) && is_string($_POST['phone']) &&
            isset($_POST['message']) && is_string($_POST['message'])
         ) {

            //noņemam tukšuma simbolus beigās
            $author = trim($_POST['author']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $message = trim($_POST['message']);
            
            // Tikai šādi pierakstot bootstraps nezina, no kuras mapes to DB dabūt ārā. Tāpēc augšā jāraksta use Storage\DB; Izmantojam to DB, kas iekš Storage
            // $comment_manager = new DB('contacts'); //konstruējam objektu, padodot uz to tabulas nosaukumu. Izveidojam, mainīgo $db. Varam pārsaukt par comment_manager. 
            // šajā vietā izvadām. šī vietā $output = [] rakstām return[]
            return [
                'status' => true,
                'author' => $author, //Response būs piemēram, šāds "author": "Vineta V\u0113vere",
                'email' => $email,
                'phone' => $phone,
                // masīvā pēdējam komata nav, ja aiz tā nekas neseko!!!  
                'message' => $message, 
                //kaut kāds test, db izsaucam metodi addEntry(), uz viņu padodod visu masīvu test' => $db->addEntry($_POST). Drošāk pa vienam.
                //šeit varam atgriezt id. Id datu bāzes pusē izveidots. Lai addEntry atgriež atpakaļ id. Mēs uz padodam  masīvu ar autoru, e-pastu, telefona Nr un ziņu.
                'id' => $this->db_comments ->addEntry ([ 
                    // šeit notiek datu sūtīšana uz datu bāzi 
                    //'author' (atslēga) => $_POST['author'] (vērtība),
                    // ar funkciju trim noņem atstarpes beigās un sākumā
                    'author' => $author,
                    'email' => $email,
                    'phone' => $phone,
                    'message' => $message
                ])
            ];
        }
    }

    public function update() {
        if (    
            // Api pusē pārbaudām, vai tika padoti īstie dati. Izmantojam POS metodi.
            isset($_POST['id']) && is_string($_POST['id']) &&
            isset($_POST['author']) && is_string($_POST['author']) &&
            isset($_POST['email']) && is_string($_POST['email']) &&
            isset($_POST['phone']) && is_string($_POST['phone']) &&
            isset($_POST['message']) && is_string($_POST['message'])
         ) {
            $id = (int) $_POST['id']; 

            $author = trim($_POST['author']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $message = trim($_POST['message']);

            $comment_manager  = new DB('contacts'); // Izveidots DB. Pārsaukts no $db uz $comment_manager, lai pēc nosaukuma saistīts ar komentāru tabulu datu bāzē.

            //Aprakstām, kādā formātā agriezīsim tekstu. Varēja tādā pašā kā add-comments gadījumā
            return [
                'status' => true,
                //atgriezīsim arī id
                'id' => $id,
                // update metodē noteikti ir jāpadod id un jauni dati
                // šeit varēsim dabūt atbildi un uzreiz vienā mainīgajā comment. Nebūs jādala, atsevišķi author,,, message
                'comment' => $this->db_comments ->updateEntry($id, [
                    // šīs vērtības, kuras tiks updatotas
                    'author' => $author,
                    'email' => $email,
                    'phone' => $phone,
                    'message' => $message
                ] )
            ];     
         }
    }

    public function delete(){
        //Izmantojam DB klasi
        $comment_manager  = new DB('contacts');
                
        // tālāk jānolasa id. Vispirms pārbaude. post masīvu pārbaudām, vai ir padots id un vai ir tekstuāla formāta. Vai nav masīvs
        if (isset($_POST['id']) && is_string($_POST['id'])) {
            //tiks izveidots id
            $id = (int) $_POST['id'];   
            
           return [
                //ja statuss ir true, tikai tad Javascripts izpildīsies tālāk
                'status' => $this->db_comments ->deleteEntry($id),
                'id' => $id,
            ];
        }
    }

}

