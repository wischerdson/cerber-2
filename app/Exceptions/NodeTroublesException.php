<?php

namespace App\Exceptions;

class NodeTroublesException extends BadRequestException
{
	public static function groupNotFound(string $idOrAlias): self
	{
		return self::message("Node of type group by {$idOrAlias} not found")
			->httpCode(404);
	}

	public static function missingAliasOrId(): self
	{
		return self::message('Either the ID or alias of the node is required');
	}

	public static function nodeChainIsIncorrect(): self
	{
		return self::message('Node chain is incorrect')->reason('incorrect_node_chain');
	}
}
