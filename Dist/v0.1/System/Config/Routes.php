<?php
	Router::Home(function(){
		Views::Init("Clicked",array(
			"Time" => "hello"
		));
	});
	Router::Post("click",array(
		"View" => "Clicked",
		"Data" => array(
			"Time" => date('d-m-Y H:i')
		)
	));
	Router::Any("posts",function() {
		Models::Init("PostsModel");
	});
	Router::Any("users",function() {
		Ood::Connect()->CreateTable("users",function($ood) {
			
		});
	});
	Router::AppError(Error::DatabaseConnectionFailed,"Failed to connect to the database");
?>