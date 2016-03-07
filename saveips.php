<?php

require_once('../../config.php');

if (!empty($_FILES['ipslist']['tmp_name'])) {
    $filedata = file_get_contents($_FILES['ipslist']['tmp_name']);  //$_FILES['csvfile']['tmp_name']
    $filedata = str_replace("\r", "\n", $filedata);
    $filedata = str_replace("\n\n", "\n", $filedata);
    
    if ($filedata) $filedata = explode("\n", $filedata);

    $filedata_ = array();
    
    foreach ($filedata as $filedatavalue){
      if (!empty($filedatavalue)) {
        $filedatavalue = trim($filedatavalue);
        $filedatavalue = str_replace(array("	", ","), ";", $filedatavalue);
        $filedatavalue = explode(";", $filedatavalue);
        
        $filedatavalue_ = array();
        
        foreach($filedatavalue as $v)
          $filedatavalue_[] = $v;
        
        
        $filedata_[]   = $filedatavalue_;
      }
    }
    
    $DB->delete_records("attendance_ips");
    
    foreach($filedata_ as $k => $v) {
        $add = new stdClass;
        $add->ip = $v[0];
        $add->location = $v[1];
        
        $DB->insert_record("attendance_ips", $add);
    }
    
    echo "Done";
    
} else {
    $str = '<form action="'.$CFG->wwwroot.'/mod/attendance/saveips.php" method="post" enctype="multipart/form-data">';
    $str .= '<input type="hidden" name="MAX_FILE_SIZE" value="300000" />';
    $str .= 'CSV ips list: <input name="ipslist" type="file" />';
    $str .= '<input type="submit" value="Send File" />';
    $str .= '</form>';
    
    echo $str;
}
