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
 	
    public function set_date($value) {
        $this->date = $value;
    }
    public function set_description($value) {
        $this->description = $value;
    }
    public function set_link($value) {
        $this->link = $value;
    }

    public function set_title($value) {
        $this->title = $value;
    }
    
    /**
     * Providing field setters on public interface
     */
    public function __set($var, $value){
    	$var = strtolower($var);
    	if(in_array($var, array('link', 'title', 'date', 'description'))){
    		call_user_func(array($this,"set_$var"), $value);
    	}
    }
    
    /**
     * Provides filed getters on public interface.
     */
	public function __get($var){
    	$var = strtolower($var);
    	if(in_array($var, array('link', 'title', 'date', 'description'))){
    		return call_user_func(array($this,"get_$var"), $value);
    	}
    }
    
    /**
     * @todo Eliminate this method
     * @param type $data 
     */
    public function fill($data) {
        if (is_array($data)) {
            foreach(array("title", "link", "description", "date") as $prop){
                $value = isset($data[$prop])? $data[$prop]: ' - ';
                call_user_func(array($this, "set_$prop"), $value);
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
