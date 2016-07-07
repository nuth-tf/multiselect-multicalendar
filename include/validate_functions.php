<?php

/**
 * @author Paul Wolbers 2008
 */

/**
 * Deze functie is om input van formulieren te controleren.
 *
 * Indien er een error optreed, wordt deze in $error gezet
 *
 * @return   mixed	De gevalideerde waarde
 * */
function validate_var($arr_submit, $bln_keep_post_values = false) {
    global $error;
    $str_empty_fields = '';

    $array = $_REQUEST;
    $arr_values = array();

    foreach ($arr_submit as $key => $value) {

        $val_value = '';

        $str_fieldname = $value[0];
        if (isset($_REQUEST[$value[0]])) {
            $var_to_check = $_REQUEST[$value[0]];
            if (!$bln_keep_post_values) {
                unset($_REQUEST[$value[0]]);
            }
        }

        $str_type = $value[1];
        $bln_required = $value[2];
        $default = (!empty($value[3]) || $value[3] == 0) ? $value[3] : '';

        if (strstr($default, 'substr')) {
            $f = String::getSubstring($default, '(', '', ',', '');
            $n = String::findNumberInString($default, true);
            $s = String::getSubstring($n, ',', '', ',', '');
            $e = String::getPartAfterLastGivenChar($n, ',');

            $default = substr($array['message'], $s, $e);
        }

        if (!empty($var_to_check)) {
            //Controleer type

            $val_value = checkvar($str_fieldname, $var_to_check, $str_type, $default);

            $arr_values[$str_fieldname] = $val_value;
        } else if ($bln_required == true) {
            //required, but not filled in
            if (!empty($str_empty_fields)) {
                $str_empty_fields .= ' , ' . $str_fieldname;
            } else {
                $str_empty_fields .= $str_fieldname;
            }

            $error = 'These are required fields:  ' . $str_empty_fields . ' ';
        } else if ($bln_required == false) {
            $arr_values[$str_fieldname] = $default;
        }

        $val_value = '';
        $default = '';
        $var_to_check = '';
    }

    return $arr_values;
}

