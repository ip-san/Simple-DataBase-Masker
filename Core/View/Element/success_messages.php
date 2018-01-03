<?php 
    $successList = getSuccessMsg();
    element('messages', array(
                    'type' => 'success',
                    'mesageList' => $successList));