<?php

// __DIR - vispirms tekošā mape. Tad mapi augstāk . '/../. Tad uz private mapi.
// caur šejieni pielādēs class DB, kur boostrap.php ir spl_autoload_register, kas mēģinās šo klasi includot, ja klase vēl nav pieejama
include __DIR__ . '/../private/bootstrap.php'; 
// echo file_get_contents(__DIR__ . '/../private/bootstrap.php');

use Storage\DB; //Variants ar as (piemēram, use Storage\DB as DataBase ir, kad lielāks projekts). Visbiežāk izmantotie nosaukumi sāk jau aizpildīties.

// Header, kas noteikts, ka saturs, ko atgriezīs šis pieprasījums uz šo failu, būs json formātā. tips application/json 
header('Content-Type: application/json');

// Nodefinējam output masīvu ar statusu false. 
$output = ['status' => false];
if (isset($_GET['name']) && is_string($_GET['name'])) {
    //Vispirms pārbauda $_GET[] masīvā, vai ir īstais api? Vai name ir  vienāds ar api, kas mūs interesē. No index.html faila :  <form action="api.php?name=add-comment" id="comments_form">
    if ($_GET['name'] === 'add-comment') {
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
            
            // Tikai šādi pierakstot bootstraps nezina, no kuras mapes tp DB dabūt ārā. Tāpēc augšā jāraksta use Storage\DB; Izmantojam to DB, kas iekš Storage
            $comment_manager = new DB('contacts'); //konstruējam objektu, padodot uz to tabulas nosaukumu. Izveidojam, mainīgo $db. Varam pārsaukt par comment_manager. 
            // šajā vietā izvadām
            $output = [
                'status' => true,
                'author' => $author, //Response būs piemēram, šāds "author": "Vineta V\u0113vere",
                'email' => $email,
                'phone' => $phone,
                // masīvā pēdējam komata nav, ja aiz tā nekas neseko!!!  
                'message' => $message, 
                //kaut kāds test, db izsaucam metodi addEntry(), uz viņu padodod visu masīvu test' => $db->addEntry($_POST). Drošāk pa vienam.
                //šeit varam atgriezt id. Id datu bāzes pusē izveidots. Lai addEntry atgriež atpakaļ id. Mēs uz padodam  masīvu ar autoru, e-pastu, telefona Nr un ziņu.
                'id' => $comment_manager ->addEntry([ 
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

    //Rakstām api priekš update-comments, kas tiek izsaukts submitojot formu form_update, <form action="api.php?name=update-comment" id="comments_update_form">
    elseif ($_GET['name'] === 'update-comment') {
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
            $output = [
                'status' => true,
                //atgriezīsim arī id
                'id' => $id,
                // update metodē noteikti ir jāpadod id un jauni dati
                // šeit varēsim dabūt atbildi un uzreiz vienā mainīgajā comment. Nebūs jādala, atsevišķi author,,, message
                'comment' => $comment_manager ->updateEntry($id, [
                    // šīs vērtības, kuras tiks updatotas
                    'author' => $author,
                    'email' => $email,
                    'phone' => $phone,
                    'message' => $message
                ] )
            ];     
         }
    }

    // vajag tikai piekļūt datu bāzei un izvadīt. get-comments 
    // taisot pieprasījumu ar javascript uz šo adresi: $_GET['name'] === 'get-comments', tiks izpildīta metode getAll() un metode pagaidām atgriež masīvu ar vārdu test (DB.php return ['test'];)
    elseif ($_GET['name'] === 'get-comments') {
        $comment_manager  = new DB('contacts'); // Izveidots DB. Pārsaukts no $db uz $comment_manager, lai pēc nosaukuma saistīts ar komentāru tabulu datu bāzē.
        $output = [
            'status' => true,
            'comments' => $comment_manager ->getAll() // getAll būs mūsu izveidota metode
        ];     
    }

    //Par cik pieprasījums bija ar post metodi, tad tā daļa, kas ierakstīta adresē (jo šeit ir query parametrs), nosūtīsies ar get metodi
    elseif ($_GET['name'] === 'delete-comment') {
        //Izmantojam DB klasi
        $comment_manager  = new DB('contacts');
         
        // tālāk jānolasa id. Vispirms pārbaude. post masīvu pārbaudām, vai ir padots id un vai ir tekstuāla formāta. Vai nav masīvs
        if (isset($_POST['id']) && is_string($_POST['id'])) {
            //tiks izveidots id
            $id = (int) $_POST['id'];   
            
            $output = [
                //ja statuss ir true, tikai tad Javascripts izpildīsies tālāk
                'status' => $comment_manager ->deleteEntry($id),
                'id' => $id,
            ];
        }
    }
    elseif ($_GET['name'] === 'get-comment') {
        //Ja gribam dabūt konkrētu komentāru, pārbaudām, vai ir dabūts konkrēts id
        if (isset($_POST['id']) && is_string($_POST['id'])) {
            //Izveidojam $comment_manager objektu
            $comment_manager  = new DB('contacts'); 
            //dabūjam id
            $id = (int) $_POST['id']; 
            //atgriežam datus
            $output = [
                'status' => true,
                // dabūjam pēc id komentāru un izvadām mainīgajā ar atslēgu'comment'
                'comment' => $comment_manager ->getEntry($id)
            ];   
        }  
    }
}

//enkodēt uz JSON formātu array. API izvada json formātā datus, nosūta atbildi uz Javascriptu - dod atbildi atpakaļ pārlūkam json formātā.
//echo and print are more or less the same. They are both used to output data to the screen.
echo json_encode($output, JSON_PRETTY_PRINT); 
