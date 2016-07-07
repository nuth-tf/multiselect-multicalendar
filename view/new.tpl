<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Add New Guest</title>
        <link rel='stylesheet' type='text/css' href='<?smarty $smarty.const.EXTERNAL_URL ?>/jquery/jqueryui/1.8.17/jquery-ui.css' />
		
         <script type="text/javascript" src="jscolor.js"></script>
        
         <script src="<?smarty $smarty.const.EXTERNAL_URL ?>/jquery/jquery.1.11.1.min.js"></script>
		 <script src="<?smarty $smarty.const.EXTERNAL_URL ?>/jquery/jquery-ui.1.11.1.min.js" type="text/javascript" charset="utf-8"></script>

         <link rel="stylesheet" type="text/css" href="style.css">
         <script>
         $(function() {
           $( "#datepicker" ).datepicker({dateFormat : 'yy-mm-dd'});
           $( "#datepick2" ).datepicker({dateFormat : 'yy-mm-dd'});
           $( "#datepick3" ).datepicker({dateFormat : 'yy-mm-dd'});
         } 
         );
         </script>
         <style>
             
.form-style-6 {
    background: #f7f7f7 none repeat scroll 0 0;
    font: 95% Arial,Helvetica,sans-serif;
    margin: 10px auto;
    max-width: 400px;
    padding: 16px;
}
.form-style-6 h1 {
    background: #43d1af none repeat scroll 0 0;
    color: #fff;
    font-size: 140%;
    font-weight: 300;
    margin: -16px -16px 16px;
    padding: 20px 0;
    text-align: center;
}
.form-style-6 input[type="text"], .form-style-6 input[type="date"], .form-style-6 input[type="datetime"], .form-style-6 input[type="email"], .form-style-6 input[type="number"], .form-style-6 input[type="search"], .form-style-6 input[type="time"], .form-style-6 input[type="url"], .form-style-6 textarea, .form-style-6 select {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #ccc;
    box-sizing: border-box;
    color: #555;
    font: 95% Arial,Helvetica,sans-serif;
    margin-bottom: 4%;
    outline: medium none;
    padding: 3%;
    transition: all 0.3s ease-in-out 0s;
    width: 100%;
}
.form-style-6 input[type="text"]:focus, .form-style-6 input[type="date"]:focus, .form-style-6 input[type="datetime"]:focus, .form-style-6 input[type="email"]:focus, .form-style-6 input[type="number"]:focus, .form-style-6 input[type="search"]:focus, .form-style-6 input[type="time"]:focus, .form-style-6 input[type="url"]:focus, .form-style-6 textarea:focus, .form-style-6 select:focus {
    border: 1px solid #43d1af;
    box-shadow: 0 0 5px #43d1af;
    padding: 3%;
}
.form-style-6 input[type="submit"], .form-style-6 input[type="button"] {
    background: #43d1af none repeat scroll 0 0;
    border-bottom: 2px solid #30c29e;
    border-style: none none solid;
    box-sizing: border-box;
    color: #fff;
    padding: 3%;
    width: 100%;
}
.form-style-6 input[type="submit"]:hover, .form-style-6 input[type="button"]:hover {
    background: #2ebc99 none repeat scroll 0 0;
}

         </style>
    </head>
    <body>
        <div align="center"><h2>New Booking For Glowrey House</h2></div> 


 <div class="form-style-6">
    <form action="<?smarty $smarty.const.FULLCAL_URL ?>/new.php?action=search" method="post">
        <input type="text" name="keyword" value="<?smarty $keyword|default:'Search for Name' ?>" onclick="this.value='';">
        <input type="submit" name="search" value="Search">
    </form>
</div>
  
 <form action="<?smarty $smarty.const.FULLCAL_URL ?>/new.php?action=submit" method="post">
 <div class="form-style-6">
     <input type="hidden" name="user_id" value="<?smarty $events.user_id ?>" />
     <strong>Names: *</strong> <input type="text" name="title" value="<?smarty $events.title ?>" /><br/>
 <strong>Location: *</strong> <input type="text" name="location" value="<?smarty $events.location ?>" /><br/>
 <strong>Description: *</strong> <input type="text" name="description" value="<?smarty $events.description ?>" /><br/>
 <strong>User ID: *</strong> <input type="hidden" name="user_id" value="1000000" /><br/>
 <strong>AllDay: *</strong> <input type="hidden" name="allDay" value="1" /><br/>
 <strong>time_start: *</strong> <input type="hidden" name="time_start" value="00:00:00" /><br/>
 <strong>time_end: *</strong> <input type="hidden" name="time_end" value="00:00:00" /><br/>
 <strong>repeating_event_id: *</strong> <input type="hidden" name="repeating_event_id" value="0" /><br/>
 <strong>Create Date: <strong> <input type="text" id="datepick3" name="create_date" value="Year/Month/Day" /></p>
 <p>Booking Date: <input type="text" id="datepicker" name="date_start" value="Year/Month/Day" /></p>
 <p>Date End: <input type="text" id="datepick2" name="date_end" value="Year/Month/Day" /></p>
 <p>Click here: <input class="color {hash:true}" name="color" value="66ff00"></p>
 Room Number:<select name="calendar_id">
 <option value="1">Room1</option>
 <option value="2">Room2</option>
 <option value="3">Room3</option>
 <option value="3">Room4</option>
 <option value="3">Room5</option>
 <option value="3">Room6</option>
 <option value="3">Room7</option>
 <option value="3">Room8</option>
 <option value="3">Room9</option>
 </select><br />
 <p>* required</p>
 <input type="submit" name="submit" value="Submit">
 </div>
 </form> 
    </body>
</html>
