<?php

include ('session.php');

$doc_added=false;

if(!isset($_SESSION['person_id'])){
    header("Location:login.php?message=Please+login+session+timed+out");
}

if (isset($_POST['cv_letter_id']) ) {

   #echo "fsag"; 

  include('./test/Databaseconnection.php');

  $cv_letter_id   = mysqli_real_escape_string($connection,$_POST['cv_letter_id']);

  $query ="Select * from cv_letter  where cv_letter_id=".$cv_letter_id;

  #echo $cv_letter_id;

  $strSQL=mysqli_query($connection,$query);

  if( $strSQL ){
      $executed=true;
      #echo "<br>"."exec<br>";
    }
    else{
      #echo "<br>"."Error".$connection->error;
    }

  $resultset=mysqli_fetch_array($strSQL);
  #print_r($resultset);

  if( mysqli_num_rows($strSQL)!=1){

    $message = "cannot execute delete no such id !! ";
    #echo "<br>".$message;

  }
  else{

	$fullPath = $resultset['file_link'];
	$title=$resultset['title'];

	echo "file=".$fullPath."&cv_letter_id=".$cv_letter_id;

	}
}
?>