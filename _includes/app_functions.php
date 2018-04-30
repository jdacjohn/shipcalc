<?php
/**
 *  Application functions file for PHP.
 *  Created:    2018-04-18 by John Arnold <john@jdacsolutions.com>
 *  Last Updated:    2018-04-18 - John Arnold <john@jdacsolutions.com>
 *  https://jdacsolutions.com
 * 
 *  Description:
 *          Provides common  functions used by the application
 * 
 *  Version History:
 *          Version:  1.0.0
 *          Date:  2018-04-18 
 *          Summary:
 *                  Centralized common functions from other application PHP include files here.
 *          Date: 2018-04-23 - Added buildCarrierCriteria
 */

// Constants for newFieldArray()
define('THE_VALUE', 0);
define('THE_TYPE', 1);
define('THE_DEFINED_VALUE', 2);
define('THE_NOT_DEFINED_VALUE', 3);
define('FIELD_IS_ARRAY', 4);
define('ALLOW_NULL_VALUE', 5);

if (!defined('ARRAY_GLUE')) { define('ARRAY_GLUE', "||"); }
if (!defined('POSTBACK_PARAMETER_PREFIX')) define('','__postback__');

/**
 * Generates a redirect statement based on current state of output/headers
 *
 * @access private
 * @param mixed $targetURL Optional complete URL to redirect to. If not specified, returns false.
 * @param mixed $dataArray Optional array of name=>value parameters to pass along.
 * @param boolean $pauseBefore Optional flag. Useful for debugging - will force to redirect by manual form/POST.
 * @return null Result dependant on redirect method. May be a JavaScript redirect string if output has already started.
 *   Otherwise, PHP headers will be added directly. Processing will halt directly after in either case.
 */
function redirect($targetURL = false, $dataArray = false, $pauseBefore = false) {
    //printVarIfDebug($targetURL, getenv('gDebug'), 'TargetURL on entering redirect()');
    //printVarIfDebug($_SESSION, getenv('gDebug'), 'Session on entering redirect()');
    
    if (!strlen($targetURL)) { return false; }
    $search = '';
    if (strrpos($targetURL,'#') !== false) {
        list($targetURL,$search) = explode('#',$targetURL);
    }
    if (strlen($search)) {
        $search = '#'.rawurlencode($search);
    }
    if (strrpos($targetURL,'?') !== false) {
        list($targetURL,$extraParams) = explode('?',$targetURL);
        $extraParams = explode('&',$extraParams);
        foreach ($extraParams as $name => $value) {
            $dataArray[$name] = $value;
        }
    }
    if (is_array($dataArray)) {
   //     printVarIfDebug($dataArray, getenv('gDebug'), 'dataArray before merge');
        $dataArray = array_merge($dataArray);
   //     printVarIfDebug($dataArray, getenv('gDebug'), 'dataArray after merge');
    }

    if ($pauseBefore !== false) {
   //     printVarIfDebug('Sending to redirectByForm()', getenv('gDebug'), 'Debugging Form');
        redirectByForm($targetURL.$search,$dataArray,true,false);
    } else {
        $sep = '?';
        if ($dataArray !== false) {
            foreach ($dataArray as $name => $value) {
                $targetURL .= $sep.rawurlencode($name).'='.rawurlencode($value);
                $sep = '&';
            }
        }
        if (!headers_sent()) {
            session_write_close();
            header('Location: '.$targetURL.$search);
            exit();
        } else {
            echo "<script type=\"text/javascript\" language=\"javascript\">window.location.replace('".addslashes(htmlentities($targetURL.$search))."');</script>";
            session_write_close();
            exit();
        }
    }
}

/**
 * Outputs a form to use in request redirection. May submit automatically if browser allows.
 *
 * @access private
 * @param mixed $targetURL Complete URL to redirect to.
 * @param mixed $dataArray Optional array of name=>value parameters to write as input fields.
 * @param boolean $redirectByPost Optional flag. Useful for debugging - will force to redirect by manual form/POST instead of form/GET.
 * @param boolean $autoSubmit Optional flag. Adds an onload javascript directive to submit form automatically.
 * @return null Outputs an HTML form set and terminates script execution.
 */
