<?php
include "classes/StorageManager.php"; //api.php tiek ielādēts StoragManageris
$drive = new StorageManager('comments_file.json'); // faila nosaukums kurā rakstīt datus comments_file.json). Tur glabāsies visa vajadzīgā informācija.
header('Content-type: application/json'); //noteikts, ka atbilde būs json formātā

$output = [ //Sagatavots sākotnējais output
    'status' => false
];


// if (isset($_GET['name']) && is_string($_GET['name'])) {

    if ($_GET['author'] == 'Vineta') {
        $output = [
            'status' => true,
            'message' => "Vēvere!"
        ];

//    }

echo json_encode($output);


/*

ALGORITMS 1.
    1. Izprast uzdevumu (uzdevums tiek formulēts no rezultāta)
    2. Jautājumi un idejas (Kur? Kas?)
    3. Sadalīt uzdevumu mazākos soļos.

PADOMS 2.
    1. Sākt no beigām.
    __________________________________________
*/
/*
UZDEVUMI
    1. ✅ Saglabāt uzdevuma statusu todo_list.json failā (uzdevuma pievieošanas brīdi).
    1.2. ✅ Tekstu attēlot uzdevumu sarakstās lapas ielādēšanas brīdī.
    2. ✅ Attēlot statusu uzdevumu sarakstā, lapas ielādēšanas brīdī.
    3. ✅ Mainīt uzdevuma statusu (tajā brīdi kad mainam čekboks stāvokli)
*/
/*
KODA STRUKTŪRA

   BACKEND - SERVER
API, Drive

  FRONTEND - BROWSER
HTML , 

ieklikšķinam -> JS -> [API -> Drive]
[API] -> JS -> ...

*/