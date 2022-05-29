<?php

// __DIR - vispirms tekošā mape. Tad mapi augstāk . '/../. Tad uz private mapi.
// caur šejieni pielādēs class DB, kur boostrap.php ir spl_autoload_register, kas mēģinās šo klasi includot, ja klase vēl nav pieejama
include __DIR__ . '/../private/bootstrap.php'; 
// echo file_get_contents(__DIR__ . '/../private/bootstrap.php');

use Storage\DB; //Variants ar as (piemēram, use Storage\DB as DataBase ir, kad lielāks projekts). Visbiežāk izmantotie nosaukumi sāk jau aizpildīties.
use Helpers\Comments;

// Header, kas noteikts, ka saturs, ko atgriezīs šis pieprasījums uz šo failu, būs json formātā. tips application/json 
header('Content-Type: application/json');

// Nodefinējam output masīvu ar statusu false. 
$output = ['status' => false];
// Tālākais kods nav optimizēts. 19/05_2 daļas lekcija 01:21:38
if (isset($_GET['name']) && is_string($_GET['name'])) {
    $comment_helper = new Comments(); //šo klasi Comments izmantojam un uz tās bāzes izveidojam comment helperi. Izveidojam objektu comment_helper no šīs klases.

    //Vispirms pārbauda $_GET[] masīvā, vai ir īstais api? Vai name ir  vienāds ar api, kas mūs interesē. No index.html faila :  <form action="api.php?name=add-comment" id="comments_form">
    if ($_GET['name'] === 'add-comment') {
        //šeit ( $comment_helper->add() )tiek saģenerēta atbilde.
        $output = $comment_helper->add();
    }   

    //Rakstām api priekš update-comments, kas tiek izsaukts submitojot formu form_update, <form action="api.php?name=update-comment" id="comments_update_form">
    elseif ($_GET['name'] === 'update-comment') {
        $output = $comment_helper->update();
    }

    // vajag tikai piekļūt datu bāzei un izvadīt. get-comments 
    // taisot pieprasījumu ar javascript uz šo adresi: $_GET['name'] === 'get-comments', tiks izpildīta metode getAll() un metode sākotnējam testam atgrieza masīvu ar vārdu test (DB.php return ['test'];)
    elseif ($_GET['name'] === 'get-comments') {
        $output = $comment_helper->getAll();
    }

    elseif ($_GET['name'] === 'get-comment') {
        /** Iepriekš $output = $comment_helper->get(); vietā bija daļa no Comments.php, kas tika uz turieni pārnesta.*/
        $output = $comment_helper->get(); //Helperī izsaucam metodi get(). Kopējam pierakstu no get-comments, jo tas  ir līdzīgs, tikai metode no klases, kuru dabūjam caur objektu $comment_helper, ir get(), kā tas tika nodefinēts api.php failā;
    }

    //Par cik pieprasījums bija ar post metodi, tad tā daļa, kas ierakstīta adresē (jo šeit ir query parametrs), nosūtīsies ar get metodi
    elseif ($_GET['name'] === 'delete-comment') {
        $output = $comment_helper->delete(); 
    }

    elseif ($_GET['name'] === 'upload-image') {
       if ( 
            isset($_POST['author']) && is_string($_POST['author']) &&

            //Files iestatīts būs vienmēr, pat ja nav dati padoti, bet jāpārbauda, vai tas nav tukšs masīvs
            // ja $_FILES nav tukšs, tad iekš $_FILES varam dabūt image. Un otra pārbaude, vai ir iestatīts nosaukums ar upload_image. 
            !empty($_FILES) && isset($_FILES['upload_image'])  //no image_upload.html faila <input type="file" name="upload_image" />
        ) { 
            $image_arr = $_FILES['upload_image']; 
            //ja $image_arr['error'] == 0, tas nozīmē, ka kļūdas nav
            if ($image_arr['error'] == 0) {
                
                $image_arr['name'];

                //'tmp_name' ir viena no $FILES masīva vērtībām (tā redzama atgrieztajos datos responsā). Piemēram, tmp_name: "C:\\Users\\vinetav\\AppData\\Local\\Temp\\phpAE4F.tmp"
                $file_content = file_get_contents($image_arr['tmp_name']);

                //Izveidosim 2 teksta nogriežņus, lai dabūtu ceļu, kurā ierakstīt failu. $file_content ir paša faila vērtība. Fails $file_content ieliekas Upload mapē ar nosaukumu image.png
                file_put_contents(UPLOAD_DIR . "image.png", $file_content);

                $output = [
                     'status'=> true,
                     'file' => $image_arr

                     /** testēšanas nolūkos atgriezām un apskatījām šos masīvus
                     * 'post' => $_POST, //POST masīvu atgriezīsim
                     * 'file' => $_FILES //ir arī šāds masīvs $FILES php nodefinēts. Kad ir POST pieprasījums, tad varam iekš FILES saturēs failus, kas tika padoti uz šejieni.
                     */
                ];
            }  
        }
    }
}

//enkodēt uz JSON formātu array. API izvada json formātā datus, nosūta atbildi uz Javascriptu - dod atbildi atpakaļ pārlūkam json formātā.
//echo and print are more or less the same. They are both used to output data to the screen.
echo json_encode($output, JSON_PRETTY_PRINT); 
