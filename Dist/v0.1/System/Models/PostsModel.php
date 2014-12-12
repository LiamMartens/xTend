<?php
	class PostsModel extends Ood
	{
		public function __construct() {
			//Connect to database
			$this->ConnectModel();
			//Select table
			$this->Table("posts");
		}
	}
?>