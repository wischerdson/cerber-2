export const transformNodeToClient = (node: Dto.Nodes.Server.Node): Dto.Nodes.Node => {
	return {
		id:              node.id,
		type:            node.type,
		alias:           node.alias,
		name:            node.name,
		notes:           node.notes,
		isEffective:     node.is_effective,
		changedOnClient: false,
		createdAt:       new Date(node.created_at * 1000),
		deletedAt:       node.deleted_at === null ? null : new Date(node.deleted_at * 1000)
	}
}

export const transformNodeResourceToClient = (resource: Dto.Nodes.Server.NodeResource): Dto.Nodes.NodeResource => {
	return {
		current:     resource.current === null ? null : transformNodeToClient(resource.current),
		parents:     resource.parents.map(transformNodeToClient),
		descendants: resource.descendants.map(transformNodeToClient)
	}
}

export const transformNodeForUpdateToServer = (node: Dto.Nodes.Node): Dto.Nodes.Server.NodeForUpdate => {
	return {
		id:           node.id,
		name:         node.name,
		notes:        node.notes,
		is_effective: node.isEffective
	}
}

export const transformNewNodeToServer = (newNode: Dto.Nodes.NewNode): Dto.Nodes.Server.NewNode => {
	return {
		name:        newNode.name,
		notes:       newNode.notes,
		type:        newNode.type,
		client_code: newNode.clientCode
	}
}

export const transformGroupToClient = (group: Dto.Nodes.Server.Group): Dto.Nodes.Group => {
	return {
		...transformNodeToClient(group),
		type:     group.type,
		editMode: false
	}
}

export const transformNewDocumentToServer = (newDocument: Dto.Nodes.NewDocument): Dto.Nodes.Server.NewDocument => {
	return {
		...transformNewNodeToServer(newDocument),
		type:   newDocument.type,
		fields: newDocument.fields.map(transformNewDocumentFieldToServer)
	}
}

export const transformNewDocumentFieldToServer = (field: Dto.Nodes.NewDocumentField): Dto.Nodes.Server.NewDocumentField => {
	return {
		label:             field.label,
		short_description: field.shortDescription,
		value:             field.value,
		is_multiline:      field.isMultiline,
		is_secure:         field.isSecure,
		sort:              field.sort
	}
}

export const transformNewGroupToServer = (newGroup: Dto.Nodes.NewGroup): Dto.Nodes.Server.NewGroup => {
	return {
		...transformNewNodeToServer(newGroup),
		type: newGroup.type
	}
}

export const transformCreatedNodeToClient = (node: Dto.Nodes.Server.CreatedNode): Dto.Nodes.CreatedNode => {
	return {
		...transformNodeToClient(node),
		clientCode: node.client_code
	}
}