function checkvar($str_fieldname, $var_to_check, $str_type, $default) {
    global $error;
    switch ($str_type) {

//				case 'varchar':
//					 if(empty($var_to_check)) {
//					 	return $default;
//					 }
//					 //mag niet alleen cijfers zijn
//					 //if(!ereg('^[0-9]+$', $var_to_check)) {
//					 if(!preg_match('/^[0-9]+$/', $var_to_check)) {
//					 //if (is_string($var_to_check) and !is_numeric($var_to_check) ) {
//					    $checked_var = $var_to_check;  //stond eerst htmlspecialchars
//						return $checked_var;
//					 } else {
//	                     $error = 'Een tekstveld verkeerd ingevuld ('.$str_fieldname.')';
//	                     //$str_error = 'wrong_input';
//	                     //return (!empty($default)) ? $default : $str_error;
//	                     return $default;
//	           		 }
//				break;

        case 'bool':

            if (empty($var_to_check)) {
                return false;
            }
            if ($var_to_check === 'on' OR $var_to_check === 'ja' OR $var_to_check === 'yes' OR $var_to_check === 'true' OR $var_to_check === true OR $var_to_check === 1 OR $var_to_check === '1') {

                return true;
            } else {
                return $default;
            }
            break;

        case 'enum/ja,nee':
            if (empty($var_to_check)) {
                return 'nee';
            }
            if ($var_to_check == 'on') {
                return 'ja';
            }
            break;

        case 'on':

            if ($var_to_check == 'on') {
                return 'on';
            } else {
                $error .= 'You have to agree to the terms of use';
                return 'off';
            }
            break;
        case 'on/off':
            if (empty($var_to_check)) {
                return 'off';
            }
            if ($var_to_check == 'on') {
                return 'on';
            }
            break;

        case 'md5':
            if (empty($var_to_check)) {
                return $default;
            }
            // if (is_string($var_to_check) and !is_numeric($var_to_check) ) {   // Marianne wilde numeriek wachtwoord
            if (is_string($var_to_check)) {
                $checked_var = md5($var_to_check);
                return $checked_var;
            } else {
                $error = 'textfield (' . $str_fieldname . ')';
                return $default;
            }
            break;

        case 'string':
            if (empty($var_to_check)) {
                return $default;
            }
            // letters, cijfers of allebei
            if (is_string($var_to_check) || is_numeric($var_to_check)) {
                return (string) htmlspecialchars($var_to_check);
            } else {
                $error = 'textfield (' . $str_fieldname . ')';
                return $default;
            }
            break;

        case 'textonly':
            if (empty($var_to_check)) {
                return $default;
            }
            if (preg_match('/^[A-Za-z ]+$/', $var_to_check)) {

                // if(ereg('^[A-Za-z]+$', $var_to_check)) {
                // if (preg_match('/^\d+\.?\d*$/', $varchar)){} kan ook
                return (string) $var_to_check;
            } else {
                $error = 'Only letters allowed (' . $str_fieldname . ')';
                return $default;
            }
            break;


        case 'email':
            if (empty($var_to_check)) {
                return $default;
            }
            $host = explode('@', $var_to_check);


            //if (eregi('^[a-z0-9.-_]+@([a-z0-9_]+\.)+[a-z]{2,6}$', $var_to_check)) {
            //if (ereg('^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$', $var_to_check)) {
            if (preg_match('/^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/', $var_to_check)) {

                if (function_exists('checkdnsrr')) {
                    if (checkdnsrr($host[1] . '.', 'MX'))
                        return $var_to_check;
                    if (checkdnsrr($host[1] . '.', 'A'))
                        return $var_to_check;
                    $error = 'Check emailaddress';
                    return $default;
                } else {
                    $result = array();
                    @exec('nslookup -type=MX ' . escapeshellcmd($host[1]) . '.', $result);
                    foreach ($result as $line) {
                        if (eregi("^$host[1].", $line)) {
                            return $var_to_check;
                        }
                    }

                    $error = 'Check emailaddress';
                    return $var_to_check;
                }
            } else {
                $error = 'Incorrect emailaddress';
                return $default;
            }

            break;

        case 'postcode':
            if (empty($var_to_check)) {
                return $default;
            }

            if (preg_match("/^[0-9]{4}[a-z|A-Z]{2}$/", $var_to_check)) {
                //if (eregi('^[0-9]{4}[a-z|A-Z]{2}$', $var_to_check)) {

                return $var_to_check;
            } else {
                $error = 'Dit is geen postcode';
                return $default;
            }

            break;

        case 'phone':
            if (empty($var_to_check)) {
                return $default;
            }

            if (preg_match("/^[0-9+ ()-]+$/", $var_to_check)) {
                // if (eregi('^[0-9 ()-]+$', $var_to_check)) {

                return $var_to_check;
            } else {
                $error = 'phone';
                return $default;
            }

            break;


        case 'captcha':
            if (empty($var_to_check)) {
                return $default;
            }
            $var_to_check = strtoupper($var_to_check);

            if (isset($_SESSION['c_s_id']) && $_SESSION['c_s_id'] == md5($var_to_check)) {

                return $var_to_check;
            } else {
                $error = 'Captcha code';
                return $default;
            }

            break;

        case 'name':
            if (empty($var_to_check)) {
                return $default;
            }

            if (preg_match("/^[A-Za-z -]+$/", $var_to_check)) {
                //if(ereg('^[A-Za-z -]+$', $var_to_check)) {
                $checked_name = ucwords(strtolower($var_to_check));            // maak van 1e letter een hoofdletter
                return $checked_name;
            } else {
                $error = 'Vul de naam correct in!';
                return $default;
            }

            break;

        case 'int':

            if (empty($var_to_check)) {
                return $default;
            }
            if (is_numeric($var_to_check)) {

                return (int) $var_to_check;
            } else {
                $error = 'A numeric field (' . $str_fieldname . ')';
                return $default;
            }
            break;
        case 'float':

            if (empty($var_to_check)) {
                return $default;
            }

            //if(ereg('^[-+]?[0-9]*\.?[0-9]+$', $var_to_check)){
            if (preg_match('/^[-+]?[0-9]*\.?[0-9]+$/', $var_to_check)) {

                return $var_to_check;
            } else {
                $error = 'A numeric field (float) : ' . $str_fieldname;
                return $default;
            }
            break;
        case 'date_mysql':
            if (empty($var_to_check)) {
                return $default;
            }

            if (preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|' .
                            '((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))(\s(20|21|22|23|[0-1]?\d):[0-5]?\d:[0-5]?\d)?$/', $var_to_check)) {

                return $var_to_check;
            } else {
                $error = 'Een datum verkeerd ingevuld, moet in deze vorm: yyyy-mm-dd';
                return $default;
            }
            break;

        case 'date_dutch':
            if (empty($var_to_check)) {
                return $default;
            }

            $mysql_date = date("Y-m-d", strtotime($var_to_check));
            if ($mysql_date != '1970-01-01') {
                if (preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|' .
                                '((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))(\s(20|21|22|23|[0-1]?\d):[0-5]?\d:[0-5]?\d)?$/', $mysql_date)) {

                    return $var_to_check;
                } else {
                    $error = 'Een datum verkeerd ingevuld, moet in deze vorm: dd-mm-yyyy';
                    return $default;
                }
            } else {
                $error = 'Een datum verkeerd ingevuld, moet in deze vorm: dd-mm-yyyy';
                return $default;
            }
            break;

        case 'date_dutch_2_mysql':
            if (empty($var_to_check)) {
                return $default;
            }

            $mysql_date = date("Y-m-d", strtotime($var_to_check));
            if ($mysql_date != '1970-01-01') {
                if (preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|' .
                                '((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))(\s(20|21|22|23|[0-1]?\d):[0-5]?\d:[0-5]?\d)?$/', $mysql_date)) {

                    return $mysql_date;
                } else {
                    $error = 'Een datum verkeerd ingevuld, moet in deze vorm: dd-mm-yyyy';
                    return $default;
                }
            } else {
                $error = 'Een datum verkeerd ingevuld, moet in deze vorm: dd-mm-yyyy';
                return $default;
            }
            break;



        case 'price':
            if (empty($var_to_check)) {
                return $default;
            }

            if (is_numeric($var_to_check)) {
                //Hier code om te checken
                $checked_int = htmlspecialchars($var_to_check);
                $checked_int = $checked_int;
                return $checked_int;
            } else {
                $error = 'Een numeriek veld verkeerd ingevuld';
                return $default;
            }
            break;

        case 'date':

            $arr_date = explode('-', $var_to_check);

            if (!empty($arr_date[1]) and ! empty($arr_date[0]) and ! empty($arr_date[2])) {
                if (strlen($arr_date[1]) == 2 and strlen($arr_date[0]) == 2 and strlen($arr_date[2]) == 4) {
                    if (checkdate($arr_date[1], $arr_date[0], $arr_date[2])) {
                        $checked_date = $var_to_check;
                        return $checked_date;
                    } else {              // moet ik nog een keer veranderen
                        $error = ' ';  //1 spatie anders werkt foutweergave index.php niet     //'Vul de datum correct in!';
                        return $default;
                    }
                } else {
                    $error = 'date';
                }
            } else {
                return $default;
            }

            break;
        case 'time':
            if (empty($var_to_check)) {
                return $default;
            }
            $arr_time = explode(':', $var_to_check);

            if (ereg('^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$', $var_to_check)) {
                $checked_name = $var_to_check;
                return $checked_name;
            } else {
                $error = 'Time is not correct';
                return $default;
            }



            break;

        default:
            $error = 'Error, no such validationtype';
    }
}

?>
