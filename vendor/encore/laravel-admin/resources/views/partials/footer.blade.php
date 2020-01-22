<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! config('app.env') !!}
            &nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        

        @if(config('admin.show_admin_version'))
        <strong>Admin Version</strong>&nbsp;&nbsp; {!! \Encore\Admin\Admin::VERSION !!}
        &nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        @if(config('admin.show_app_version'))
        <strong>Version</strong>&nbsp;&nbsp; {!! config('admin.app_version') !!}
        @endif

    </div>
    <!-- Default to the left -->
    <strong>Powered by <a href="https://akonto.ltd" target="_blank">akonto</a></strong>
</footer>