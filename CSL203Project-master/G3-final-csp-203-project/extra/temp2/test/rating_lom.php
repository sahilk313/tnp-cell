<?php

include ('../session.php');

if( isset($_SESSION['name']) )
    ;
else{
    header("Location:../login.php?message=Please+login+session+timed+out");
}

include('Databaseconnection.php');

global $connection;

if(!isset($_POST)){
    #echo "<script type='text/javascript'>alert('check');</script>";
}

foreach ($_POST as $key => $value) {
    ##echo $key;
    
    ##echo $value."<br>";
}

##echo $_POST['lom_id'];

 

# either return ratings, or process a vote
if (isset($_POST['fetch']) ) {
    
    $query = mysqli_query($connection,"SELECT SUM(lom_rate) as total_rate ,COUNT(person_id) as total_people from ratings where lom_id=".$_POST['lom_id']." group by person_id " );

    $cv_data = mysqli_fetch_array($query);


    if( $cv_data ) {
        $avg_rating=$cv_data['total_rate'];
        $no_votes=$cv_data['total_people'];
        $rounded_rating=round($avg_rating/$no_votes);
        echo "avg_rating=".$rounded_rating."&no_votes=".$no_votes;
    }
    else {
        echo "avg_rating=0&no_votes=0";
    } 


} 
else{
    
    $query = mysqli_query($connection,"SELECT * from ratings where lom_id=".$_POST['lom_id']." and person_id=".$_SESSION['person_id'] ) ;

    $person_data = mysqli_fetch_array($query);



     # Get the value of the vote
    preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
    $vote = $match[1];

    #echo $vote."<br>";

    # Update the record if it exists
    $message = "wrong answer";
    
    if( $person_data ) {

        #updating the vote by user.
        $query = mysqli_query($connection," update ratings set lom_rate=".$vote." where lom_id=".$_POST['lom_id']." and person_id=".$person_data['person_id'] ) ;
        if($query){
            #echo "insert".$connection->error;

        }else{
            #echo "error insert".$connection->error;
        }

    }
    # Create a new one if it does not
    else {
        #inserting the vote by user.
        $query = mysqli_query($connection,"insert into ratings(person_id,lom_id,lom_rate)  VALUES (".$_SESSION['person_id'].",".$_POST['lom_id'].",".$vote.");" );
        #echo "insert into ratings(person_id,lom_id,lom_rate)  VALUES (".$_SESSION['person_id'].",".$_POST['lom_id'].",".$vote.");";
        
        if($query){
            #echo "insert".$connection->error;
        }else{
            #echo "error insert".$connection->error;
        }

    }  
    $query = mysqli_query($connection,"SELECT SUM(lom_rate) as total_rate ,COUNT(person_id) as total_people from ratings where lom_id=".$_POST['lom_id']." group by person_id " );

    $cv_data = mysqli_fetch_array($query);

    get_ratings($cv_data);
}

function get_ratings($cv_data) {
    if( $cv_data ) {
        $avg_rating=$cv_data['total_rate'];
        $no_votes=$cv_data['total_people'];
        $rounded_rating=round($avg_rating/$no_votes);
        echo "avg_rating=".$rounded_rating."&no_votes=".$no_votes;
    }
    else {
        echo "avg_rating=0&no_votes=0";
    } 
}


function vote($connection,$cv_data,$person_data) {

    # Get the value of the vote
    preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
    $vote = $match[1];

    #echo $vote."<br>";

	# Update the record if it exists
	$message = "wrong answer";
	
	if( $person_data ) {

        #updating the vote by user.
        $query = mysqli_query($connection," UPDATE ratings set lom_rate=".$vote." where lom_id=".$_POST['lom_id']." and person_id=".$person_data['person_id'] ) ;
        if($query){
            #echo "insert".$connection->error;

        }else{
            #echo "error insert".$connection->error;
        }

	}
	# Create a new one if it does not
	else {
        #inserting the vote by user.
        $query = mysqli_query($connection,"INSERT INTO ratings(person_id,lom_id,lom_rate)  VALUES (".$person_data['person_id'].",".$_POST['lom_id'].",".$vote.")" );
        
        if($query){
            #echo "insert".$connection->error;
        }else{
            #echo "error insert".$connection->error;
        }

	}  

    get_ratings($cv_data);

}


?>
