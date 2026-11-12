<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @param mixed $ref
 */
function client_ref_is_valid($ref): bool
{
	if (!is_string($ref)) {
		return false;
	}

	$ref = trim($ref);
	if ($ref === '') {
		return false;
	}

	return (bool) preg_match(
		'/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
		$ref
	);
}

/**
 * @param mixed $ref
 */
function client_ref_normalize($ref): ?string
{
	if (!client_ref_is_valid($ref)) {
		return null;
	}

	return strtolower(trim($ref));
}
