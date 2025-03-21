import type { AppRequest } from '~/utils/request.types'
import type { AuthDecoratedRequest } from '~/decorators/request/auth.decorator'
import type { EncryptDecoratedRequest } from '~/decorators/request/encryption.decorator'

export { decorator as authenticationRequest } from '~/decorators/request/auth.decorator'
export { decorator as encryptionRequest } from '~/decorators/request/encryption.decorator'
export { decorator as attachingHandshakeId } from '~/decorators/request/handshaking.decorator'
export { decorator as decryptionResponse } from '~/decorators/request/decryption.decorator'

export type DecoratedRequest = AuthDecoratedRequest & EncryptDecoratedRequest
