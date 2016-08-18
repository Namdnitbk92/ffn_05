<?php

if (!function_exists('is_admin')) {
	function is_admin () 
	{
		$user = auth()->user();
		if ($user !== null) {
			if ($user->role == config('common.roles.user')) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
}