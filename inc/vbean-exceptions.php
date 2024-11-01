<?php

set_error_handler("vbean_error_mailer");

function vbean_error_mailer($errno, $errstr, $errfile, $errline)
{
    $backtrace = debug_backtrace();
    $lasterror = error_get_last();

    $reportinglevel = error_reporting();
    if (empty($reportinglevel) || $reportinglevel == 0) {
        return;
    }
    $msg = '';
    $survivedfilter = TRUE;
    // check repetition
    $thiserror = $errno . $errstr . $errfile . $errline;
    if ($lasterror != null && $lasterror['type'] == $errno && $lasterror['message'] == $errstr && $lasterror['file'] == $errfile && $lasterror['line'] == $errline) {
        return;
    }
    $lasterror = get_option('vbean_errormailer_lasterror');
    $lasterrorcount = (int)get_option('vbean_errormailer_lasterrorcount');
    $lasterrortime = (int)get_option('vbean_errormailer_lasterrortime');
    $lasterrorstart = (int)get_option('vbean_errormailer_lasterrorstart');
    $thiserrortime = time();
    if ($thiserrortime - $lasterrortime < 3) {
        update_option('vbean_errormailer_lasterrorcount', $lasterrorcount + 1);
        return;
    }
    if ($thiserror == $lasterror) {
        update_option('vbean_errormailer_lasterrorcount', $lasterrorcount + 1);
        $since = $thiserrortime - $lasterrortime;
        $alltime = $thiserrortime - $lasterrorstart;
        if ($since < 10) {
            // don't send
            update_option('vbean_errormailer_lasterrortime', $thiserrortime);
            $survivedfilter = false;
        } else {
            update_option('vbean_errormailer_lasterrortime', $thiserrortime);
            $msg = 'This error has occurred ' . $lasterrorcount . ' times over the last ' . $alltime . ' seconds.' . PHP_EOL;
            if ($alltime > 4) {
                $msg .= 'Consider adding "`' . $errfile . '`" with line number: "`' . $errline . '`" to your exception list in errormailer settings.' . PHP_EOL;
            }
        }
    } else {
        update_option('vbean_errormailer_lasterror', $thiserror);
        update_option('vbean_errormailer_lasterrorcount', 1);
        update_option('vbean_errormailer_lasterrortime', $thiserrortime);
        update_option('vbean_errormailer_lasterrorstart', $thiserrortime);
    }

    try {
        $subj = '' . get_option('vbean_errormailer_subject');
    } catch (Exception $ex) {
        $subj = '{errortype}';
    }

    if ($subj == '') {
        $subj = '{errortype}';
    }
    $exemptionlist = get_option('vbean_errormailer_exemptions') . '' == '' ? false : get_option('vbean_errormailer_exemptions');
    // iterate through exemptions to see if we should ignore error
    if ($exemptionlist != FALSE) {

        try {

            $exemptions = explode(PHP_EOL, $exemptionlist);
            foreach ($exemptions as $exemption) {
                $thisrow = explode(',', $exemption);


                if (($thisrow[0] == $errfile || \VanillaBeans\vbean_endsWith($errfile, $thisrow[0])) || \VanillaBeans\vbean_startsWith($thisrow[0], $errstr)) {
                    if (count($thisrow) > 1) {
                        if (is_int($thisrow[1])) {
                            if ($thisrow[1] == $errline) {
                                $survivedfilter = FALSE;
                            }
                        } else {
                            $survivedfilter = FALSE;
                        }
                    }
                }
            }

        } catch (Exception $ex) {

        }
    }

    if ($survivedfilter) {

        $subj = str_replace('{errornumber}', $errno, $subj);
        $subj = str_replace('{errorline}', $errline, $subj);
        $subj = str_replace('{errorpage}', $errfile, $subj);
        try {
            $subj = str_replace('{siteurl}', get_option('siteurl') . '', $subj);
        } catch (Exception $ex) {
            $subj = str_replace('{siteurl}', '', $subj);
        }
        try {
            //get_current_site()
            $subj = str_replace('{sitename}', get_option('blogname') . '', $subj);
        } catch (Exception $ex) {
            $subj = str_replace('{sitename}', '', $subj);
        }


        switch ($errno) {


            case E_ERROR:
            case E_USER_ERROR:
                $send = get_option('vbean_errormailer_fatals');
                $subj = str_replace('{errortype}', 'Fatal Error', $subj);
                $msg .= "<b>ERROR</b> [$errno] $errstr<br />\n";
                $msg .= "  Fatal error on line $errline in file $errfile";
                $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
//                $msg.=  "Aborting...<br />\n";
//                exit(1);
                break;

            case E_WARNING:
            case E_USER_WARNING:
                $send = get_option('vbean_errormailer_warnings');
                $subj = str_replace('{errortype}', 'Warning', $subj);
                $msg .= "<b>WARNING</b> [$errno] $errstr<br />\n";
                $msg .= "  Warning on line $errline in file $errfile";
                $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $send = get_option('vbean_errormailer_notices');
                $subj = str_replace('{errortype}', 'Notice', $subj);
                $msg .= "<b>NOTICE</b> [$errno] $errstr<br />\n";
                $msg .= "  Notice on line $errline in file $errfile";
                $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                break;

            case E_PARSE:
                $send = get_option('vbean_errormailer_parse');
                $subj = str_replace('{errortype}', 'Parse Error', $subj);
                $msg .= "<b>PARSE ERROR</b> [$errno] $errstr<br />\n";
                $msg .= "  Notice on line $errline in file $errfile";
                $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";

                break;
            default:
                try {
                    $exempterrnos = '' . get_option('vbean_errormailer_excludetypes');
                    $numbers = explode(',', $exempterrnos);
                    foreach ($numbers as $number) {
                        $number = trim($number);
                        if (is_numeric($number)) {
                            $int = intval($number);
                            if ($int == $errno) {
                                $survivedfilter = FALSE;
                            }
                        }
                    }
                } catch (Exception $ex) {
                    $msg .= "Additional exeption: " . $ex;
                }


                $subj = str_replace('{errortype}', 'Unspecified', $subj);
                $send = get_option('vbean_errormailer_fatals');
                $msg .= "Uncaught error type: [$errno] $errstr<br />\n";
                try {
                    $msg .= "  Uncaught on line $errline in file $errfile";

                } catch (Exception $ex) {
                    $msg .= " Error line or file not available";
                }
                try {
                    $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                } catch (Exception $ex) {
                    $msg .= " PHP version not available";
                }
                break;
        }


        $bt = '';

        if ($send && $survivedfilter) {


//                try{
//                $trace = array_reverse(debug_backtrace());
//                array_pop($trace);
//                if(php_sapi_name() == 'cli') {
//                    $bt.= 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
//                    foreach($trace as $item)
//                        $bt.=  '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()' . "\n";
//                } else {
//                    $bt.=  '<p class="error_backtrace">' . "\n";
//                    $bt.=  '  Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
//                    $bt.=  '  <ol>' . "\n";
//                    foreach($trace as $item)
//                        $bt.=  '    <li>' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()</li>' . "\n";
//                    $bt.=  '  </ol>' . "\n";
//                    $bt.=  '</p>' . "\n";
//                }
//                if(ini_get('log_errors')) {
//                    $items = array();
//                    foreach($trace as $item)
//                        $items[] = (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()';
//                    $message = 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ': ' . join(' | ', $items);
//                    error_log($message);
//                }        
//                $msg.=$bt;
//                }catch(Exception $ex){
//                    
//                }
            update_option('vbean_errormailer_lasterrortime', time());
            update_option('vbean_errormailer_lasterrorcount', 1);
            @vbean_debugMail($subj, $msg, $backtrace);
        }


    }


    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    } else {
        return false;
    }


}

