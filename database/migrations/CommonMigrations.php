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
}