<?php
/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

class Biriani_Data implements IDataFillable {

    protected $data;
    protected $filled;

    const BIRIANI_DATA_FEED=0;
    const BIRIANI_DATA_AUDIO=1;
    const BIRIANI_DATA_VIDEO=2;
    const BIRIANI_DATA_HTML=3;
    const BIRIANI_DATA_XML=4;
    const BIRIANI_DATA_FLASH=5;

    public $title;
    public $desc;
    public $short_desc;
    public $last_modified;

    /**
     * @var string. Value can be audio, video
     */
    public $data_type;

    public function get_all_data() {
        return $this->data;
    }

    public function fill($data) {
        // making sure that once a data is filled
        // the object can not be re-initialized
        if (!$this->filled) {
            if (is_array($data)) {
                $this->data = $data;
                $this->filled = true;
            }
        }
    }

    public function __construct($data) {
        $this->data = array();
        $this->filled = false;
        $this->fill($data);
    }

    public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : '';
    }

}
?>