function vbean_debugMail($subject, $string, $backtrace = NULL)
{
    try {
        $chunk = get_option('vbean_errormailer_recipients');
        if (!empty($chunk)) {
            $recips = explode(",", $chunk);
            if (!empty($backtrace)) {
                ob_start();
                var_dump($backtrace);
                $string = ob_get_clean();
                $string .= PHP_EOL . PHP_EOL . $string;
            }
            foreach ($recips as $recip) {
                $weechunks = explode("\n", $recip);
                $headers = "MIME-Version: 1.0\n" .
                    "From: " . get_option('admin_email') . "\n" .
                    "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
                foreach ($weechunks as $wee) {
                    try {
                        //echo 'option: '.get_option('vbean_errormailer_recipients').' recip='.$wee.',  subject='.$subject.', body='.$string;
                        if (function_exists('wp_mail')) {
                            wp_mail($wee, $subject, $string, $headers);
                        } else {
                            @mail($wee, $subject, $string, $headers);

                        }
                        // die;
                    } catch (Exception $ex) {
                        return;
                        // echo 'mail failed';
                        // die;

                    }
                }

            }

        }
    } catch (Exception $ex) {
        return;
    }
    $slackvalid = (get_option('vbean_slack_hooker_setupvalid') && function_exists('\VanillaBeans\SlackHooker\vbean_slackhooker'));

    $useslack = get_option('vbean_errormailer_useslack');
    $slackicon = get_option('vbean_errormailer_slackicon');
    if (!empty($slackvalid) && $useslack) {
        // send it to slack
        try {
            if (!empty($slackicon)) {
                \VanillaBeans\SlackHooker\vbean_slackhooker(strip_tags($subject . PHP_EOL . strip_tags($string)), get_option('vbean_errormailer_slackchannel'), get_option('vbean_errormailer_slackfrom'), get_option('vbean_errormailer_slackicon'));
            } else {
                \VanillaBeans\SlackHooker\vbean_slackhooker(strip_tags($subject . PHP_EOL . strip_tags($string)), get_option('vbean_errormailer_slackchannel'), get_option('vbean_errormailer_slackfrom'));
            }

        } catch (Exception $ex) {
            return;
        }
    }

}        
   

