import { type ServerSecretGroup, type SecretGroup, serverToClientSecretGroup } from '~/repositories/adapters/secret-group-adapter'
import { serverToClientSecretPreview, type SecretPreview, type ServerSecretPreview } from '~/repositories/adapters/secret-adapter'

export interface ServerSecretGroupAggregate {
	current_group: ServerSecretGroup
	children_groups: ServerSecretGroup[]
	parent_groups: ServerSecretGroup[]
	secrets: ServerSecretPreview[]
}

export interface SecretGroupAggregate {
	currentGroup: SecretGroup
	childrenGroups: SecretGroup[]
	parentGroups: SecretGroup[]
	secrets: SecretPreview[]
}

export const serverToClientSecretGroupAggregate = (aggregate: ServerSecretGroupAggregate): SecretGroupAggregate => {
	return {
		currentGroup: serverToClientSecretGroup(aggregate.current_group),
		childrenGroups: aggregate.children_groups.map(g => serverToClientSecretGroup(g)),
		parentGroups: aggregate.parent_groups.map(g => serverToClientSecretGroup(g)),
		secrets: aggregate.secrets.map(s => serverToClientSecretPreview(s))
	}
}
