<?php

return [
	"default_upload_route" => "/admin/upload",
	"resource_export_extension" => "xlsx",
	"extra_javascript_global_variables" => [],
	"upload_disk" => "s3",
	"prepend_breadcrumb" => [
		"Início" => "/admin"
	],
	"socket_service" => [
		"port" => env('SOCKET_PORT_SERVER_PORT', "3003"),
		"uri" => env("APP_URL"),
		"enabled" => env('SOCKET_SERVER_ENABLED', false),
	],
	"api" => [
		"token_expiration" => "1 hour",
	],
	"animation" => [
		"enabled" => false,
	],
];
