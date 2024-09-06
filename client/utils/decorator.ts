
interface AppRequest { send(): void }

const request: AppRequest = {
	send() {
		console.log('Sending request...');
	}
}



type EncryptDecoratedRequest<ObjectT, ReturnT extends EncryptDecoratedRequest<ObjectT, ReturnT>> = ObjectT & {
	shouldEncrypt(): ReturnT
}

const encryptRequestDecorator = <
	ReturnT extends EncryptDecoratedRequest<ObjectT, ReturnT>,
	ObjectT extends AppRequest
>(object: ObjectT): ReturnT => {
	const decorated = object as unknown as ReturnT

	decorated.shouldEncrypt = (): ReturnT => {
		return decorated
	}

	return decorated
}

type AuthDecoratedRequest<ObjectT> = ObjectT & {
	sign(): AuthDecoratedRequest<ObjectT>
}

const authRequestDecorator = <
	ReturnT extends AuthDecoratedRequest<ObjectT>,
	ObjectT extends AppRequest
>(object: ObjectT): ReturnT => {
	const decorated = object as unknown as ReturnT

	decorated.sign = (): ReturnT => {
		return decorated
	}

	return decorated
}


type FullyDecorated<ObjectT = AppRequest> = AuthDecoratedRequest<ObjectT> & EncryptDecoratedRequest<ObjectT, >


const dRequest = encryptRequestDecorator<FullyDecorated, AppRequest>(request)

dRequest.



// const dRequest = decorate(request, [encryptRequestDecorator, authRequestDecorator])

// dRequest.sign().shouldEncrypt().send()
// dRequest.shouldEncrypt().sign().send()




// type Decorator<ObjectT, ReturnT> = (object: ObjectT) => ReturnT

// type DecorationFactory = <ObjectT, ReturnT>(object: ObjectT, decorators: Decorator<ObjectT, ReturnT>[]) => ReturnT



// type RequestDecorator<ObjectT extends AppRequest, ReturnT extends RequestDecorator<ObjectT, ReturnT>> = Decorator<ObjectT, ReturnT & { sign: () => ReturnT }>

// type EncryptDecorator<ObjectT extends AppRequest, ReturnT extends EncryptDecorator<ObjectT, ReturnT>> = Decorator<ObjectT, ReturnT & { shouldEncrypt: () => ReturnT }>




// const requestDecorator: RequestDecorator




// type Decorator = <ObjectT, ReturnT>(object: ObjectT) => ReturnT

// export const decorate = <ObjectT, DecoratedObjectT extends ObjectT>(object: ObjectT, decorators: Decorator[]) => {
// 	let decoratedObject = object as unknown as DecoratedObjectT

// 	for (let i = decorators.length - 1; i >= 0; i--) {
// 	  decoratedObject = decorators[i]<ObjectT, DecoratedObjectT>(object)
// 	}

// 	return decoratedObject
// }





// type DecoratedRequest1<T extends AppRequest, ReturnT = DecoratedRequest1<T, unknown>> = T & { someMethod1: () => ReturnT }
// type DecoratedRequest2<T extends AppRequest> = T & { someMethod2: () => DecoratedRequest2<T> }

// const decorator1 = <ReturnT extends DecoratedRequest1<ObjectT>, ObjectT extends AppRequest>(object: ObjectT): ReturnT => {
// 	const decorated = object as unknown as ReturnT

// 	decorated.someMethod1 = () => {
// 		console.log('somMethod1')

// 		return decorated
// 	}

// 	return decorated
// }

// const decorator2 = <ReturnT extends DecoratedRequest2<ObjectT>, ObjectT extends AppRequest>(object: ObjectT): ReturnT => {
// 	const decorated = object as unknown as ReturnT

// 	decorated.someMethod2 = () => {
// 		console.log('somMethod1')

// 		return decorated
// 	}

// 	return decorated
// }

// const asd = decorate<AppRequest, DecoratedRequest1<AppRequest> & DecoratedRequest2<AppRequest>>(request, [decorator1, decorator2]) // decorator1(request)

// asd.someMethod1()



// asd.someMethod1().someMethod1().someMethod1()


// type FullyDecorated = DecoratedRequest1

// const ddd = decorate<AppRequest, DecoratedRequest1>(request, [decorator1])

// ddd.someMethod1()

// const decorate = <FullyDecoratedT>(object: ObjectT, decorators: ((object: ObjectT) => any)[]) => {
// 	return decorators.reduce((object, decorator) => decorator(object), object)
// }







// type Decorated<T extends Request> = DecoratedRequest1<T> & DecoratedRequest2<T>

// type DecoratedRequest1<T extends Request> = T & { someMethod1: () => Decorated<T> }
// type DecoratedRequest2<T extends Request> = T & { someMethod2: () => Decorated<T> }


// // Функция для добавления метода someMethod1
// const requestDecorator1 = <T extends Request>(request: T) => {
// 	const decorated = request as Decorated<T>

// 	decorated.someMethod1 = () => {
// 		return decorated
// 	}

// 	return decorated
// };

// // Функция для добавления метода someMethod2
// const requestDecorator2 = <T extends Request>(request: T) => {
// 	const decorated = request as Decorated<T>

// 	decorated.someMethod2 = () => {
// 		return decorated
// 	}

// 	return decorated
// };

// // Применяем оба декоратора последовательно
// const finalRequest = requestDecorator1(requestDecorator2(request));

// // Теперь оба метода работают независимо от их порядка
// finalRequest.someMethod1().someMethod2().send();
// finalRequest.someMethod2().someMethod1().send();
