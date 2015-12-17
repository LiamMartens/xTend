@layout:example
@compile:change
@version:1

@startsection:head
	@title:Example page
@endsection:head

@startsection:body
	@module(example)
	@({

		$app=xTend\getCurrentApp(__DIR__);
		$app->getModelHandler()->getModel()->function_call();

	})
@endsection:body