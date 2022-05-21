<?php
// tālāk tāda konstrukcija kā namspace. Namespace sakritīs ar mapes nosaukumu - Storage. Namespace pasaka, kurā mapē mēs atrodamies. 
// Ja iekš Storage būtu vēl viena mape /Db2 vai ORM, tad namspace būtu garāks Storage\DB2, kas atspoguļot vairāku mapju secīgumu
// Pilnais klases nosaukums šobrīd Storage DB . Iedod tādu prefiksu.
namespace Storage;
class DB
{
    private $table_name = null;
    private $connection = null;

    //$table_name tiek iestatīts konstruēšanas brīdī. Tā tiek padota uz konstruktoru. API.php failā tika konstruēts, tad tur arī padodam: $db = new DB(contacts);
    public function __construct($table_name) {
        //Datu bāzes konfigurācija. Mainīgie izveidoti, lai tālāk connection izveidotu: $conn = new mysqli($servername, $username, $password, $dbname);
        $this->table_name = $table_name; //this, jo noderēs citās metodēs
        // $servername = "localhost";
        // $username = "root";
        // $password = "root";
        // $dbname = "contacts";

        // Create connection
        $this->conn = new \mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME); // Izmantojam mysqli klasi. Meklējam sākotnējā līmenī. this - šīs metodes konstruēšanas brīdī iestatīsim nevis lokālu mainīgo. Būs pieejams visās metodēs iekš šīs klases
        // Check connection, Pārbaude, vai savienojums ir izveidojies
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    //Pārnests no Constructor daļas.
    //Deconstruct tiek izsaukts tad, kad vairs neizmantojam klasi php kodā, automātiski izsauksies šī metode function __deconstruct() un aizvērs ciet savienojumu.
    public function __deconstruct() {
        $this->conn->close();
    }

    //Konekcija jau būs izveidojusies.  Tabula zināma. Vajag ko līdzīgu getAll() {}
    //$ entry ir masīvs, kas sastāv no autora, epasta,....  (api.php) 'author' => $_POST['author']
    public function addEntry($entry) {

        // Iesim cauri šim masīvam $entry un katru reizi iekš šī {} cikla, būs pieejam šī konkrētā ieraksta atslēga $key un vērtība  $value. Masīvs kā tabula 
        // api.php 'author' (atslēga) => $_POST['author'] (vērtība),
        //izveidos $column_str, kurā ejot cauri ciklam būs atslēgas author, email, phone, message,  Viens, pēdējais komats paliek lieks
        $column_str = '';
        $value_str = '';
        //Jāizveido mainīgais, kas saturēs šo te tekstu: 'Vineta', 'vineta.vevere@gmail.com' , '+3711112222', 'Sveiki pasaule' katrs vārds ielikts pediņās

        foreach ($entry as $key => $value) {
            $column_str .= $key . ',';
            $value_str .= "'" . $this->conn->real_escape_string($value) . "',"; //pret injekciju. Iepriekš bija $value_str .= "'" . $value . "',"; Visus speciālos simbolus, kas var būt šajā tekstā, priekšā būs slīpsvītra 1\' Priekš SQL norādīs to, ka simbols, kas seko aiz šīs svītras, nav speciālais simbi.                              
        }
            //Viens, pēdējais komats paliek lieks. ar rtrim noņemam.
        $column_str = rtrim($column_str, ',');
        $value_str = rtrim($value_str, ',');
        

            // Nevispārināts, bet ļoti saprotams
            // " .$this->table_name. "  = teksts, mainīgais, tālākais teksts. Insertojam šajā tabulā Šajos laukos: author.... Id pašam jāģenerējas

            // $sql = "INSERT INTO " .$this->table_name. " (author, email, phone, message)
            // VALUES ('Vineta', 'vineta.vevere@gmail.com' , '+3711112222', 'Sveiki pasaule')";

            // Vispārināts
        $sql = "INSERT INTO " . $this->table_name . " ($column_str) VALUES ($value_str)";

        // Pieprasījuma atbilde ierakstīsies iekš $result
        $result = $this->conn->query($sql);
        //Kad viss veiksmīgi, tad atbilde ir TRUE
        if ($result === true) {
            // šeit būs id jāatgriež
            return $this->conn->insert_id;
        }
        return false;
    }

    //Publiska funkcija getAll un tā izvadīs masīvu, kas saturēs visus ierakstus. Masīvs ar masīviem. Autora, e-pasta, telefona un ziņas kombinācija
    public function getAll() {

        //sql pats pieprasījums. Izveidots sql. Pārnests no Construktora
        $sql ="SELECT * FROM " . $this->table_name; // * nozīmē visu. Atlasīt visu no contacts: $sql ="SELECT * FROM contacts"; Lai universālāk izmantojam $this->table_name. Caur punktu apvienojam

        //sql koda pieprasījums uz MySQL serveri
        //atbilde no sql pieprasījuma iestatīsies iekš $result. Tālāk result jādabū iekš return
        //kad select pieprasījums (query), tad atbilde ir jābūt objekta veidā - $result
        $result = $this->conn->query($sql);

        //Dabūt visus datus no šī rezultāta
       
        if ($result !== false) {
        // $result ir objekts un ar šādu bultiņu -> varam izmantot metodes
        return $result->fetch_all(MYSQLI_ASSOC); //Dēļ šīs konstantes, ko padodam (MYSQLI_ASSOC), funkcijai būs cits režīms, ko atgriež. Atgriež asociatīvu masīvu. Tāds, kuram ir atslēgas
        //Asociatīva vērtība ir tāda, kurai ir klāt atslēga. Vērtība asociatīva masīva veidē ['author' = 'vineta", 'message' => 'hello world']. nevis [0] -tais [1] -mais elements..., bet konkrētu atslēgu
        // Starprezultāti:
        // return
        // ['author' => "Vineta", 'email'=> 'vineta.vevere@gmail.com', 'phone'=>"123456", 'message'=> "Sveika"],
        // ['author' => "Kārlis", 'email'=> 'karlis.inka1@gmail.com', 'phone'=>"234567", 'message'=> "Hei"]
        // return ['test']; // sākonējais tests, lai pārbaudīti, vai ieiet šajā failā un funkcijā. 
        }
        return false;
    }

    public function getError() {
        if (DEBUG_MODE) {
            return $this->conn->error;
        }
        else {
            return 'An error has aqured';
        }
    }
}
?>