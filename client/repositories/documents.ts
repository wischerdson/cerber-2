import type { NewDocument, ServerDocument } from './adapters/document-adapter'
import { usePostReq } from '#imports'
import { auth } from '~/utils/decorators/request/auth.decorator'
import { transformDocumentToClient, transformNewDocumentToServer } from './adapters/document-adapter'
import { encrypt } from '~/utils/decorators/request/encryption.decorator'

export const createDocument = async (newDocument: NewDocument) => {
	const document = await usePostReq<ServerDocument>()
		.url('/documents')
		.body(transformNewDocumentToServer(newDocument))
		.apply(auth, encrypt)
		.send()

	return transformDocumentToClient(document)
}
