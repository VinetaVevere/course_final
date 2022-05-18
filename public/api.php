<?php

include __DIR__ . '/../private/bootstrap.php';

// use Storage\DB;

// Header, kas noteikts, ka saturs, ko atgriezīs šis pieprasījums uz šo failu, būs json formātā. tips application/json 
header('Content-Type: application/json');

// Nodefinējam output masīvu ar statusu false
$output = ['status' => false];
if (isset($_GET['name']) && is_string($_GET['name'])) {
    //Vispirms pārbauda $_GET[] masīvā, vai ir īstais api? Vai name ir  vienāds ar api, kas mūs interesē. No index.html faila :  <form action="api.php?name=add-comment" id="comments_form">
    if ($_GET['name'] === 'add-comment') {

    // Pārbauda, vai tika padoti īstie dati. Izmantojam POS metodi.
         if (    
            // Api pusē pārbaudām, vai tika padoti īstie dati.
            isset($_POST['author']) && is_string($_POST['author']) &&
            isset($_POST['email']) && is_string($_POST['email']) &&
            isset($_POST['phone']) && is_string($_POST['phone']) &&
            isset($_POST['message']) && is_string($_POST['message'])
         ) {
            
            $output = [
                'status' => true,
                // Uzmanīgi ar komatiem
                'author' => $_POST['author'], //Response būs piemēram, šāds "author": "Vineta V\u0113vere",
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'message' => $_POST['message'] // Pēdējam komata nav!!!
                // 'comments' => $db->getAll()
            ];
        }
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);


    //     if (
            
    //         // Api pusē pārbaudām, vai tika padoti īstie dati.
    //         isset($_POST['author']) && is_string($_POST['author']) &&
    //         isset($_POST['email']) && is_string($_POST['email']) &&
    //         isset($_POST['phone']) && is_string($_POST['phonel']) &&
    //         isset($_POST['message']) && is_string($_POST['message'])
    //     ) {
    //         $db = new DB('comments');
    //         $output = [
    //             'status' => true,
    //             'author' => $_POST['author'],
    //             'message' => $_POST['message'],
    //             'test' => $db->addEntry([
    //                 'author' => $_POST['author'],
    //                 'message' => $_POST['message']
    //             ])
    //         ];
    //     }
    // }
    // elseif ($_GET['name'] === 'get-comments') {
    //     $db = new DB('comments');
    //     $output = [
    //         'status' => true,
    //         'comments' => $db->getAll()
    //     ];
