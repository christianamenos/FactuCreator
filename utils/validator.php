<?php
class Validator
{
  
  /*
   * List of the possible checking modes.
   */
  public static $VALID_NOT_NULL = 0x01;
  public static $VALID_NUMBER   = 0x02;
  public static $VALID_STRING   = 0x04;
  public static $VALID_MAIL     = 0x08;
  public static $VALID_SELECT   = 0x10;
  
  private $mode = 0x00;
  private $min = null;
  private $max = null;

  /*
   * This function checks if the value of $value is correct for the mode indicated.
   * The mode can be a convination between one or more modes through the combination
   * of some of them with a OR al bit level.
   * 
   * Then the value 0X00011 will check that the value of $value is not null, and
   * that it represents a numeric value.
   * 
   * Additionally, some modes could need extra information, like VALID_SELECT,
   * which requires an array of values.
   * 
   * Params:
   * - $value: the value that we want to check.
   * - $mode:  the kind of check we want to do.
   * - $additionalParams: mixed value. Depending on the mode it could be necessary
   * to check some extra information.
   * 
   * Return:
   * - $b (Boolean), which indicates if the value is or not correct depending on the
   * specified mode.
   */
  public function check($value, $mode, $additionalParams = array())
  {
    $b = true;
    if($mode & self::$VALID_NOT_NULL){
      return is_null($value);
    }
    if($mode & self::$VALID_NUMBER){
      return is_null($b);
    }
  }
  
}
?>