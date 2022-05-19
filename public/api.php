<?php

// __DIR - vispirms tekošā mape. Tad mapi augstāk . '/../. Tad uz private mapi.
// caur šejieni pielādēs class DB, kur boostrap.php ir spl_autoload_register, kas mēģinās šo klasi includot, ja klase vēl nav pieejama
include __DIR__ . '/../private/bootstrap.php'; 

// echo include __DIR__ . '/../private/bootstrap.php';

use Storage\DB; //Variants ar as (piemēram, use Storage\DB as DataBase ir, kad lielāks projekts). Visbiežāk izmantotie nosaukumi sāk jau aizpildīties.

// Header, kas noteikts, ka saturs, ko atgriezīs šis pieprasījums uz šo failu, būs json formātā. tips application/json 
header('Content-Type: application/json');

// Nodefinējam output masīvu ar statusu false
$output = ['status' => false];
if (isset($_GET['name']) && is_string($_GET['name'])) {
    //Vispirms pārbauda $_GET[] masīvā, vai ir īstais api? Vai name ir  vienāds ar api, kas mūs interesē. No index.html faila :  <form action="api.php?name=add-comment" id="comments_form">
    if ($_GET['name'] === 'add-comment') {
         if (    
            // Api pusē pārbaudām, vai tika padoti īstie dati.Izmantojam POS metodi.
            isset($_POST['author']) && is_string($_POST['author']) &&
            isset($_POST['email']) && is_string($_POST['email']) &&
            isset($_POST['phone']) && is_string($_POST['phone']) &&
            isset($_POST['message']) && is_string($_POST['message'])
         ) {
            // Tikai šādi pierakstot bootstraps nezina, no kuras mapes tp DB dabūt ārā. Tāpēc augšā jāraksta use Storage\DB; Izmantojam to DB, kas iekš Storage
            $db = new DB(); 
            // šajā vietā izvadām
            $output = [
                'status' => true,
                //Uzmanīgi ar komatiem
                'author' => $_POST['author'], //Response būs piemēram, šāds "author": "Vineta V\u0113vere",
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                // Pēdējam komata nav, ja aiz tā nekas neseko!!!  
                'message' => $_POST['message'], 
                //kaut kāds test, db izsaucam metodi addEntry(), uz viņu padodod visu masīvu test' => $db->addEntry($_POST). Drošāk pa vienam.
                'test' => $db->addEntry([ 
                    'author' => $_POST['author'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'message' => $_POST['message'] 
                    ])
            ];
        }
    }   
    // vajag tikai piekļūt datu bāzei un izvadīt. get-comments 
    // taisot pieprasījumu ar javascript uz šo adresi: $_GET['name'] === 'get-comments', tiks izpildīta metode getAll() un metode pagaidāma atgriež masīvu ar vārdu test (DB.php return ['test'];)
    elseif ($_GET['name'] === 'get-comments') {
        $db = new DB(); // Izveidots DB
        $output = [
            'status' => true,
            'comments' => $db->getAll() // getAll būs mūsu metode
        ];     
    }
}

//enkodēt uz JSON formātu array. API izvada json formātā datus, nosūta atbildi uz Javascriptu - dod atbildi atpakaļ pārlūkam json formātā.
echo json_encode($output, JSON_PRETTY_PRINT); 
