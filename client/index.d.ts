/**
 * type A = { prop: string }
 * type B = SubstituteType<A, number>
 *
 * Type B have a "prop" with a value of type number
 */
type SubstituteType<T, B> = T extends object ? { [K in keyof T]: SubstituteType<T[K], B> } : B

/**
 * const array = [1, 'a', true]
 * type MixedArrayElement = ElementType<typeof array> // number | string | boolean
 */
type ElementType<T> = T extends Array<infer U> ? U : never;
