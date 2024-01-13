import type { Schema, InferType, ValidationError, ObjectSchema, AnyObject } from 'yup'
import { set, get } from 'lodash-es'
import { useState, watch } from '#imports'
import { uid } from '~/utils/helpers'

export interface FieldContext<T> {
	isDirty: () => boolean
	hasErrors: () => boolean
	getError: () => string | null
	touch(): void
	clear(): void
	appendError(value: string): void
	clearErrors(): void
	setValue(value: T): void
	getValue(): T
}

interface FieldState<T> {
	isDirty: boolean
	model: T
	errors: string[]
}

export interface ValidationContext<T extends ObjectSchema<AnyObject>> {
	defineRules<R extends ObjectSchema<AnyObject>>(rules: R): ValidationContext<R>
	useField(path: string): FieldContext<any>
	getYupSchemaInstance(): T
	touchAll(): void
	clearAll(): void
	validate(): Promise<any>
}

interface ValidationState<T extends Schema<object> = Schema<object>, I = InferType<T>> {
	rules?: ObjectSchema<AnyObject>
	model: I
	fields: { [path: string]: FieldContext<any> }
}

const makeField = <T>(initial: T): FieldContext<T> => {
	const state = useState<FieldState<T>>(uid(), () => {
		return {
			isDirty: false,
			model: initial,
			errors: []
		}
	})

	const touch = () => state.value.isDirty = true
	const clear = () => state.value.isDirty = false
	const isDirty = () => state.value.isDirty
	const hasErrors = () => state.value.isDirty && state.value.errors.length > 0
	const getError = () => hasErrors() ? state.value.errors[0] : null
	const setValue = (value: T) => state.value.model = value
	const getValue = () => state.value.model
	const clearErrors = () => state.value.errors = []
	const appendError = (value: string) => state.value.errors.push(value)

	return {
		touch, clear, appendError, clearErrors, isDirty, hasErrors, getError, setValue, getValue
	}
}

export const useValidation = (): ValidationContext<any> => {
	const state: ValidationState = {
		rules: void 0,
		model: {},
		fields: {}
	}

	const context: ValidationContext<any> = {
		useField(path) {
			if (path in state.fields) {
				return state.fields[path]
			}

			const field = makeField(get(state.model, path) || '')

			watch(() => field.getValue(), value => {
				set(state.model, path, value)
				field.isDirty() && context.validate()
			})

			watch(() => field.isDirty(), v => v && context.validate())

			return state.fields[path] = field
		},
		validate() {
			if (!state.rules) {
				throw new Error('Validation rules is not defined')
			}

			Object.values(state.fields).forEach(field => field.clearErrors())

			return state.rules.validate(state.model, { abortEarly: false })
				.catch((e: ValidationError) => {
					e.inner.forEach(error => {
						if (typeof error.path === 'string' && error.path in state.fields) {
							state.fields[error.path].appendError(error.message)
						}
					})
				})
		},
		defineRules(rules) {
			state.rules = rules
			state.model = rules.getDefault()

			return context
		},
		getYupSchemaInstance() {
			return state.rules
		},
		touchAll() {
			Object.values(state.fields).forEach(field => field.touch())
		},
		clearAll() {
			Object.values(state.fields).forEach(field => field.clear())
		}
	}

	return context
}
