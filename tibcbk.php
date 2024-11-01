<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>tibit callback and tib-token processing</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.16.1/URI.js"></script>

</head>

<body>

<p>
    Thanks for tibbing!
</p>

<p>
    Your tib has been processed. This window should have closed itself.  Have you got javascript enabled?
</p>

<!-- Static message in the case that the user doesn't have javascript enabled -->

<?php

function process_tib_token(){
    $token = $_GET['tibtok'];
    $token = base64_decode($token);
    $token_content = json_decode($token, true);
    return $token_content;
}

function set_QTY($token_content){
    $options = get_option('tibdit_options');

    if(gettype($token_content['QTY']) == 'integer'){
        $options['TIB_QTYs'][$token_content['SUB']] = $token_content['QTY'];
    }

    update_option('tibdit_options', $options);
}

/* Grabs the tib token from the GET params, decodes it, and returns it as an array to be used */
$token_content = process_tib_token();

/* Including wp-load to give us access to Wordpress functions and save our data in wp_options */
$wordpress_path = realpath(dirname(dirname(dirname(dirname($_SERVER["SCRIPT_FILENAME"])))));
require_once($wordpress_path . "/wp-load.php");

/* Setting the item within tibit_options['TIB_QTYs'][SUB] to the value of QTY, with SUB and QTY corresponding
to the QTY and SUB values retrieved from the tib token */
set_QTY($token_content);

?>

<script>

    //developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API
    function storageAvailable(type) {
        try {
            var storage = window[type],
                x = '__storage_test__';
            storage.setItem(x, x);
            storage.removeItem(x);
            return true;
        }
        catch(e) {
            return false;
        }
    }

    if(storageAvailable('localStorage')){
        /* If localStorage is available, we set a local storage item with the key "bd-subref-SUB" with the value contained
         * within the ISS value retrieved from the tib token. This can then be read by tib.js when the CBK is closed
         * and all relevant functions are triggered as a result */
        SUB = '<?php echo $token_content['SUB'] ?>';
        ISS = '<?php echo $token_content['ISS'] ?>';
        QTY = '<?php echo $token_content['QTY'] ?>';

        localStorageJson = {'ISS' : ISS, 'QTY' : QTY};

        localStorage.setItem("bd-subref-" + SUB, JSON.stringify(localStorageJson));

    }
    else {
        throw( "bd: cannot access localStorage");
    }

    /* Tries to close the window, throws an error message if unable to */
    try {
        var x=window.open('','_self'); x.close();
    }
    catch (ex) {
        console.error( "bd: attempt to close window failed");
    }

</script>
