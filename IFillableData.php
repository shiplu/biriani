<?php
/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * implentors will be able to fill themselves with provided data.
 */
interface IFillableData extends IData{

    function fill($data);
}

?>
