import type { AppRequest } from '~/utils/request.types'
import type { AuthDecoratedRequest } from '~/decorators/request/auth.request-decorator'
import type { EncryptDecoratedRequest } from '~/decorators/request/encryption.request-decorator'

export { decorator as authenticationRequest } from '~/decorators/request/auth.request-decorator'
export { decorator as encryptionRequest } from '~/decorators/request/encryption.request-decorator'
export { decorator as attachingHandshakeId } from '~/decorators/request/handshaking.request-decorator'
export { decorator as decryptionResponse } from '~/decorators/request/decryption.request-decorator'

export type DecoratedRequest<T extends AppRequest> = AuthDecoratedRequest<T> & EncryptDecoratedRequest<T>