function redirectByForm($targetURL, $dataArray = false, $redirectByPost = true, $autoSubmit = true) {
    if (!strlen($targetURL)) {
        return false;
    }
    $method = (($redirectByPost === true) ? 'post' : 'get');
    //printvarIfDebug($method, getenv('gDebug'), 'Form Method in redirectByForm()');
    $search = '';
    if (strrpos($targetURL,'#') !== false) {
        list($targetURL,$search) = explode('#',$targetURL);
    }

    if (strlen($search)) {
        $search = '#'.rawurlencode($search);
    }

    if (strrpos($targetURL,'?') !== false) {
        list($targetURL,$extraParams) = explode('?',$targetURL);
        $extraParams = explode('&',$extraParams);
        foreach ($extraParams as $name => $value) {
            $dataArray[$name] = $value;
        }
    }
    
    if (is_array($dataArray)) {
        $dataArray = array_merge($dataArray);
    }

    echo '<html><body' . (($autoSubmit == true) ? ' onload="document.forms[0].submit()"' : '') . '><form method="' . $method . '"' .
        ' action="' . htmlentities($targetURL.$search) . '">';
    writeHiddenFormFields($dataArray);
    echo '<input type="submit" name="'.POSTBACK_PARAMETER_PREFIX.'submit" value="Continue" /></form></body></html>';
    session_write_close();
    exit();
}

/**
 * Outputs values from the dataArray as hidden form field elements.
 *
 * @access private
 * @param array $dataArray Array of name=>value pairs to output. Nested arrays are processed recursively.
 * @param mixed $clean_array Optional parameter used to trim off array elements that start with specified string. Ignored if false.
 * @param string $id_prefix Optional string to append to beginning of element names when used as element ID attribute
 * @return null Outputs hidden HTML <input> fields directly
 */
function writeHiddenFormFields($dataArray, $clean_array = false, $id_prefix = '') {

    if (!is_array($dataArray)) { return false; }
    if (!sizeof($dataArray)) { return true; }

    if ($clean_array) {
        $dataArray = array_clean($dataArray, $clean_array);
    }
    foreach ($dataArray as $name => $value) {
        // repeat any POST params verbatim (except for the login page's internal POST params)
        // If this page is included by another page as a result of password timeout,
        // we want to preserve the GET or POST in progress

        // POST param name doesn't begin with $loginParamPrefix? Include it as a hidden form item.
        if (is_array($value)) {
            foreach ($value as $name2 => $value2) {
                writeHiddenFormFields(array("{$name}[{$name2}]" => $value2), $clean_array, $id_prefix);
            }
        } else {
            echo '<input type="hidden" name="'.htmlentities($name).'" id="'.htmlentities($id_prefix.preg_replace('/[^0-9a-z\-_]/i','_',$name)).'" value="'.htmlentities($value).'" />'."\n";
        }
    }
}

function newFieldArray($theValue, $theType = "text", $theDefinedValue = "", $theNotDefinedValue = "", $fieldIsArray = false, $allowNullValues = true) {
    /**
     * Summary:
     * creates a new field array. For use in insert/update forms for collecting form or database data
     * 
     * Usage:
     *      newFieldArray(string theValue, string theType, string theDefinedValue, string theNotDefinedValue, boolean fieldIsArray)
     *              theValue: The value of the field
     *              theType: a string representing the data type (same as those used in getSQLValueString)
     *              theDefinedValue: the value to use if theType == "defined" and theValue != ""
     *              theNotDefinedValue: the value to use if theType == "defined" and theValue == ""
     *              fieldIsArray: a boolean value indicating if the field uses an array of values
     *              allowNullValues: whether an empty value should return NULL or just an empty quoted string
     * 
     * Returns:
     *      an array with the following indexes: THE_VALUE, THE_TYPE, THE_DEFINED_VALUE, THE_NOT_DEFINED_VALUE, FIELD_IS_ARRAY, ALLOW_NULL_VALUES
     */
    return array($theValue, $theType, $theDefinedValue, $theNotDefinedValue, $fieldIsArray, $allowNullValues);
}

/**
 *  Checks the POST and GET collections for any values with the paramName key and returns the value.
 *  Usage 
 *      getParam(string paramName, boolean fieldIsArray, string the DefaultValue
 * @
 * @param   string    $paramName  - The name of the parameter to be found
 * @param   boolean   $fieldIsArray - Indicates if the parameter to be found is an array
 * @param   mixed     $theDefaultValue - value to return if the param is not found
 * @param   string  $theArrayGlue -     delimiter to be used for array explosion
 * @return  string
 */
