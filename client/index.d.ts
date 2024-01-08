/**
 * type A = { prop: string }
 * type B = SubstituteType<A, number>
 *
 * Type B have a "prop" with a value of type number
 */
type SubstituteType<T, B> = T extends object ? { [K in keyof T]: SubstituteType<T[K], B> } : B
