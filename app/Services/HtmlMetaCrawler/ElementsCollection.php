<?php

namespace App\Services\HtmlMetaCrawler;

class ElementsCollection
{
	public ?Element $html = null;

	public ?Element $title = null;

	/** @var \App\Services\HtmlMetaCrawler\Element[] */
	public array $meta = [];

	/** @var \App\Services\HtmlMetaCrawler\Element[] */
	public array $link = [];

	public static function init(): self
	{
		return new self();
	}

	public function setHtml(Element $html): self
	{
		$this->html = $html;

		return $this;
	}

	public function setTitle(Element $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function addMeta(Element $meta): self
	{
		$this->meta[] = $meta;

		return $this;
	}

	public function addLink(Element $link): self
	{
		$this->link[] = $link;

		return $this;
	}
}