function getParam($paramName, $fieldIsArray = false, $theDefaultValue = "", $theArrayGlue = ARRAY_GLUE) {
    $theField = "";
    if (isset($_POST[$paramName])) {
        $theField = $_POST[$paramName];
    } elseif (isset($_GET[$paramName])) {
        $theField = $_GET[$paramName];
    }

    if ($fieldIsArray) {
        //for multiple-item select fields
        if (!is_array($theField)) {
            if (trim($theField) === "") {
                $theField = (($theDefaultValue == "") ? array() : explode($theArrayGlue, $theDefaultValue));
            } else {
                $theField = explode($theArrayGlue, $theField);
            }
        }
    } elseif (is_array($theField)) {
        //the field that was requested is an array but was not requested as one, convert to string
        $theField = ((!sizeof($theField)) ? $theDefaultValue : explode($theArrayGlue, $theField));
    } elseif (trim($theField) == "") {
        $theField = $theDefaultValue;
    }
    return $theField;
}

function reset_session($limit_to_index = false) {
    // This 'admin' session array is not used anywhere.  If commenting out the switch doesn't cause issues in the system,
    //  this code needs to go away.
    //switch (strtolower($limit_to_index)) {
    //    case 'user':
    //        $_SESSION[SESSION_NAME]['user'] = array();
    //        break;
    //    case 'admin':
    //        $_SESSION[SESSION_NAME]['admin'] = array();
    //        break;
    //    default:
    //        $_SESSION[SESSION_NAME]['user'] = array();
    //        $_SESSION[SESSION_NAME]['admin'] = array();
    //        break;
    //}
    $_SESSION[SESSION_NAME]['user'] = array();
}

function form_error($error_message, $dom_id, $keywords = false) {
    if (!$error_message || !strlen(trim($error_message))) { return 'An unspecified error occurred.'; }
    if (!$dom_id || !strlen(trim($dom_id))) { return $error_message; }
    if (!$keywords || !strlen(trim($keywords))) { $keywords = $dom_id; }
    printvar($keywords, 'keywords');
    $new_dom_id = addslashes(htmlspecialchars($dom_id));
    printvar($new_dom_id, 'new_dom_id');
    //$msg = $error_message . "<strong><a href='javascript:void(0);' style='color: #FF0000' onclick='setFocus($new_dom_id)'>" . $new_dom_id . "</a></stong>";

    $msg =  preg_replace_callback('/('.preg_quote($keywords,'/').')/i',
            function ($matches) use  ($new_dom_id) {
                return "<strong><a href=\"javascript:void(0);\" style=\"color:#FF0000;\" onclick=\"setFocus('$new_dom_id', 1);\">$matches[0] </a></strong>";
            }, 
           $error_message);
    return $msg;    
}

function array_clean ($array, $todelete = false, $caseSensitive = false) {
    //removes elements from an array by comparing the value of each key
    foreach($array as $key => $value) {
        if(is_array($value)) {
            $array[$key] = array_clean($array[$key], $todelete, $caseSensitive);
        } else {
            if($todelete) {
                if($caseSensitive) {
                    if(strstr($key ,$todelete) !== false) {
                        unset($array[$key]);
                    }
                } else {
                    if(stristr($key, $todelete) !== false) {
                        unset($array[$key]);
                    }
                }
            } elseif (empty($key)) {
                unset($array[$key]);
            }
        }
    }
    return $array;
}

/**
 *  Summary
 *      Change the default values in the app global variable $carrierCriteria based on the instance variable values of the
 *      input parameters.
 * 
 * @param shipcalc/Location $startLoc
 * @param shipcalc/Location $endLoc
 */
function buildCarrierLocationCriteria($startLoc, $endLoc) {
    global $carrierCriteria;
    // Is either the start state or end state Alaska?
    if ($startLoc->getState() == 'Alaska' || $endLoc->getState() == 'Alaska') {
        $carrierCriteria['ak'] = 'Y';
    }
    // Is either the start or end state Hawaii?
    if ($startLoc->getState() == 'Hawaii' || $endLoc->getState() == 'Hawaii') {
        $carrierCriteria['hi'] = 'Y';
    }
    // Are both locations in the Lower 48?
    if ($startLoc->isLower48() || $endLoc->isLower48()) {
        $carrierCriteria['forty8'] = 'Y';
    }
    // Is this an overseas (international) move?
    if (!$startLoc->isDomestic() || !$endLoc->isDomestic()) {
        $carrierCriteria['ovs'] = 'Y';
    }
//    printVarIfDebug($carrierCriteria, getenv('gDebug'), 'Carrier Selection Critieria After Loc Build');
}

/**
 *  Print variables in a nice <pre></pre> format for debugging purposes.
 * @param type $var
 * @param type $label
 */
