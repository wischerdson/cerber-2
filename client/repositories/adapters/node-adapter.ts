export const transformNodeToClient = (node: App.Nodes.Server.Node): App.Nodes.Node => {
	return {
		id:          node.id,
		type:        node.type,
		alias:       node.alias,
		name:        node.name,
		notes:       node.notes,
		isEffective: node.is_effective,
		createdAt:   new Date(node.created_at * 1000),
		deletedAt:   node.deleted_at === null ? null : new Date(node.deleted_at * 1000)
	}
}

export const transformNewNodeToServer = (newNode: App.Nodes.NewNode): App.Nodes.Server.NewNode => {
	return {
		name:  newNode.name,
		notes: newNode.notes,
		type:  newNode.type
	}
}

export const transformGroupToClient = (group: App.Nodes.Server.Group): App.Nodes.Group => {
	return {
		...transformNodeToClient(group),
		type:     group.type,
		editMode: false
	}
}

export const transformNewDocumentFieldToServer = (field: App.Nodes.NewDocumentField): App.Nodes.Server.NewDocumentField => {
	return {
		label:             field.label,
		short_description: field.shortDescription,
		value:             field.value,
		is_multiline:      field.isMultiline,
		is_secure:         field.isSecure,
		sort:              field.sort
	}
}

export const transformNewDocumentToServer = (newDocument: App.Nodes.NewDocument): App.Nodes.Server.NewDocument => {
	return {
		name:   newDocument.name,
		notes:  newDocument.notes,
		type:   newDocument.type,
		fields: newDocument.fields.map(transformNewDocumentFieldToServer)
	}
}

export const transformNewGroupToServer = (newGroup: App.Nodes.NewGroup): App.Nodes.Server.NewGroup => {
	return {
		name:  newGroup.name,
		notes: newGroup.notes,
		type:  newGroup.type
	}
}
