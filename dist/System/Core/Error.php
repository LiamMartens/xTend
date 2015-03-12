<?php
	namespace xTend
	{
		abstract class Error
		{
			const NotFound = "404";
			const ClassNotFound = "ClassNotFound";
			const FileNotFound = "FileNotFound";
			const ControllerNotFound = "ControllerNotFound";
			const ViewNotFound = "ViewNotFound";
			const ModelNotFound = "ModelNotFound";
			const DatabaseConnectionFailed = "DatabaseConnectionFailed";
		}
	}