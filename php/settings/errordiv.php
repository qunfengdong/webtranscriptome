<?php

function errordiv($msg){
  $divstart = '<div class="box box-danger"><div class="box-header error"><h3 class="box-title"><i class="fa fa-times-circle"></i> ERROR</h3></div><div class="box-body">';
  $divend = '<br><br>Copy paste the above message to system administrator</div></div>';
  $html = $divstart . $msg . $divend;
  return json_encode($html);
}

function warningdiv($msg){
  $divstart = '<div class="box box-danger"><div class="box-header error"><h3 class="box-title"><i class="fa fa-warning"></i> ERROR</h3></div><div class="box-body">';
  $divend = '</div></div>';
  $html = $divstart . $msg . $divend;
  return json_encode($html);
}

function errordivmsg($msg){
  $divstart = '<div class="box box-danger"><div class="box-header error"><h3 class="box-title"><i class="fa fa-times-circle"></i> ERROR</h3></div><div class="box-body">';
  $divend = '</div></div>';
  $html = $divstart . $msg . $divend;
  return $html;
}

function callouterror($msg){
  $html = '<div class="callout callout-danger"><h4>ERROR</h4><p>'.$msg.'</p></div>';
  return $html;
}

function callouterrorjson($msg){
  $html = callouterror($msg);
  return json_encode($html);
}

function calloutwarning($msg){
  $html = '<div class="callout callout-warning"><p>'.$msg.'</p></div>';
  return $html;
}

function calloutwarningjson($msg){
  $html = calloutwarning($msg);
  return json_encode($html);
}
?>