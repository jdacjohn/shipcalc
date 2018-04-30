<?php

/*
 * shipcalc
 * RequestMailer
 *
 * Description - Enter a description of the file and its purpose.
 *
 * Author:      John Arnold <john@jdacsolutions.com>
 * Link:           https://jdacsolutions.com
 *
 * Created:             Apr 23, 2018 10:48:40 PM
 * Last Updated:    Date 
 * Copyright            Copyright 2018 JDAC Computing Solutions All Rights Reserved
 */

namespace shipcalc;

/**
 * Description of RequestMailer
 *
 * @author John Arnold <john@jdacsolutions.com>
 */
class RequestMailer {
    private $activeRequest;
    private $activeCarriers = array();
    private $recipient = '';
    
    public function __construct($request, $carriers, $recipient) {
        $this->activeRequest = $request;
        $this->activeCarriers = $carriers;
        $this->recipient = $recipient;
    }
    public function getActiveRequest() {
        return $this->activeRequest;
    }

    public function getActiveCarriers() {
        return $this->activeCarriers;
    }

    public function getRecipient() {
        return $this->recipient;
    }

    public function setActiveRequest($activeRequest) {
        $this->activeRequest = $activeRequest;
    }

    public function setActiveCarriers($activeCarriers) {
        $this->activeCarriers = $activeCarriers;
    }

    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }

    // Send acknowledgment  to user
    public function sendAck() {
        
        $msg = $this->getHTMLReqMsg();
        // multiple recipients
        //$to  = 'aidan@example.com' . ', '; // note the comma
        // DEV ONLY - once fully tested and ready to deploy replace with commented line below
        $to = EMAIL_LEADS_NOTIFICATION;
        //$to = $this->getRecipient();
        // subject
        $subject = PROJECT_TITLE_SHORT . ' Information Request';
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'To: ' . PROJECT_TITLE_SHORT . ' Leads Contact <' . EMAIL_LEADS_NOTIFICATION . '>' . "\r\n";
        $headers .= 'From: ' . EMAIL_CONTACT . "\r\n";
        //$headers .= 'Cc: jdaceasttexas@gmail, webbheadz@gmail.com' . "\r\n";
        //$headers .= 'Bcc: john@arnoldsrule.com' . "\r\n";

        // Mail it
        if (mail($to, $subject, $msg, $headers)) {
            return $msg;
        } else {
            return "Error occurred while attempting to send mail";
        }
    }
    
    public function sendNotify() {
        
        $msg = $this->getHTMLMsgNotification();
        // multiple recipients
        //$to  = 'aidan@example.com' . ', '; // note the comma
        $to = EMAIL_LEADS_NOTIFICATION;
        // subject
        $subject = PROJECT_TITLE_SHORT . ' Lead Email Event Notification';
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'To: ' . PROJECT_TITLE_SHORT . ' Leads Contact <' . EMAIL_LEADS_NOTIFICATION . '>' . "\r\n";
        $headers .= 'From: ' . EMAIL_CONTACT . "\r\n";
        //$headers .= 'Cc: jdaceasttexas@gmail.com, webbheadz@gmail.com' . "\r\n";
        //$headers .= 'Bcc: john@arnoldsrule.com' . "\r\n";

        // Mail it
        if (mail($to, $subject, $msg, $headers)) {
            return $msg;
        } else {
            return "Error occurred while attempting to send mail";
        }
        
    }
    
    public function sendInfoRequest() {
        $msg = $this->getHTMLLeadMsg();
        // multiple recipients
        //$to  = 'aidan@example.com' . ', '; // note the comma
        // DEV ONLY - once fully tested and ready to deploy replace with commented line below
        $to = EMAIL_LEADS_NOTIFICATION;
        //$to = $this->getActiveCarriers()[0]['lead_email'];
        // subject
        $subject = PROJECT_TITLE_SHORT . ' Lead Email Event Notification';
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'To: ' . PROJECT_TITLE_SHORT . ' Leads Contact <' . EMAIL_LEADS_NOTIFICATION . '>' . "\r\n";
        $headers .= 'From: ' . EMAIL_CONTACT . "\r\n";
        //$headers .= 'Cc: jdaceasttexas@gmail.com, webbheadz@gmail.com' . "\r\n";
        //$headers .= 'Bcc: john@arnoldsrule.com' . "\r\n";

        // Mail it
        if (mail($to, $subject, $msg, $headers)) {
            return $msg;
        } else {
            return "Error occurred while attempting to send mail";
        }
    }
    
    public function getHTMLLeadMsg() {
        $msgbody = '
            
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>' . PROJECT_TITLE_SHORT . ' Vehicle Shipping Information Request</title>
    </head>
    <body yahoo>
        <table width="600" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="25%" align="left">
                                        <img src="http://shipcalc.jdac.ddns.net/images/logo.png" alt="ShipCalc Instant Vehicle Shipping Estimates" width="89" height="89" />
                                    </td>
                                    <td><h3>' . PROJECT_TITLE_SHORT. ' Vehicle Shipping Information Request</h3></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td colspan="3"><h4>' . PROJECT_TITLE_SHORT . ' Generated a New Vehicle Shipping Information Request</h4></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td colspan="3"><h4>Lead Provider: ' . PROJECT_TITLE_SHORT . '</h4></td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Carrier</strong></td>
                                    <td width="30%" align="left"><strong>Contact</strong></th>
                                    <td width="30%" align="left"><strong>Email</strong></th>
                                </tr>';
                                foreach($this->activeCarriers as $carrier) {
                                    $msgbody .= '<tr style="padding-bottom: 10px;">'
                                        . '<td width="40%" align="left">' . $carrier['name'] . '</td>'
                                        . '<td width="30%" align="left">' . $carrier['contact'] . '</td>' 
                                        . '<td width="30%" align="left">' . $carrier['lead_email'] . '</td>' 
                                . '</tr>';
                                }
        $msgbody .= '<tr style="padding-bottom: 10px;">
                                    <td colspan="3"><h4>Request Details:</h4></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                 <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5;">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5; padding: 2px;">
                                <tr>
                                    <td width="40%" align="left"><strong>Request Date</strong></td>
                                    <td width="30%" align="left"><strong>Request ID No</strong></th>
                                    <td width="30%" align="left"><strong>Contact Email</strong></th>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . date("Y-m-d H:i:s T") .  '</td>
                                    <td width="30%" align="left">' . $this->activeRequest->getRequestId()  . '</td>
                                    <tdwidth="30%" align="left">' . $this->getRecipient() . '</td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Vehicle Class</strong></td>
                                    <td width="30%" align="left"><strong>Vehicle Class Size</strong></td>
                                    <td width="30%" align="left"><strong>Vehicle Status<strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . $this->activeRequest->getVClassName()  . '</td>
                                    <td width="30%" align="left">' . $this->activeRequest->getVClassSizeName() . '</td>
                                    <td width="30%" align="left">Not Provided</td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Origin</strong></td>
                                    <td width="40%" align="left"><strong>Desitnation</strong></td>
                                    <td width="20%" align="left"><strong>Est. Miles</strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . 
                                        $this->activeRequest->getStartLoc()->getCity() . ', ' . $this->activeRequest->getStartLoc()->getState()  . 
                                        '  ' . $this->activeRequest->getStartLoc()->getZipCode() .
                                    '</td>
                                    <td width="40%" align="left">' . 
                                        $this->activeRequest->getEndLoc()->getCity() . ', ' . $this->activeRequest->getEndLoc()->getState()  .
                                        '  ' . $this->activeRequest->getEndLoc()->getZipCode() .
                                    '</td>
                                    <td width="20%" align="left">' . 
                                        $this->activeRequest->getTripLen() .
                                    '</td>
                                </tr>
                                <tr>
                                    <td width="25%" align="left"><strong>' . PROJECT_TITLE_SHORT . ' Estimate</strong></td>
                                    <td width="25%" align="left"><strong>Ant. Move Date</strong></td>
                                    <td width="50%" align="left"><strong>&nbsp;</strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="25%" align="left">$' . number_format(($this->activeRequest->getEstimatedBaseCost() + $this->activeRequest->getEstimatedSurcharge()),2,".", ",")  . '</td>
                                    <td width="25%" align="left">Not Provided</td>
                                    <td width="50%" align="left">&nbsp;</td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="100%" align="left" colspan="3">' . PROJECT_TITLE_SHORT . ' estimates include per/mile rates for vehicle class and size plus fuel surcharges.</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->               
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td>*Please contact the requester directly at the email address provided at the top of this notice.*</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td><h3>Thank you for choosing ' . PROJECT_TITLE_SHORT. ' for your vehicle shipping leads!</h3></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
        </table>
    </body>
</html>';            
        return $msgbody;
    }
    

    private function getHTMLMsgNotification() {
        $msgbody = '
            
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>' . PROJECT_TITLE_SHORT . ' Lead Email Event Notification</title>
    </head>
    <body yahoo>
        <table width="600" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="25%" align="left">
                                        <img src="http://shipcalc.jdac.ddns.net/images/logo.png" alt="ShipCalc Instant Vehicle Shipping Estimates" width="89" height="89" />
                                    </td>
                                    <td><h3>' . PROJECT_TITLE_SHORT. ' Email Lead Information Request Event Notification</h3></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td><h4>' . PROJECT_TITLE_SHORT . ' Generated a New Vehicle Shipping Information Request</h4></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td><h4>Request Details -</h4></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                 <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5;">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5; padding: 2px;">
                                <tr>
                                    <td width="40%" align="left"><strong>Request Date</strong></td>
                                    <td width="30%" align="left"><strong>Request ID No</strong></th>
                                    <td width="30%" align="left"><strong>Request Recipient</strong></th>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . date("Y-m-d H:i:s T") .  '</td>
                                    <td width="30%" align="left">' . $this->activeRequest->getRequestId()  . '</td>
                                    <tdwidth="30%" align="left">' . $this->getRecipient() . '</td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Vehicle Class</strong></td>
                                    <td width="30%" align="left"><strong>Vehicle Class Size</strong></td>
                                    <td width="30%" align="left">&nbsp;</td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . $this->activeRequest->getVClassName()  . '</td>
                                    <td width="30%" align="left">' . $this->activeRequest->getVClassSizeName() . '</td>
                                    <td width="30%" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Origin</strong></td>
                                    <td width="40%" align="left"><strong>Desitnation</strong></td>
                                    <td width="20%" align="left"><strong>Est. Miles</strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . 
                                        $this->activeRequest->getStartLoc()->getCity() . ', ' . $this->activeRequest->getStartLoc()->getState()  . 
                                        '  ' . $this->activeRequest->getStartLoc()->getZipCode() .
                                    '</td>
                                    <td width="40%" align="left">' . 
                                        $this->activeRequest->getEndLoc()->getCity() . ', ' . $this->activeRequest->getEndLoc()->getState()  .
                                        '  ' . $this->activeRequest->getEndLoc()->getZipCode() .
                                    '</td>
                                    <td width="20%" align="left">' . 
                                        $this->activeRequest->getTripLen() .
                                    '</td>
                                </tr>
                                <tr>
                                    <td width="25%" align="left"><strong>Base Estimate</strong></td>
                                    <td width="25%" align="left"><strong>Surcharges</strong></td>
                                    <td width="50%" align="left"><strong>Total Estimate</strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="25%" align="left">' . number_format($this->activeRequest->getEstimatedBaseCost(),2,".", ",")  . '</td>
                                    <td width="25%" align="left">' . number_format($this->activeRequest->getEstimatedSurcharge(),2,".",",") . '</td>
                                    <td width="50%" align="left">$' . number_format(($this->activeRequest->getEstimatedBaseCost() + $this->activeRequest->getEstimatedSurcharge()),2,".", ",") . '</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->               
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td><h4>Carrier Lead Details -</h4></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td>The following carriers were sent information request leads for this vehicle shipping estimate:</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                 <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5;">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5; padding: 2px;">
                                <tr>
                                    <td width="40%" align="left"><strong>Carrier Name</strong></td>
                                    <td width="30%" align="left"><strong>Contact</strong></th>
                                    <td width="30%" align="left"><strong>Carrier Lead Email</strong></th>
                                </tr>';
                                foreach($this->activeCarriers as $carrier) {
                                    $msgbody .= '<tr style="padding-bottom: 10px;">'
                                        . '<td width="40%" align="left">' . $carrier['name'] . '</td>'
                                        . '<td width="30%" align="left">' . $carrier['contact'] . '</td>' 
                                        . '<td width="30%" align="left">' . $carrier['lead_email'] . '</td>' 
                                . '</tr>';
                                }
          $msgbody .= '</table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td>*All Carrier Lead Information regarding this request has been logged in the ' . PROJECT_TITLE_SHORT . 
                                        ' system and can be viewed in the <a href="' . HOME_LINK . 'admin">Admin Dashboard</a>.*</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
        </table>
    </body>
</html>';            
 
        return $msgbody;
    }
    
    private function getHTMLReqMsg() {
        $msgbody = '
            
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Your ' . PROJECT_TITLE_SHORT . ' Information Request</title>
    </head>
    <body yahoo>
        <table width="600" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="25%" align="left">
                                        <img src="http://shipcalc.jdac.ddns.net/images/logo.png" alt="ShipCalc Instant Vehicle Shipping Estimates" width="89" height="89" />
                                    </td>
                                    <td><h3>Your ' . PROJECT_TITLE_SHORT. ' Vehicle Shipping Information Request Has Been Sent!</h3></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td><h4>Thank you for visiting ' . PROJECT_TITLE_SHORT . ' to request your vehicle shipping information!</h4></td>
                                </tr>
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td><h4>- This vehicle shipping estimate is valid for 30 days -</h4></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td><h4>Your Request Details</h4></td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                 <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5;">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5; padding: 2px;">
                                <tr>
                                    <td width="40%" align="left"><strong>Request Date</strong></td>
                                    <td width="30%" align="left"><strong>Request ID No</strong></th>
                                    <td width="30%" align="left"><strong>Your Email</strong></th>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . date("Y-m-d H:i:s T") .  '</td>
                                    <td width="30%" align="left">' . $this->activeRequest->getRequestId()  . '</td>
                                    <tdwidth="30%" align="left">' . $this->getRecipient() . '</td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Vehicle Class</strong></td>
                                    <td width="30%" align="left"><strong>Vehicle Class Size</strong></td>
                                    <td width="30%" align="left">&nbsp;</td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . $this->activeRequest->getVClassName()  . '</td>
                                    <td width="30%" align="left">' . $this->activeRequest->getVClassSizeName() . '</td>
                                    <td width="30%" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="40%" align="left"><strong>Origin</strong></td>
                                    <td width="40%" align="left"><strong>Desitnation</strong></td>
                                    <td width="20%" align="left"><strong>Est. Miles</strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="40%" align="left">' . 
                                        $this->activeRequest->getStartLoc()->getCity() . ', ' . $this->activeRequest->getStartLoc()->getState()  . 
                                        '  ' . $this->activeRequest->getStartLoc()->getZipCode() .
                                    '</td>
                                    <td width="40%" align="left">' . 
                                        $this->activeRequest->getEndLoc()->getCity() . ', ' . $this->activeRequest->getEndLoc()->getState()  .
                                        '  ' . $this->activeRequest->getEndLoc()->getZipCode() .
                                    '</td>
                                    <td width="20%" align="left">' . 
                                        $this->activeRequest->getTripLen() .
                                    '</td>
                                </tr>
                                <tr>
                                    <td width="25%" align="left"><strong>Your ' . PROJECT_TITLE_SHORT . ' Estimate</strong></td>
                                    <td width="25%" align="left"><strong>&nbsp;</strong></td>
                                    <td width="50%" align="left"><strong>&nbsp;</strong></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td width="25%" align="left">$' . number_format(($this->activeRequest->getEstimatedBaseCost() + $this->activeRequest->getEstimatedSurcharge()),2,".", ",")  . '</td>
                                    <td width="25%" align="left">&nbsp;</td>
                                    <td width="50%" align="left">&nbsp;</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->               
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td><h4>Your Request Has Been Sent</h4></td>
                                </tr>
                                <tr style="padding-bottom: 10px;">
                                    <td>' . PROJECT_TITLE_SHORT . ' has forwarded your request to the following Vehicle Shipping Specialists who will email you shortly.</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                 <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5;">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0" style="background-color: #E3E4E5; padding: 2px;">
                                <tr>
                                    <td width="40%" align="left"><strong>Vehicle Shipper</strong></td>
                                    <td width="40%" align="left"><strong>Website</strong></th>
                                    <td width="20%" align="left"><strong>Phone</strong></th>
                                </tr>';
                                foreach($this->activeCarriers as $carrier) {
                                    $msgbody .= '<tr style="padding-bottom: 10px;">'
                                        . '<td width="40%" align="left">' . $carrier['name'] . '</td>'
                                        . '<td width="40%" align="left">' . $carrier['site_url'] . '</td>';
                                    $phoneNum = ($carrier['toll_free'] != '') ? $carrier['toll_free'] : $carrier['phone'];
                                    $msgbody .=  '<td width="20%" align="left">' . $phoneNum . '</td>' 
                                . '</tr>';
                                }
          $msgbody .= '</table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
            <tr>
                <td>
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <![endif]-->
                            <table width="100%" align="left" cellpadding="20px 5px 20px 5px" cellspacing="0" border="0">
                                <tr style="padding-top: 15px; padding-bottom: 15px;">
                                    <td>Thank you once again for visitiing <a href="' . HOME_LINK . '">' . PROJECT_TITLE_SHORT . '</a>!</td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
                </td>
            </tr>
        </table>
    </body>
</html>';            
 
        return $msgbody;
    }

}
