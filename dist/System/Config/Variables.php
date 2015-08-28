<?php
	namespace xTend
	{
		Variables::Set('app.root', substr(__DIR__, 0, strlen(__DIR__) - 14));
		Variables::Set('app.web', Variables::Get('app.root').'\public');
		Variables::Set('app.system', Variables::Get('app.root').'\System');
	}