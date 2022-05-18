<?php
class StorageManager
{
    private $file_name;
    private $data = [
        'entries' => [],
        'next_id' => 1
    ];

    public function __construct($file_name = 'data.json') {
        $this->file_name = $file_name; //Konstruēšanas brīdī nosaka kādā failā. Faila nosaukums tiks ierakstīts iekš $this->file_name. Mainīgais, kas piejams visām klasēm
        if (file_exists($file_name)) {
            $content = file_get_contents($file_name); 

            $data = json_decode($content, true); //Saturs tiek konvertēts uz masīvu. Fails saturs tiek ierakstīts iekš $data. decpode = JSON kodētu virkni pārvērš to par PHP mainīgo.

            if (is_array($data)) { //Pārbaudās, vai tiešām masīvs
                $this->data = $data; //$data tiek ierakstīts iekš šī data
            }
        }
    }

    public function getAllEntries() {
        return $this->data['entries'];
    }

    public function getEntry($id) {
        if (isset($this->data['entries'][$id])) {
            return $this->data['entries'][$id];
        }
        return null;
    }

    public function addEntry($value) {
        $this->setEntry($this->data['next_id'], $value);

        return $this->data['next_id']++;
    }

    // public function updateStatus($id, $status) {
    //     $this->data['entries'][$id]['status'] = $status;

    //     $content = json_encode($this->data, JSON_PRETTY_PRINT);
    //     file_put_contents($this->file_name, $content);
    // }

    public function setEntry($id, $entry) {
        $this->data['entries'][$id] = $entry;

        $content = json_encode($this->data, JSON_PRETTY_PRINT); //Atgriež virkni, kas satur piegādātā JSON atveidojumu value.
        file_put_contents($this->file_name, $content);
    }

    public function getEntryCount() {
        return count($this->data['entries']);
    }

    public function deleteEntry($id) {
       unset($this->data['entries'][$id]);
       $content = json_encode($this->data, JSON_PRETTY_PRINT);
       file_put_contents($this->file_name, $content);
    }

    public function resetData() {
        $this->data = [
            'entries' => [],
            'next_id' => 1
        ];
        file_put_contents($this->file_name, '');
    }
}