function printvar($var, $label="") {
    print "<pre style=\"border: 1px solid #999; background-color: #f7f7f7; color: #000; overflow: auto; width: auto; text-align: left; padding: 1em;\">" .
        ((strlen(trim($label))) ? htmlentities($label)."\n===================\n" : "" ) . htmlentities(print_r($var, TRUE)) . "</pre>";
}

/**
 *  Summary
 *          Outputs the object, $var if the  $debug parameter is true.  This method eliminates the need for so many if(0 { } 
 *          statements throughout the code and minimizes 'Too Many Nested If' warnings.
 * @param object $var
 * @param boolean $debug
 * @param string $label
 */

function printVarIfDebug($var, $debug, $label="") {
    if ($debug) {
        print "<pre style=\"border: 1px solid #999; background-color: #f7f7f7; color: #000; overflow: auto; width: auto; text-align: left; padding: 1em;\">" .
            ((strlen(trim($label))) ? htmlentities($label)."\n===================\n" : "" ) . htmlentities(print_r($var, TRUE)) . "</pre>";
    }
}

function require_login($returnURL = false) {
    if (!is_logged_in()) {
        //printVar($_SERVER['PHP_SELF']);
        $returnURL = (($returnURL) ? $returnURL : SITE_ROOT . '/' . ltrim($_SERVER['PHP_SELF'], '/'));
        //printVarIfDebug($returnURL, getenv('gDebug'), ' Return URL');
        intercept_request(SITE_ROOT . '/login.php', $returnURL);
    } else {
        //printVarIfDebug('Checking if more than 5 minutes have elapsed since last session init', getenv('gDeubg'), 'Session refresh check');
        //user is logged in -> reinitialize every few minutes just in case any user data has changed since
        if ((time() - $_SESSION[SESSION_NAME]['user']['last_initialized']) > (60*5) || getParam('refresh') == 1) { //check at least once every 5 minutes
            //printVarIfDebug('Reinitializing User', getenv('gDebug'), 'Send to -> reinit_user()');
            return reinit_user();
        }
    }
}

function is_logged_in() {
    if ( isset($_SESSION[SESSION_NAME]['user']['logged_in']) && $_SESSION[SESSION_NAME]['user']['logged_in'] == true) {
        // if(getenv('gDebug')) { printvar('true', 'is_logged_in returning:'); }
        return true;
    } else {
      //  if(getenv('gDebug')) { printvar('false', 'is_logged_in returning:'); }
        return false;
    }
}

function is_logged_in_user($user_id) {
    return (is_logged_in() && $user_id == $_SESSION[SESSION_NAME]['user']['id']);
}

function intercept_request($targetURL,$returnURL) {
    $targetURL = (($targetURL) ? $targetURL : 'http://'. filter_input(INPUT_SERVER, 'SERVER_NAME') . filter_input(INPUT_SERVER, 'PHP_SELF'));
    $returnURL = ((strlen($returnURL)) ? $returnURL : false);

    //if (getenv('gDebug')) {
    //    printvar($targetURL, 'intercept_request targetURL:');
    //    printvar($returnURL, 'intercept_request returnURL:');
    //}

    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
        $dataArray = array_clean(array_merge(filter_input_array(INPUT_GET), filter_input_array(INPUT_POST)), POSTBACK_PARAMETER_PREFIX);
        $dataArray[POSTBACK_PARAMETER_PREFIX.'return_method'] = 'post';
        if ($returnURL) {
            $dataArray[POSTBACK_PARAMETER_PREFIX.'return'] = $returnURL;
        }
        if (strpos(filter_input(INPUT_SERVER, 'CONTENT_TYPE'),'multipart/form-data') === 0 && isset($_FILES) && sizeof($_FILES) ) {
            //set error message to be displayed on the next page.
            $dataArray[POSTBACK_PARAMETER_PREFIX.'error'] = 'Your login expired before the form could be submitted. After signing in you will need to upload the file again.';
        }
        if (getenv('gDebug')) {
            $dataArray['debug'] = 1;
        //    printvar($dataArray, 'intercept_request dataArray:');
        //    printvar('redirecting by post', 'intercept_request:');
        }
        redirectByForm($targetURL,$dataArray,(!getenv('gDebug')));
    } else {
        $dataArray = filter_input_array(INPUT_GET);
        if ($returnURL) {
            $dataArray[POSTBACK_PARAMETER_PREFIX.'return'] = $returnURL;
        }
        if (getenv('gDebug')) {
            $dataArray['debug'] = 1;
        //    printvar($dataArray, 'intercept_request dataArray:');
        //    printvar('normal redirect', 'intercept_request:');
        }
        redirect($targetURL,$dataArray,getenv('gDebug'));
    }
}

/**
 *  
 */

