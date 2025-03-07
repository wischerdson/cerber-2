import { transformDocumentToClient, type Document, type ServerDocument } from '~/repositories/adapters/document-adapter'

export interface ServerDocumentGroupAggregate {
	parents: ServerDocument[]
	current: ServerDocument
	descendants: ServerDocument[]
}

export interface DocumentGroupAggregate {
	parents: Document[]
	current: Document
	descendants: Document[]
}

export const transformDocumentGroupAggregateToClient = (aggregate: ServerDocumentGroupAggregate): DocumentGroupAggregate => {
	return {
		parents: aggregate.parents.map(d => transformDocumentToClient(d)),
		current: transformDocumentToClient(aggregate.current),
		descendants: aggregate.descendants.map(d => transformDocumentToClient(d)),
	}
}
