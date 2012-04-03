<?php
/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

class Biriani_Data implements IFillableData {

    protected $filled;

    protected $title;
    protected $link;
    protected $description;
    protected $date;

    public function get_date() {
        return $this->date;
    }
    public function get_description() {
        return $this->description;
    }
    public function get_link() {
        return $this->link;
    }

    public function get_title() {
        return $this->title;
    }
    /**
     *
     * @param type $data 
     */
    public function fill($data) {
        // making sure that once a data is filled
        // the object can not be re-initialized
        if (!$this->filled) {
            if (is_array($data)) {
                foreach(array("title", "link", "description", "date") as $prop){
                    $this->$prop = isset($data[$prop])? $data[$prop]: ' - ';
                }
                $this->filled = true;
            }
        }
    }

    public function __construct($data=null){
        $this->filled = false;
        if(!is_null($data) && is_array($data)){
            $this->fill($data);
        }
    }

}
?>
