<?php
require 'sheets_setup.php';

function read_template($filename) {
    ob_start();
    include $filename;
    return ob_get_clean();
}

//gets the string of the selected loan time
function getLoanTime() {
    $names = array('2hr','4hr','12hr','2 weeks');
    foreach($names as $c){
        if(isset($_POST[$c])) {
            return $c;
        }
    }

}

//gets the list of items on the form
function getValues(){
    $values = array();
    
    for($c = 0; $c < $_POST['num_rows']; $c++){
        array_push($values, array(
            $_POST['last'],
            $_POST['first'],
            $_POST['course_num'],
            $_POST['barcode'][$c],
            $_POST['title'][$c],
            $_POST['call_num'][$c],
            $_POST['author'][$c],
            getLoanTime(),
            $_POST['owner'][$c],
        ));
    }
    return $values;
}

//called if reccurence is chosen
/*function makeRecurrenceString() {
    $days = array_map('ucfirst', array_keys($_POST['days']));
    $word = "";
    foreach $days as $c
        $word = $word . c;
}*/
/**
 * Sends the actual email to the patron, confirming their submission.
 */
function send_email_to_patron() {
    $headers = "";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $body = read_template("patron_email_body.php");

    mail($_POST['email'],
        'Skeen Library Course Item Reservation',
        $body,
        $headers);
}

/**
 * Sends an email about the reservation request to the circulation desk.
 */
function send_email_to_circulation() {
    $headers = "";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $body = read_template("circulation_email_body.php");

    mail('nmtlib@gmail.com',
        'Course Reservation Request',
        $body,
        $headers);
}

function create_google_sheet_entry(){
    // Get the API client and construct the service object.
    $client = getClient();
    $service = new Google_Service_Sheets($client);

    // Prints the names and majors of students in a sample spreadsheet:
    $spreadsheetId = '1mcvjpbbYtlTnErwic3pbMQoUDO4Fkmy_KRj7_xS1g0o';
    
    //NOTE: REPLACE RANGE NAME WITH CORRECT SHEET NAME 
    $range = $_POST['semester'].' '.$_POST['year'].' Contacts!A1';
    $valueInputOption = 'USER_ENTERED';
    
    $values = array(
    array(
        $_POST['last'],
        $_POST['first'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['course_num'],
        $_POST['course_name'],
        
    ),
    // Additional rows ...
    );
    
    $body = new Google_Service_Sheets_ValueRange(array(
      'values' => $values
    ));
    $params = array(
      'valueInputOption' => $valueInputOption  
    );
    
    $result = $service->spreadsheets_values->append($spreadsheetId, $range,
        $body, $params);
        
    //push the table information next
    
    $range = $_POST['semester'].' '.$_POST['year'].' Reservations!H1';
    $valueInputOption = 'USER_ENTERED';
    
    $loanTime = getLoanTime();

    $values = getValues();
    
    /*$values = array(
    array(
        $_POST['last'],
        $_POST['first'],
        $_POST['course_num'],
        $_POST['barcode'][0],
        $_POST['title'][0],
        $_POST['call_num'][0],
        $_POST['author'][0],
        $loanTime,
        $_POST['owner'][0],
        
    ),
    array(
        $_POST['last'],
        $_POST['first'],
        $_POST['course_num'],
        $_POST['barcode'][1],
        $_POST['title'][1],
        $_POST['call_num'][1],
        $_POST['author'][1],
        $loanTime,
        $_POST['owner'][1],
    ),
    // Additional rows ...
    );*/
    
    $body = new Google_Service_Sheets_ValueRange(array(
      'values' => $values
    ));
    $params = array(
      'valueInputOption' => $valueInputOption  
    );
    
    $result = $service->spreadsheets_values->append($spreadsheetId, $range,
        $body, $params);
}

/**
 * Validates the form submission, returning 'true' if
 * the submission is valid and the request can be saved.
 *
 * @return bool whether or not the patron's submission is acceptable
 */
function validate_submission() {
    // FIXME: Don't trust the user's Javascript so much
    // We probably shouldn't trust that the Javascript
    // prevented the user from doing anything they
    // should not have been able to do here, but right now
    // that's what we do.
    return true;
}

/**
 * Explains to the patron why their submission is not valid.
 */
function complain_loudly_to_the_patron() {

}

/*****************************************************************************/
/*                                                                           */
/*         T H E    A C T U A L   S U B M I S S I O N   H A N D L E R        */
/*                                                                           */
/*****************************************************************************/
function handle_submission() {
    // if the submission is valid, keep it
    if (validate_submission()) {
        // first make the google calendar entry
        
        //then record on sheets
        create_google_sheet_entry();

        // then email circulation
        //send_email_to_circulation();

        // then email the patron
        //send_email_to_patron();
    }

    // if the submission is not valid
    else {
        // tell the patron why
        complain_loudly_to_the_patron();
    }
}

// handle the user's submission
handle_submission();

echo "<h4>Thank you for your submission!</h4>";
echo "<h5>Redirecting you back to the home page!<br> if nothing happens, click <a href='index.html'>here</a></h5>";
header('Refresh: 3;url=index.html');
