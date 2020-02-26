<?php 
$tenant = Auth::user()->tenant;
$menu = [];
$menu[] = ["label"=>"Dashboard","icon"=>"el-icon-s-home","url"=>route('admin.home'), "active" => Menu::isActive('admin.home')];
foreach(ResourcesHelpers::all() as $group=>$resources)
{
    $groups = ["label"=>$group, "items" => []];
    foreach($resources as $resource)
    {
        if($resource->canViewList())
        {
            $groups["icon"] = $resource->menuIcon();
            $groups["items"][] = ["active"=>Menu::ResourceIsActive($resource->id),"url"=>$resource->route(),"icon"=>$resource->icon(),"label"=>$resource->label()];
        }
    }
    $menu[] = $groups;
}
$logo = ["src"=> @$tenant->big_logo[0] ? $tenant->big_logo[0] : asset('assets/images/big_logo.png'),"href"=>route('admin.home')];
$smalllogo = ["src"=> @$tenant->small_logo[0] ? $tenant->small_logo[0] : asset('assets/images/logo.png'),"href"=>route('admin.home')];
?>
<template-sidebar  :logo="{{json_encode($logo)}}"  :smalllogo="{{json_encode($smalllogo)}}" :menu="{{json_encode($menu)}}"></template-sidebar>