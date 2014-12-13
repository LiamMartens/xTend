<?php
	class PostsModel extends Ood
	{
		public function __construct() {
			//Connect to database
			$this->Connect();
			//Select table
			$this->Table("posts");
			
			var_dump($this);
		}
	}
?>