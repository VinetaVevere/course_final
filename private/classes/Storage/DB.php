<?php

// tālāk tāda konstrukcija kā namspace. Namespace sakritīs ar mapes nosaukumu - Storage. Namespace pasaka, kurā mapē mēs atrodamies. 
// Ja iekš Storage būtu vēl viena mape /Db2 vai ORM, tad namspace būtu garāks Storage\DB2, kas atspoguļot vairāku mapju secīgumu
// Pilnais klases nosaukums šobrīd Storage DB . Iedod tādu prefiksu.
namespace Storage;
class DB
{

    public function addEntry($entry) {
       return $entry;
    }

    //Publiska funkcija getAll un tā izvadīs masīvu, kas saturēs visus ierakstus. Masīvs ar masīviem. Autora, e-pasta, telefona un ziņas kombinācija
    public function getAll() {
        // return [ 
        //     // ['author' => "Vineta", 'email'=> 'vineta.vevere@gmail.com', 'phone'=>"123456", 'message'=> "Sveika"],
        //     // ['author' => "Kārlis", 'email'=> 'karlis.inka1@gmail.com', 'phone'=>"234567", 'message'=> "Hei"]
        //     ]
        return ['test']; // šakonējais tests, lai pārbaudīti, vai ieiet šajā failā un funkcijā. 
    }
}