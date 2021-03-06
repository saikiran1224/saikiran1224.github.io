<?php

require("connect.php");

header('Content-type: application/json');

 if($_SERVER["REQUEST_METHOD"] == "POST") {

	  $conn = getConn();

	  // Takes raw data from the request
	  $json = file_get_contents('php://input');

	  // Converts it into a PHP object
	  $data = json_decode($json);

	  $restaurantID = $data->restaurant_ID;
	  $category = $data->category;
	   
	  $sql = "SELECT * from items WHERE category = '$category' and Restaurant_ID = '$restaurantID' ";
	  $result = mysqli_query($conn,$sql);

	  $name = "SELECT Restaurant_Name from restaurants where Restaurant_ID = '$restaurantID' ";
	  $resultName = mysqli_query($conn,$name);
	  $rowName = mysqli_fetch_array($resultName, MYSQLI_ASSOC);
	          
	  if(! $result ) {
	      die('Could not get data: ' . mysqli_error());
	  }
	  
    if (mysqli_num_rows($result) > 0) {

       $res_array = array();

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

             $response = array();
             $response['Item_ID'] = $row['item_id'];
             $response['Item_Name'] = $row['Name'];
             $response['Type'] = $row['Type'];
             $response['Item_Price'] = $row['price'];
             array_push($res_array, $response);

         }

        echo json_encode(["status" => true,
                          "Restaurant_Name" => $rowName['Restaurant_Name'],
                          "Category" => $data->category,
                          "data" => $res_array]); 

    } else {
    echo json_encode(["status" => false, "msg" => "No Items Found"]);
    }
    
                                                        
 } else {

  echo json_encode(["status" => false, "msg" => "Unauthorized User" ]);
 }

?>