// const decorate = <FullyDecoratedT>(object: ObjectT, decorators: ((object: ObjectT) => any)[]) => {
//  return decorators.reduce((object, decorator) => decorator(object), object)
// }




interface Request {
	send(): void;
}

const request: Request = {
	send() {
		console.log('Sending request...');
	}
};


type Decorated<T extends Request> = DecoratedRequest1<T> & DecoratedRequest2<T>

type DecoratedRequest1<T extends Request> = T & { someMethod1: () => Decorated<T> }
type DecoratedRequest2<T extends Request> = T & { someMethod2: () => Decorated<T> }


// Функция для добавления метода someMethod1
const requestDecorator1 = <T extends Request>(request: T) => {
	const decorated = request as Decorated<T>

	decorated.someMethod1 = () => {
		return decorated
	}

	return decorated
};

// Функция для добавления метода someMethod2
const requestDecorator2 = <T extends Request>(request: T) => {
	const decorated = request as Decorated<T>

	decorated.someMethod2 = () => {
		return decorated
	}

	return decorated
};

// Применяем оба декоратора последовательно
const finalRequest = requestDecorator1(requestDecorator2(request));

// Теперь оба метода работают независимо от их порядка
finalRequest.someMethod1().someMethod2().send();
finalRequest.someMethod2().someMethod1().send();
