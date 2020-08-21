<?php

class CommonMigrations
{
    public static function commonColumns($table)
    {
        //$table->boolean('status')->default(true)->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->timestamps();
    }

    public static function addMenu($parent_id, $order, $title, $icon, $uri, $permission_slug)
    {
        $id = DB::table('admin_menu')
            ->insertGetId([
                'parent_id' => $parent_id,
                'order' => $order,
                'title' => $title,
                'icon' => $icon,
                'uri' => $uri,
                'permission' => $permission_slug
            ]);

        return $id;
    }

    public static function removeMenu($order, $title)
    {
        DB::table('admin_menu')
            ->where('order', $order)
            ->where('title', $title)
            ->delete();
    }

    public static function removeMenuByOrderRange($order_from, $order_to)
    {
        DB::table('admin_menu')
            ->whereBetween('order', [$order_from, $order_to])
            ->delete();

    }

    public static function removeEntityPermissions($singular_slug, $plural_slug)
    {
        DB::table('admin_permissions')->where('slug', $plural_slug . '_list')->delete();
        DB::table('admin_permissions')->where('slug', 'add_' . $singular_slug)->delete();
        DB::table('admin_permissions')->where('slug', 'edit_' . $singular_slug)->delete();
        DB::table('admin_permissions')->where('slug', 'delete_' . $singular_slug)->delete();
    }

    public static function insertEntityPermissions($singular_title, $plural_title, $singular_slug, $plural_slug, $resource_url)
    {
        DB::table('admin_permissions')
            ->insert([
                [
                    'name' => $plural_title . ' List',
                    'slug' => $plural_slug . '_list',
                    'http_method' => 'GET',
                    'http_path' => $resource_url,
                ],
                [
                    'name' => 'Add ' . $singular_title,
                    'slug' => 'add_' . $singular_slug,
                    'http_method' => 'GET,POST',
                    'http_path' => '/' . $resource_url . '/create' . "\r\n" . '/' . $resource_url,
                ],
                [
                    'name' => 'Edit ' . $singular_title,
                    'slug' => 'edit_' . $singular_slug,
                    'http_method' => 'GET,PUT',
                    'http_path' => '/' . $resource_url . '/*/edit' . "\r\n" . '/' . $resource_url . '/*',
                ],
                [
                    'name' => 'Delete ' . $singular_title,
                    'slug' => 'Delete_' . $singular_slug,
                    'http_method' => 'DELETE',
                    'http_path' => '/' . $resource_url . '/*',
                ],
            ]);
    }

    public static function insertPermission($name, $slug, $http_method, $http_path)
    {
        DB::table('admin_permissions')
            ->insert([
                [
                    'name' => $name,
                    'slug' => $slug,
                    'http_method' => $http_method,
                    'http_path' => $http_path,
                ]
            ]);
    }
}