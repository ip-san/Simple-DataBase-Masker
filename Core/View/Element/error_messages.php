<?php 
    $errorList = getErrorMsg();
    element('messages', array(
                    'type' => 'error',
                    'mesageList' => $errorList));