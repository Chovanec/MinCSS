<?php

function mincss_load ($file)
{
	$key_pattern = '#({{)\s*\w+\s*:[^}]+\s*(}})#';
	$ref_pattern = '#({{)\w+(}})#';

	$data = file_get_contents($file);

	/**
	 * Extract keys from file
	 */

	$keys = null;
	preg_match_all ($key_pattern, $data, $keys, PREG_PATTERN_ORDER);
	$keys = $keys[0];
	$keys_temp = null;

	foreach ($keys as $key => $value)
	{
		$temp = str_replace ('{{', '', $value);
		$temp = str_replace ('}}', '', $temp);
		$temp = explode (':', $temp, 2);
		$key = trim($temp[0], " \t\n\r\0\x0B");
		$value = trim($temp[1], " \t\n\r\0\x0B");
		$keys_temp[$key] = $value;
	}

	$keys = $keys_temp;

	/**
	 * Remove keys definitions
	 */
	foreach ($keys as $key => $value)
	{
		$data = preg_replace($key_pattern, '', $data);
	}

	/**
	 * Replace variables with defined values
	 */
	foreach ($keys as $key => $value)
	{
		$data = str_replace('{{' . $key . '}}', $value, $data);
		$data = str_replace('$' . $key, $value, $data);
	}

	/**
	 * Remove multiple line-breaks
	 */
	$data = preg_replace("/[\r\n]{3,}/", "\n\n", $data);

	/**
	 * Render as CSS
	 */
	header('Content-Type: text/css');
	echo $data;

	/**
	 * Return true on success
	 */
	return true;
}
