import {Type} from 'main.core';

export class GreetingMessage
{
	constructor(options = {name: 'GreetingMessage'})
	{
		this.name = options.name;
	}

	setName(name)
	{
		if (Type.isString(name))
		{
			this.name = name;
		}
	}

	getName()
	{
		return this.name;
	}
}
