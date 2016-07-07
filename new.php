<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'include/default.inc.php';

if(isset($_GET['action'])) {
  	switch($_GET['action']) {
		case 'search':
		    search();
		    break;
        case 'submit':
		    submit();
		    break;
		default:
			showForm();
   
   
  }

  exit;
} else {
    
    showForm();
}

function showForm() {
    global $obj_smarty;
    
    // $obj_smarty->assign('results', $arr_return);
     //$obj_smarty->assign('q', $frm_submitted['sq']);


    $obj_smarty->display(FULLCAL_DIR.'/view/new.tpl');
}

function submit() {
    global $error;
    global $obj_smarty;
    global $obj_db;
    
    $arr_submit 		= array(
        array('user_id',    	'int',   	true, 	''),
        array('title',    		'string',   true, 	''),
     // and all the submitted values  
    );

    $frm_submitted = validate_var($arr_submit);
    
    // save in the database
    
    Events::insertEvent($frm_submitted);
    
    // send message if success
        
    global $obj_smarty;
    
    // $obj_smarty->assign('results', $arr_return);
     //$obj_smarty->assign('q', $frm_submitted['sq']);


    $obj_smarty->display(FULLCAL_DIR.'/view/new.tpl');
}

function search() {
       
    global $error;
    global $obj_smarty;
    global $obj_db;
    
    $arr_submit 		= array(
        array('keyword',    		'string',   	true, 	''),
       
    );

    $frm_submitted = validate_var($arr_submit);
    
    $str_query = 'SELECT e.* FROM events e WHERE title LIKE  "%'.$frm_submitted['keyword'].'%" ';

   // $str_query .= ' ORDER BY date_start';

    $obj_result = mysqli_query($obj_db, $str_query);

    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
        $arr_events[] = $arr_line;
    }
    
    $obj_smarty->assign('keyword', $frm_submitted['keyword']);
    
    if(count($arr_events) == 1) {
        // no need to show the results, go to form right away
        $obj_smarty->assign('events', $arr_events[0]);
        $obj_smarty->display(FULLCAL_DIR.'/view/new.tpl');
    } else {
        // show the search results
        $obj_smarty->assign('events', $arr_events);
        $obj_smarty->display(FULLCAL_DIR.'/view/found.tpl');
    }
    

    
}

 