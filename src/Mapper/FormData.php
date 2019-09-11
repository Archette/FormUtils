<?php

declare(strict_types=1);

namespace Archette\FormUtils\Mapper;

use Doctrine\ORM\PersistentCollection;

class FormData
{
	public function __construct(object $data)
	{
		foreach ($data as $key => $value) {
			if (is_object($value) && method_exists($value, 'getId')) {
				$this->{$key} = (string) $value->getId();

			} elseif (is_array($value) || $value instanceof PersistentCollection) {
				$array = [];
				foreach ($value as $item) {
					if (is_object($item) && method_exists($item, 'getId')) {
						$array[] = (string) $item->getId();
					}
				}
				$this->{$key} = $array;

			} else {
				$this->{$key} = $value;
			}
		}
	}
}
